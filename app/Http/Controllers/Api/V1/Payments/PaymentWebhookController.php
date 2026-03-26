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
    private $paymentGatewayService;

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

        if (!$valid) {
            PaymentEvent::create([
                'gateway' => 'razorpay',
                'event_type' => (string) ($data['event'] ?? 'unknown'),
                'signature_valid' => false,
                'payload' => $data,
            ]);
            return response()->json(['error' => 'Invalid signature.'], 403);
        }

        $eventType = (string) ($data['event'] ?? 'unknown');
        $entity = $data['payload']['payment']['entity'] ?? [];
        $externalOrderId = $entity['order_id'] ?? null;
        $paymentId = $entity['id'] ?? null;
        $status = $entity['status'] ?? null;
        $amount = isset($entity['amount']) ? ((float) $entity['amount'] / 100) : null;

        $order = null;
        if ($externalOrderId) {
            $order = Order::query()->where('payment_reference', $externalOrderId)->first();
        }

        PaymentEvent::create([
            'order_id' => $order?->id,
            'gateway' => 'razorpay',
            'event_type' => $eventType,
            'external_reference' => $paymentId ?: $externalOrderId,
            'status' => $status,
            'amount' => $amount,
            'currency' => 'INR',
            'signature_valid' => true,
            'payload' => $data,
        ]);

        if ($order && in_array($eventType, ['payment.captured', 'order.paid'], true)) {
            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing',
                'payment_gateway' => 'razorpay',
                'payment_reference' => $externalOrderId,
            ]);
        }

        if ($order && in_array($eventType, ['payment.failed'], true)) {
            $order->update([
                'payment_status' => 'failed',
                'payment_gateway' => 'razorpay',
            ]);
        }

        return response()->json(['received' => true]);
    }

    public function stripe(Request $request): JsonResponse
    {
        $payload = $request->getContent();
        $signature = (string) $request->header('Stripe-Signature');
        $valid = $this->paymentGatewayService->verifyStripeWebhook($payload, $signature);
        $data = $request->json()->all();

        if (!$valid) {
            PaymentEvent::create([
                'gateway' => 'stripe',
                'event_type' => (string) ($data['type'] ?? 'unknown'),
                'signature_valid' => false,
                'payload' => $data,
            ]);
            return response()->json(['error' => 'Invalid signature.'], 403);
        }

        $eventType = (string) ($data['type'] ?? 'unknown');
        $object = $data['data']['object'] ?? [];
        $intentId = $object['id'] ?? null;
        $metadata = $object['metadata'] ?? [];
        $orderId = isset($metadata['order_id']) ? (int) $metadata['order_id'] : null;
        $status = $object['status'] ?? null;
        $amount = isset($object['amount_received']) ? ((float) $object['amount_received'] / 100) : null;

        $order = null;
        if ($orderId) {
            $order = Order::query()->find($orderId);
        }

        PaymentEvent::create([
            'order_id' => $order?->id,
            'gateway' => 'stripe',
            'event_type' => $eventType,
            'external_reference' => $intentId,
            'status' => $status,
            'amount' => $amount,
            'currency' => strtoupper((string) ($object['currency'] ?? 'INR')),
            'signature_valid' => true,
            'payload' => $data,
        ]);

        if ($order && in_array($eventType, ['payment_intent.succeeded', 'charge.succeeded'], true)) {
            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing',
                'payment_gateway' => 'stripe',
                'payment_reference' => $intentId,
            ]);
        }

        if ($order && in_array($eventType, ['payment_intent.payment_failed', 'charge.failed'], true)) {
            $order->update([
                'payment_status' => 'failed',
                'payment_gateway' => 'stripe',
            ]);
        }

        return response()->json(['received' => true]);
    }
}
