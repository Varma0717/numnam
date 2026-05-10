<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PricingPlan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PricingPlanModuleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $plans = PricingPlan::query()
            ->with(['products:id,name,slug,image'])
            ->when($request->filled('billing_cycle'), fn ($q) => $q->where('billing_cycle', $request->string('billing_cycle')))
            ->when($request->filled('active'), fn ($q) => $q->where('is_active', $request->boolean('active')))
            ->orderBy('sort_order')
            ->paginate((int) $request->input('per_page', 12));

        return response()->json([
            'success' => true,
            'data' => $plans,
        ]);
    }

    public function show(PricingPlan $pricingPlan): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $pricingPlan->load(['products:id,name,slug,image']),
        ]);
    }
}
