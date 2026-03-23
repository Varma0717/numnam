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

    public function createStripePaymentIntent(Order $order): array
    {
        $secret = (string) env('STRIPE_SECRET_KEY');
        $publishable = (string) env('STRIPE_PUBLISHABLE_KEY');

        if (! $secret || ! $publishable) {
            return [
                'success' => false,
                'message' => 'Stripe credentials are not configured.',
            ];
        }

        $response = Http::withToken($secret)
            ->asForm()
            ->post('https://api.stripe.com/v1/payment_intents', [
                'amount' => (int) round(((float) $order->total) * 100),
                'currency' => 'inr',
                'metadata[order_id]' => (string) $order->id,
                'metadata[order_number]' => $order->order_number,
                'description' => 'NumNam order ' . $order->order_number,
            ]);

        if (! $response->successful()) {
            return [
                'success' => false,
                'message' => 'Unable to create Stripe payment intent.',
                'response' => $response->json(),
            ];
        }

        return [
            'success' => true,
            'gateway' => 'stripe',
            'data' => $response->json(),
            'publishable_key' => $publishable,
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

    public function verifyStripeWebhook(string $payload, string $signatureHeader): bool
    {
        $secret = (string) env('STRIPE_WEBHOOK_SECRET');
        if (! $secret || ! $signatureHeader) {
            return false;
        }

        $parts = [];
        foreach (explode(',', $signatureHeader) as $segment) {
            [$key, $value] = array_pad(explode('=', trim($segment), 2), 2, null);
            if ($key && $value) {
                $parts[$key] = $value;
            }
        }

        if (empty($parts['t']) || empty($parts['v1'])) {
            return false;
        }

        $signedPayload = $parts['t'] . '.' . $payload;
        $expected = hash_hmac('sha256', $signedPayload, $secret);

        return hash_equals($expected, $parts['v1']);
    }
}
