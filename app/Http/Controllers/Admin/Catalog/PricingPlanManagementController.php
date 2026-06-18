<?php

namespace App\Http\Controllers\Admin\Catalog;

use App\Http\Controllers\Controller;
use App\Models\PricingPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PricingPlanManagementController extends Controller
{
    public function index(Request $request)
    {
        $plans = PricingPlan::query()
            ->withCount('products')
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = $request->string('q')->toString();
                $query->where(function ($nested) use ($search) {
                    $nested->where('name', 'like', '%' . $search . '%')
                        ->orWhere('slug', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $status = $request->string('status')->toString();
                if ($status === 'active') {
                    $query->where('is_active', true);
                }

                if ($status === 'inactive') {
                    $query->where('is_active', false);
                }
            })
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.pricing-plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.pricing-plans.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatePayload($request);
        PricingPlan::create($data);

        return redirect()->route('admin.pricing-plans.index')->with('status', 'Subscription plan created successfully.');
    }

    public function edit(PricingPlan $pricingPlan)
    {
        return view('admin.pricing-plans.edit', compact('pricingPlan'));
    }

    public function update(Request $request, PricingPlan $pricingPlan): RedirectResponse
    {
        $data = $this->validatePayload($request, $pricingPlan->id);
        $pricingPlan->update($data);

        return redirect()->route('admin.pricing-plans.index')->with('status', 'Subscription plan updated successfully.');
    }

    public function destroy(PricingPlan $pricingPlan): RedirectResponse
    {
        $pricingPlan->delete();

        return redirect()->route('admin.pricing-plans.index')->with('status', 'Subscription plan deleted successfully.');
    }

    private function validatePayload(Request $request, ?int $planId = null): array
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pricing_plans,slug,' . ($planId ?? 'NULL') . ',id',
            'description' => 'nullable|string|max:5000',
            'price' => 'required|numeric|min:0',
            'duration' => 'nullable|string|max:100',
            'billing_cycle' => 'required|in:weekly,monthly,quarterly,yearly,one_time',
            'features' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['duration'] = trim((string) ($validated['duration'] ?? '')) !== ''
            ? trim((string) $validated['duration'])
            : '1 month';
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        $validated['features'] = collect(preg_split('/\r\n|\r|\n/', (string) ($validated['features'] ?? '')))
            ->map(fn($line) => trim($line))
            ->filter()
            ->values()
            ->all();

        return $validated;
    }
}
