<?php

namespace App\Http\Controllers\Api\V1\Mobile;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileCartController extends BaseMobileController
{
    public function index(Request $request): JsonResponse
    {
        return $this->success($this->buildCartPayload($request), 'Cart fetched successfully.');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'nullable|integer|min:1|max:99',
        ]);

        $product = Product::query()->where('id', $validated['product_id'])->where('is_active', true)->first();
        if (! $product) {
            return $this->error('Product is not available for purchase.', 422);
        }

        $qty = max(1, (int) ($validated['qty'] ?? 1));

        $item = CartItem::query()->firstOrNew([
            'user_id' => $request->user()->id,
            'product_id' => $product->id,
        ]);
        $item->qty = max(0, (int) $item->qty) + $qty;
        $item->save();

        return $this->success($this->buildCartPayload($request), 'Item added to cart.', 201);
    }

    public function update(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            'qty' => 'required|integer|min:1|max:99',
        ]);

        if (! $product->is_active) {
            return $this->error('Product is not available for purchase.', 422);
        }

        CartItem::query()
            ->updateOrCreate(
                ['user_id' => $request->user()->id, 'product_id' => $product->id],
                ['qty' => (int) $validated['qty']]
            );

        return $this->success($this->buildCartPayload($request), 'Cart updated successfully.');
    }

    public function destroy(Request $request, Product $product): JsonResponse
    {
        CartItem::query()
            ->where('user_id', $request->user()->id)
            ->where('product_id', $product->id)
            ->delete();

        return $this->success($this->buildCartPayload($request), 'Item removed from cart.');
    }

    public function clear(Request $request): JsonResponse
    {
        CartItem::query()->where('user_id', $request->user()->id)->delete();

        return $this->success($this->buildCartPayload($request), 'Cart cleared successfully.');
    }

    private function buildCartPayload(Request $request): array
    {
        $rows = CartItem::query()
            ->where('user_id', $request->user()->id)
            ->with('product')
            ->orderBy('id')
            ->get();

        $items = [];
        $subtotal = 0.0;

        foreach ($rows as $row) {
            $product = $row->product;
            if (! $product || ! $product->is_active) {
                continue;
            }

            $qty = max(1, (int) $row->qty);
            $unitPrice = (float) ($product->sale_price ?? $product->price);
            $lineTotal = $unitPrice * $qty;

            $items[] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'image' => $product->image,
                'qty' => $qty,
                'unit_price' => $unitPrice,
                'line_total' => $lineTotal,
            ];

            $subtotal += $lineTotal;
        }

        $shippingFee = $subtotal > 0 && $subtotal < 999 ? 99.0 : 0.0;

        return [
            'items' => $items,
            'totals' => [
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'total' => $subtotal + $shippingFee,
            ],
        ];
    }
}
