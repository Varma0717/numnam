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
            'gateway' => 'required|in:razorpay,stripe',
        ])['gateway'];

        $result = $gateway === 'razorpay'
            ? $this->paymentGatewayService->createRazorpayOrder($order)
            : $this->paymentGatewayService->createStripePaymentIntent($order);

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
}
