<?php

namespace App\Services\Commerce;

use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PaymentGatewayService
{
    public function createRazorpayOrder(Order $order): array
    {
        $keyId = (string) config('services.razorpay.key_id', '');
        $keySecret = (string) config('services.razorpay.key_secret', '');

        if (! $keyId || ! $keySecret) {
            return [
                'success' => false,
                'message' => 'Razorpay credentials are not configured.',
            ];
        }

        try {
            $response = Http::withBasicAuth($keyId, $keySecret)
                ->acceptJson()
                ->timeout(15)
                ->post('https://api.razorpay.com/v1/orders', [
                    'amount' => (int) round(((float) $order->total) * 100),
                    'currency' => 'INR',
                    'receipt' => $order->order_number,
                    'notes' => [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                    ],
                ]);
        } catch (\Throwable $e) {
            Log::error('Razorpay order create failed', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Unable to connect to Razorpay right now. Please try again shortly.',
            ];
        }

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
        $secret = (string) config('services.razorpay.webhook_secret', '');
        if (! $secret || ! $signature) {
            return false;
        }

        $expected = hash_hmac('sha256', $payload, $secret);

        return hash_equals($expected, $signature);
    }

    public function verifyRazorpayCheckoutSignature(string $razorpayOrderId, string $razorpayPaymentId, string $razorpaySignature): bool
    {
        $keySecret = (string) config('services.razorpay.key_secret', '');

        if (! $keySecret || ! $razorpayOrderId || ! $razorpayPaymentId || ! $razorpaySignature) {
            return false;
        }

        $payload = $razorpayOrderId . '|' . $razorpayPaymentId;
        $expected = hash_hmac('sha256', $payload, $keySecret);

        return hash_equals($expected, $razorpaySignature);
    }
}
