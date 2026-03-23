<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\PricingPlan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PricingPlanController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $plans = PricingPlan::query()
            ->when($request->filled('active'), fn ($q) => $q->where('is_active', $request->boolean('active')))
            ->with('products:id,name,slug')
            ->orderBy('sort_order')
            ->paginate((int) $request->input('per_page', 20));

        return response()->json(['success' => true, 'data' => $plans]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pricing_plans,slug',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|string|max:100',
            'billing_cycle' => 'required|in:monthly,quarterly,yearly,one_time',
            'features' => 'nullable|array',
            'included_products' => 'nullable|array',
            'included_products.*.product_id' => 'required|exists:products,id',
            'included_products.*.quantity' => 'nullable|integer|min:1',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $includedProducts = $validated['included_products'] ?? [];
        unset($validated['included_products']);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);

        $plan = DB::transaction(function () use ($validated, $includedProducts) {
            $plan = PricingPlan::create($validated);

            if ($includedProducts) {
                $syncData = collect($includedProducts)
                    ->mapWithKeys(fn ($item) => [
                        (int) $item['product_id'] => ['quantity' => (int) ($item['quantity'] ?? 1)],
                    ])
                    ->all();

                $plan->products()->sync($syncData);
            }

            return $plan;
        });

        return response()->json([
            'success' => true,
            'message' => 'Pricing plan created successfully.',
            'data' => $plan->fresh()->load('products:id,name,slug'),
        ], 201);
    }

    public function show(PricingPlan $pricingPlan): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $pricingPlan->load('products:id,name,slug')]);
    }

    public function update(Request $request, PricingPlan $pricingPlan): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pricing_plans,slug,' . $pricingPlan->id,
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'duration' => 'sometimes|required|string|max:100',
            'billing_cycle' => 'sometimes|required|in:monthly,quarterly,yearly,one_time',
            'features' => 'nullable|array',
            'included_products' => 'nullable|array',
            'included_products.*.product_id' => 'required|exists:products,id',
            'included_products.*.quantity' => 'nullable|integer|min:1',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $includedProducts = $validated['included_products'] ?? null;
        unset($validated['included_products']);

        if (isset($validated['name']) && empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        DB::transaction(function () use ($pricingPlan, $validated, $includedProducts) {
            $pricingPlan->update($validated);

            if (is_array($includedProducts)) {
                $syncData = collect($includedProducts)
                    ->mapWithKeys(fn ($item) => [
                        (int) $item['product_id'] => ['quantity' => (int) ($item['quantity'] ?? 1)],
                    ])
                    ->all();

                $pricingPlan->products()->sync($syncData);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Pricing plan updated successfully.',
            'data' => $pricingPlan->fresh()->load('products:id,name,slug'),
        ]);
    }

    public function destroy(PricingPlan $pricingPlan): JsonResponse
    {
        $pricingPlan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pricing plan deleted successfully.',
        ]);
    }
}
