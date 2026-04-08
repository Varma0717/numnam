<?php

namespace App\Services\Commerce;

use App\Models\Order;
use Illuminate\Support\Facades\Http;

class PaymentGatewayService
{
    public function createRazorpayOrder(Order $order): array
    {
        $keyId = (string) env('RAZORPAY_KEY_ID');
        $keySecret = (string) env('RAZORPAY_KEY_SECRET');

        if (! $keyId || ! $keySecret) {
            return [
                'success' => false,
                'message' => 'Razorpay credentials are not configured.',
            ];
        }

        $response = Http::withBasicAuth($keyId, $keySecret)
            ->post('https://api.razorpay.com/v1/orders', [
                'amount' => (int) round(((float) $order->total) * 100),
                'currency' => 'INR',
                'receipt' => $order->order_number,
                'notes' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                ],
            ]);

        if (! $response->successful()) {
            return [
                'success' => false,
                'message' => 'Unable to create Razorpay order.',
                'response' => $response->json(),
            ];
        }

        return [
            'success' => true,
            'gateway' => 'razorpay',
            'data' => $response->json(),
            'publishable_key' => $keyId,
        ];
    }

    public function verifyRazorpayWebhook(string $payload, string $signature): bool
    {
        $secret = (string) env('RAZORPAY_WEBHOOK_SECRET');
        if (! $secret || ! $signature) {
            return false;
        }

        $expected = hash_hmac('sha256', $payload, $secret);

        return hash_equals($expected, $signature);
    }
}
