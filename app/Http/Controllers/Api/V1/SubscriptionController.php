<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PricingPlan;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SubscriptionController extends Controller
{
    /** GET /api/v1/subscriptions/plans — public list of available plans */
    public function plans(): JsonResponse
    {
        if (!Schema::hasTable('pricing_plans')) {
            return response()->json(['data' => []]);
        }

        $plans = PricingPlan::query()
            ->with(['products:id,name,slug,image'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return response()->json(['data' => $plans]);
    }

    /** GET /api/v1/subscriptions  (current user) */
    public function index(Request $request): JsonResponse
    {
        $subs = Subscription::where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json(['data' => $subs]);
    }

    /** POST /api/v1/subscriptions */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'plan_name'       => 'required|string|max:100',
            'plan_type'       => 'required|in:puree,puffs',
            'duration'        => 'required|in:3M,6M,12M',
            'frequency'       => 'required|in:weekly,monthly',
            'price_per_cycle' => 'required|numeric|min:0',
            'discount_percent'=> 'required|integer|between:0,50',
        ]);

        $validated['user_id'] = $request->user()->id;
        $validated['next_billing_date'] = now()->addWeek();

        $sub = Subscription::create($validated);

        return response()->json(['data' => $sub, 'message' => 'Subscription created.'], 201);
    }

    /** PATCH /api/v1/subscriptions/{id}/pause */
    public function pause(Request $request, Subscription $subscription): JsonResponse
    {
        $this->authorizeOwner($request, $subscription);
        $subscription->update(['status' => 'paused']);
        return response()->json(['message' => 'Subscription paused.']);
    }

    /** PATCH /api/v1/subscriptions/{id}/resume */
    public function resume(Request $request, Subscription $subscription): JsonResponse
    {
        $this->authorizeOwner($request, $subscription);
        $subscription->update(['status' => 'active', 'next_billing_date' => now()->addWeek()]);
        return response()->json(['message' => 'Subscription resumed.']);
    }

    /** DELETE /api/v1/subscriptions/{id} */
    public function destroy(Request $request, Subscription $subscription): JsonResponse
    {
        $this->authorizeOwner($request, $subscription);
        $subscription->update(['status' => 'cancelled']);
        return response()->json(['message' => 'Subscription cancelled.']);
    }

    private function authorizeOwner(Request $request, Subscription $subscription): void
    {
        if ($subscription->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized.');
        }
    }
}
