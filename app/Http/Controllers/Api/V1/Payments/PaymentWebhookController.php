<?php

namespace App\Http\Controllers\Api\V1\Payments;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentEvent;
use App\Services\Commerce\PaymentGatewayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentWebhookController extends Controller
{
    private PaymentGatewayService $paymentGatewayService;

    public function __construct(PaymentGatewayService $paymentGatewayService)
    {
        $this->paymentGatewayService = $paymentGatewayService;
    }

    public function razorpay(Request $request): JsonResponse
    {
        $payload = $request->getContent();
        $signature = (string) $request->header('X-Razorpay-Signature');
        $valid = $this->paymentGatewayService->verifyRazorpayWebhook($payload, $signature);
        $data = $request->json()->all();
        $eventType = (string) ($data['event'] ?? 'unknown');

        if (! $valid) {
            $this->recordInvalidEvent('razorpay', $eventType, $data);
            return response()->json(['error' => 'Invalid signature.'], 403);
        }

        $entity = $data['payload']['payment']['entity'] ?? [];
        $externalOrderId = $entity['order_id'] ?? null;
        $paymentId = $entity['id'] ?? null;
        $status = (string) ($entity['status'] ?? 'unknown');
        $amount = isset($entity['amount']) ? ((float) $entity['amount'] / 100) : null;

        $order = $externalOrderId
            ? Order::query()->where('payment_reference', $externalOrderId)->first()
            : null;

        $fingerprint = $this->buildFingerprint('razorpay', $eventType, $paymentId ?: $externalOrderId, $data);
        $created = $this->recordEventIfNew([
            'order_id' => $order?->id,
            'gateway' => 'razorpay',
            'event_type' => $eventType,
            'external_reference' => $paymentId ?: $externalOrderId,
            'fingerprint' => $fingerprint,
            'status' => $status,
            'amount' => $amount,
            'currency' => 'INR',
            'signature_valid' => true,
            'payload' => $data,
        ]);

        if ($created && $order) {
            $this->reconcileOrderPaymentState(
                $order,
                'razorpay',
                $paymentId ?: $externalOrderId,
                $eventType,
                ['payment.captured', 'order.paid'],
                ['payment.failed']
            );
        }

        return response()->json(['received' => true]);
    }

    public function stripe(Request $request): JsonResponse
    {
        $payload = $request->getContent();
        $signature = (string) $request->header('Stripe-Signature');
        $valid = $this->paymentGatewayService->verifyStripeWebhook($payload, $signature);
        $data = $request->json()->all();
        $eventType = (string) ($data['type'] ?? 'unknown');

        if (! $valid) {
            $this->recordInvalidEvent('stripe', $eventType, $data);
            return response()->json(['error' => 'Invalid signature.'], 403);
        }

        $object = $data['data']['object'] ?? [];
        $intentId = $object['id'] ?? null;
        $metadata = $object['metadata'] ?? [];
        $orderId = isset($metadata['order_id']) ? (int) $metadata['order_id'] : null;
        $status = (string) ($object['status'] ?? 'unknown');
        $amount = isset($object['amount_received']) ? ((float) $object['amount_received'] / 100) : null;

        $order = $orderId ? Order::query()->find($orderId) : null;

        $fingerprint = $this->buildFingerprint('stripe', $eventType, (string) ($data['id'] ?? $intentId), $data);
        $created = $this->recordEventIfNew([
            'order_id' => $order?->id,
            'gateway' => 'stripe',
            'event_type' => $eventType,
            'external_reference' => $intentId,
            'fingerprint' => $fingerprint,
            'status' => $status,
            'amount' => $amount,
            'currency' => strtoupper((string) ($object['currency'] ?? 'INR')),
            'signature_valid' => true,
            'payload' => $data,
        ]);

        if ($created && $order) {
            $this->reconcileOrderPaymentState(
                $order,
                'stripe',
                $intentId,
                $eventType,
                ['payment_intent.succeeded', 'charge.succeeded'],
                ['payment_intent.payment_failed', 'charge.failed']
            );
        }

        return response()->json(['received' => true]);
    }

    private function recordInvalidEvent(string $gateway, string $eventType, array $payload): void
    {
        PaymentEvent::query()->create([
            'gateway' => $gateway,
            'event_type' => $eventType,
            'fingerprint' => $this->buildFingerprint($gateway, $eventType, 'invalid-signature', $payload),
            'signature_valid' => false,
            'payload' => $payload,
        ]);
    }

    private function recordEventIfNew(array $data): bool
    {
        if (! empty($data['fingerprint'])) {
            $existing = PaymentEvent::query()->where('fingerprint', $data['fingerprint'])->first();
            if ($existing) {
                return false;
            }
        }

        PaymentEvent::query()->create($data);
        return true;
    }

    private function buildFingerprint(string $gateway, string $eventType, ?string $eventKey, array $payload): string
    {
        $normalizedPayload = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return hash('sha256', implode('|', [
            $gateway,
            $eventType,
            (string) ($eventKey ?? ''),
            (string) ($normalizedPayload ?: ''),
        ]));
    }

    private function reconcileOrderPaymentState(
        Order $order,
        string $gateway,
        ?string $externalReference,
        string $eventType,
        array $paidEvents,
        array $failedEvents
    ): void {
        if (in_array($eventType, $paidEvents, true)) {
            $order->update([
                'payment_status' => 'paid',
                'status' => in_array($order->status, ['pending', 'failed'], true) ? 'processing' : $order->status,
                'payment_gateway' => $gateway,
                'payment_reference' => $externalReference ?: $order->payment_reference,
            ]);
            return;
        }

        // Never downgrade a paid order to failed due to out-of-order webhook delivery.
        if (in_array($eventType, $failedEvents, true) && $order->payment_status !== 'paid') {
            $order->update([
                'payment_status' => 'failed',
                'payment_gateway' => $gateway,
            ]);
        }
    }
}
