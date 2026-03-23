<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\RewardLedger;
use App\Services\Commerce\DiscountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    private $discountService;

    public function __construct(DiscountService $discountService)
    {
        $this->discountService = $discountService;
    }

    /** GET /api/v1/orders  (current user's orders) */
    public function index(Request $request): JsonResponse
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->with('items.product')
            ->orderByDesc('created_at')
            ->paginate(10);

        return response()->json($orders);
    }

    /** GET /api/v1/orders/{orderNumber} */
    public function show(Request $request, string $orderNumber): JsonResponse
    {
        $order = Order::with('items.product')
            ->where('order_number', $orderNumber)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        return response()->json(['data' => $order]);
    }

    /** POST /api/v1/orders  — place a new order */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'payment_method'     => 'required|in:upi,card,cod,netbanking',
            'ship_name'          => 'required|string|max:255',
            'ship_phone'         => 'required|string|max:15',
            'ship_address'       => 'required|string',
            'ship_city'          => 'required|string|max:100',
            'ship_state'         => 'required|string|max:100',
            'ship_pincode'       => 'required|string|max:10',
            'coupon_code'        => 'nullable|string|max:50',
        ]);

        return DB::transaction(function () use ($request) {
            $user = $request->user();
            $isFirstOrder = $user?->orders()->doesntExist() ?? false;
            $subtotal = 0;
            $lineItems = [];

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    abort(422, "Insufficient stock for: {$product->name}");
                }

                $unitPrice = $product->effective_price;
                $lineTotal = $unitPrice * $item['quantity'];
                $subtotal += $lineTotal;

                $lineItems[] = [
                    'product_id'   => $product->id,
                    'product_name' => $product->name,
                    'unit_price'   => $unitPrice,
                    'quantity'     => $item['quantity'],
                    'line_total'   => $lineTotal,
                ];

                // Decrement stock
                $product->decrement('stock', $item['quantity']);
            }

            $discounts = $this->discountService->resolve($user, (float) $subtotal, $request->coupon_code);
            $shippingFee = $subtotal >= 499 ? 0 : 49;
            $payableTotal = max(0, ($subtotal - $discounts['total_discount']) + $shippingFee);

            $order = Order::create([
                'user_id'        => $user?->id,
                'subtotal'       => $subtotal,
                'discount'       => $discounts['total_discount'],
                'shipping_fee'   => $shippingFee,
                'total'          => $payableTotal,
                'payment_method' => $request->payment_method,
                'coupon_code'    => $discounts['coupon']?->code,
                'ship_name'      => $request->ship_name,
                'ship_phone'     => $request->ship_phone,
                'ship_address'   => $request->ship_address,
                'ship_city'      => $request->ship_city,
                'ship_state'     => $request->ship_state,
                'ship_pincode'   => $request->ship_pincode,
            ]);

            $order->items()->createMany($lineItems);

            if ($discounts['coupon']) {
                $discounts['coupon']->increment('used_count');
            }

            if ($isFirstOrder && $user && $user->referred_by) {
                RewardLedger::create([
                    'user_id' => $user->referred_by,
                    'order_id' => $order->id,
                    'type' => 'credit',
                    'amount' => 100,
                    'description' => 'Referral reward for first successful order',
                    'meta' => [
                        'referred_user_id' => $user->id,
                        'order_number' => $order->order_number,
                    ],
                ]);
            }

            return response()->json([
                'data'    => $order->load('items'),
                'message' => 'Order placed successfully.',
            ], 201);
        });
    }

    /** PATCH /api/v1/orders/{id}/cancel */
    public function cancel(Request $request, Order $order): JsonResponse
    {
        if ($order->user_id !== $request->user()->id) {
            abort(403);
        }

        if (! in_array($order->status, ['pending', 'processing'])) {
            return response()->json(['message' => 'Order cannot be cancelled at this stage.'], 422);
        }

        $order->update(['status' => 'cancelled']);

        // Restore stock
        foreach ($order->items as $item) {
            $item->product->increment('stock', $item->quantity);
        }

        return response()->json(['message' => 'Order cancelled.']);
    }
}
