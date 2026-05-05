<?php

namespace App\Http\Controllers\Web\Payments;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentEvent;
use App\Services\Commerce\PaymentGatewayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckoutPaymentController extends Controller
{
    private $paymentGatewayService;

    public function __construct(PaymentGatewayService $paymentGatewayService)
    {
        $this->paymentGatewayService = $paymentGatewayService;
    }

    public function createSession(Request $request, Order $order): JsonResponse
    {
        if ($order->user_id !== $request->user()->id) {
            abort(403);
        }

        if ($order->payment_status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Order already paid.',
            ], 422);
        }

        $gateway = $request->validate([
            'gateway' => 'required|in:razorpay',
        ])['gateway'];

        $result = $this->paymentGatewayService->createRazorpayOrder($order);

        PaymentEvent::create([
            'order_id' => $order->id,
            'gateway' => $gateway,
            'event_type' => 'checkout.session.created',
            'external_reference' => $result['data']['id'] ?? null,
            'status' => $result['success'] ? 'created' : 'failed',
            'amount' => $order->total,
            'currency' => 'INR',
            'signature_valid' => true,
            'note' => $result['message'] ?? null,
            'payload' => $result,
        ]);

        if (! $result['success']) {
            return response()->json($result, 422);
        }

        $order->update([
            'payment_gateway' => $gateway,
            'payment_reference' => $result['data']['id'] ?? $order->payment_reference,
            'payment_meta' => $result['data'] ?? null,
            'payment_status' => 'pending',
        ]);

        return response()->json($result);
    }

    public function verify(Request $request, Order $order): JsonResponse
    {
        if ($order->user_id !== $request->user()->id) {
            abort(403);
        }

        $payload = $request->validate([
            'razorpay_order_id' => 'required|string|max:120',
            'razorpay_payment_id' => 'required|string|max:120',
            'razorpay_signature' => 'required|string|max:255',
        ]);

        $isValid = $this->paymentGatewayService->verifyRazorpayCheckoutSignature(
            $payload['razorpay_order_id'],
            $payload['razorpay_payment_id'],
            $payload['razorpay_signature']
        );

        PaymentEvent::create([
            'order_id' => $order->id,
            'gateway' => 'razorpay',
            'event_type' => 'checkout.payment.verify',
            'external_reference' => $payload['razorpay_payment_id'],
            'status' => $isValid ? 'paid' : 'signature_invalid',
            'amount' => $order->total,
            'currency' => 'INR',
            'signature_valid' => $isValid,
            'note' => $isValid ? 'Client-side payment verification succeeded' : 'Invalid Razorpay checkout signature',
            'payload' => $payload,
        ]);

        if (! $isValid) {
            return response()->json([
                'success' => false,
                'message' => 'Payment signature verification failed.',
            ], 422);
        }

        $order->update([
            'payment_status' => 'paid',
            'payment_gateway' => 'razorpay',
            'payment_reference' => $payload['razorpay_order_id'],
            'payment_meta' => array_merge($order->payment_meta ?? [], [
                'razorpay_payment_id' => $payload['razorpay_payment_id'],
                'razorpay_signature' => $payload['razorpay_signature'],
            ]),
            'status' => in_array($order->status, ['pending', 'failed'], true) ? 'processing' : $order->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment verified successfully.',
        ]);
    }
}
