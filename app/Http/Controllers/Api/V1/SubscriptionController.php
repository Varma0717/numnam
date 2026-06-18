<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Mail\SubscriptionStatusNotification;
use App\Mail\NewSubscriptionAdminNotification;
use App\Models\PricingPlan;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
            // Legacy payload support
            'plan_name' => 'required_without:pricing_plan_id|string|max:100',
            'plan_type' => 'required_without:pricing_plan_id|in:puree,puffs',
            'duration' => 'required_without:pricing_plan_id|in:3M,6M,12M',
            'frequency' => 'required_without:pricing_plan_id|in:weekly,monthly',
            'price_per_cycle' => 'required_without:pricing_plan_id|numeric|min:0',
            'discount_percent' => 'nullable|integer|between:0,50',

            // Mobile checkout payload support
            'pricing_plan_id' => 'nullable|integer|exists:pricing_plans,id',
            'payment_method' => 'nullable|string|max:50',
            'ship_name' => 'nullable|string|max:255',
            'ship_phone' => 'nullable|string|max:30',
            'ship_address' => 'nullable|string|max:255',
            'ship_city' => 'nullable|string|max:120',
            'ship_state' => 'nullable|string|max:120',
            'ship_pincode' => 'nullable|string|max:20',
            'payment_reference' => 'nullable|string|max:255',
            'razorpay_order_id' => 'nullable|string|max:255',
            'razorpay_signature' => 'nullable|string|max:255',
        ]);

        $subscriptionData = [
            'user_id' => $request->user()->id,
            'discount_percent' => (int) ($validated['discount_percent'] ?? 0),
        ];

        if (! empty($validated['pricing_plan_id'])) {
            $plan = PricingPlan::query()
                ->where('id', (int) $validated['pricing_plan_id'])
                ->where('is_active', true)
                ->firstOrFail();

            $billingCycle = $plan->billing_cycle ?: 'monthly';
            $frequency = in_array($billingCycle, ['weekly', 'monthly'], true) ? $billingCycle : 'monthly';
            $durationValue = (string) ($plan->duration ?? '1');
            $duration = preg_match('/^\d+$/', $durationValue) ? ($durationValue . 'M') : strtoupper($durationValue);

            $subscriptionData['plan_name'] = $plan->name;
            $subscriptionData['plan_type'] = str_contains(strtolower($plan->name), 'puff') ? 'puffs' : 'puree';
            $subscriptionData['duration'] = in_array($duration, ['3M', '6M', '12M'], true) ? $duration : '3M';
            $subscriptionData['frequency'] = $frequency;
            $subscriptionData['price_per_cycle'] = $plan->price;
        } else {
            $subscriptionData['plan_name'] = $validated['plan_name'];
            $subscriptionData['plan_type'] = $validated['plan_type'];
            $subscriptionData['duration'] = $validated['duration'];
            $subscriptionData['frequency'] = $validated['frequency'];
            $subscriptionData['price_per_cycle'] = $validated['price_per_cycle'];
        }

        $subscriptionData['next_billing_date'] =
            $subscriptionData['frequency'] === 'weekly' ? now()->addWeek() : now()->addMonth();

        $sub = Subscription::create($subscriptionData);

        $this->notifySubscriptionStatus($sub, 'created');
        $this->notifyAdminAboutSubscription($sub, 'created');

        return response()->json(['data' => $sub, 'message' => 'Subscription created.'], 201);
    }

    /** PATCH /api/v1/subscriptions/{id}/pause */
    public function pause(Request $request, Subscription $subscription): JsonResponse
    {
        $this->authorizeOwner($request, $subscription);
        $subscription->update(['status' => 'paused']);
        $this->notifySubscriptionStatus($subscription, 'paused');
        $this->notifyAdminAboutSubscription($subscription, 'paused');
        return response()->json(['message' => 'Subscription paused.']);
    }

    /** PATCH /api/v1/subscriptions/{id}/resume */
    public function resume(Request $request, Subscription $subscription): JsonResponse
    {
        $this->authorizeOwner($request, $subscription);
        $subscription->update(['status' => 'active', 'next_billing_date' => now()->addWeek()]);
        $this->notifySubscriptionStatus($subscription, 'resumed');
        $this->notifyAdminAboutSubscription($subscription, 'resumed');
        return response()->json(['message' => 'Subscription resumed.']);
    }

    /** DELETE /api/v1/subscriptions/{id} */
    public function destroy(Request $request, Subscription $subscription): JsonResponse
    {
        $this->authorizeOwner($request, $subscription);
        $subscription->update(['status' => 'cancelled']);
        $this->notifySubscriptionStatus($subscription, 'cancelled');
        $this->notifyAdminAboutSubscription($subscription, 'cancelled');
        return response()->json(['message' => 'Subscription cancelled.']);
    }

    private function notifySubscriptionStatus(Subscription $subscription, string $action): void
    {
        $subscription->loadMissing('user');

        if (! $subscription->user?->email) {
            return;
        }

        try {
            Mail::to($subscription->user->email)->queue(
                new SubscriptionStatusNotification($subscription->fresh(), $action)
            );
        } catch (\Throwable $exception) {
            report($exception);
        }
    }

    private function notifyAdminAboutSubscription(Subscription $subscription, string $action): void
    {
        $adminRecipient = (string) config('mail.order_recipient', '');

        if ($adminRecipient === '') {
            return;
        }

        try {
            Mail::to($adminRecipient)->queue(
                new NewSubscriptionAdminNotification($subscription->fresh()->loadMissing('user'), $action)
            );
        } catch (\Throwable $exception) {
            report($exception);
        }
    }

    private function authorizeOwner(Request $request, Subscription $subscription): void
    {
        if ($subscription->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized.');
        }
    }
}
