<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GuestCheckout;
use App\Services\Commerce\PaymentGatewayService;
use Illuminate\Support\Facades\Log;

class ToolsController extends Controller
{
    protected PaymentGatewayService $paymentService;

    public function __construct(PaymentGatewayService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Show tools hub/index
     */
    public function index()
    {
        $tools = [
            [
                'id' => 'numnam-tracker',
                'name' => 'NumNam Weaning Tracker',
                'description' => 'Track your baby\'s feeding journey with personalized insights, recipes, and developmental guidance.',
                'icon' => '🍼',
                'route' => 'store.tools.numnam',
                'category' => 'Nutrition'
            ]
        ];

        return view('store.tools.index', compact('tools'));
    }

    /**
     * Show NumNam tracker tool
     */
    public function numnam()
    {
        return view('store.tools.numnam');
    }

    /**
     * Create guest checkout order with Razorpay
     */
    public function createGuestCheckout(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'address' => 'nullable|string|max:500',
            ]);

            // Create guest checkout record (pending status)
            $guestCheckout = GuestCheckout::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address ?? '',
                'payment_status' => 'pending',
                'source' => 'tools_tracker',
            ]);

            // Create Razorpay order
            $razorpayResponse = $this->createRazorpayOrder($guestCheckout);

            if (!$razorpayResponse['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $razorpayResponse['message']
                ], 400);
            }

            // Update guest checkout with Razorpay order ID
            $guestCheckout->update([
                'razorpay_order_id' => $razorpayResponse['razorpay_order_id'],
                'payment_meta' => $razorpayResponse,
            ]);

            return response()->json([
                'success' => true,
                'guest_checkout_id' => $guestCheckout->id,
                'razorpay_order_id' => $razorpayResponse['razorpay_order_id'],
                'amount' => $razorpayResponse['amount'],
                'currency' => $razorpayResponse['currency'],
                'key_id' => config('services.razorpay.key_id'),
            ]);
        } catch (\Exception $e) {
            Log::error('Guest checkout creation failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create guest checkout. Please try again.'
            ], 500);
        }
    }

    /**
     * Verify guest payment and complete checkout
     */
    public function verifyGuestPayment(Request $request)
    {
        try {
            $request->validate([
                'guest_checkout_id' => 'required|integer|exists:guest_checkouts,id',
                'razorpay_payment_id' => 'required|string',
                'razorpay_order_id' => 'required|string',
                'razorpay_signature' => 'required|string',
            ]);

            $guestCheckout = GuestCheckout::findOrFail($request->guest_checkout_id);

            // Verify signature
            $isValid = $this->verifyRazorpaySignature(
                $request->razorpay_order_id,
                $request->razorpay_payment_id,
                $request->razorpay_signature
            );

            if (!$isValid) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment verification failed. Please try again.'
                ], 400);
            }

            // Update guest checkout with payment confirmation
            $guestCheckout->update([
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'payment_status' => 'completed',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment completed successfully!',
                'guest_checkout_id' => $guestCheckout->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Guest payment verification failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed. Please contact support.'
            ], 500);
        }
    }

    /**
     * Create Razorpay order for guest checkout
     */
    private function createRazorpayOrder(GuestCheckout $checkout): array
    {
        $keyId = (string) config('services.razorpay.key_id', '');
        $keySecret = (string) config('services.razorpay.key_secret', '');

        if (!$keyId || !$keySecret) {
            return [
                'success' => false,
                'message' => 'Razorpay credentials are not configured.',
            ];
        }

        // Amount in paisa (100 = 1 INR) - Using 1 INR for data collection
        $amount = 100;

        try {
            $response = \Illuminate\Support\Facades\Http::withBasicAuth($keyId, $keySecret)
                ->post('https://api.razorpay.com/v1/orders', [
                    'amount' => $amount,
                    'currency' => 'INR',
                    'receipt' => 'guest_' . $checkout->id,
                    'notes' => [
                        'customer_name' => $checkout->name,
                        'customer_email' => $checkout->email,
                        'customer_phone' => $checkout->phone,
                        'source' => 'tools_tracker',
                    ]
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'razorpay_order_id' => $data['id'],
                    'amount' => $amount,
                    'currency' => 'INR',
                ];
            }

            Log::error('Razorpay order create failed', [
                'status' => $response->status(),
                'response' => $response->json(),
            ]);

            return [
                'success' => false,
                'message' => 'Unable to create Razorpay order.',
            ];
        } catch (\Exception $e) {
            Log::error('Razorpay API connection error', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Unable to connect to Razorpay. Please try again shortly.',
            ];
        }
    }

    /**
     * Verify Razorpay signature
     */
    private function verifyRazorpaySignature(string $orderId, string $paymentId, string $signature): bool
    {
        $keySecret = (string) config('services.razorpay.key_secret', '');

        if (!$keySecret) {
            return false;
        }

        $data = $orderId . '|' . $paymentId;
        $expectedSignature = hash_hmac('sha256', $data, $keySecret);

        return hash_equals($expectedSignature, $signature);
    }
}
