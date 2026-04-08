<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingZone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function create()
    {
        return view('admin.shipping.zones.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'is_active'         => 'nullable',
            'regions'           => 'nullable|array',
            'regions.*.type'    => 'required_with:regions|in:pincode,state,country',
            'regions.*.value'   => 'required_with:regions|string|max:255',
            'methods'           => 'nullable|array',
            'methods.*.name'    => 'required_with:methods|string|max:255',
            'methods.*.type'    => 'required_with:methods|in:flat_rate,free_shipping,weight_based',
            'methods.*.cost'    => 'nullable|numeric|min:0',
            'methods.*.free_above'  => 'nullable|numeric|min:0',
            'methods.*.min_weight'  => 'nullable|numeric|min:0',
            'methods.*.max_weight'  => 'nullable|numeric|min:0',
            'methods.*.cost_per_kg' => 'nullable|numeric|min:0',
            'methods.*.is_active'   => 'nullable',
        ]);

        $zone = ShippingZone::create([
            'name'       => $request->input('name'),
            'is_active'  => $request->boolean('is_active'),
            'sort_order' => ShippingZone::max('sort_order') + 1,
        ]);

        foreach ($request->input('regions', []) as $region) {
            if (!empty($region['value'])) {
                $zone->regions()->create($region);
            }
        }

        foreach ($request->input('methods', []) as $i => $method) {
            if (!empty($method['name'])) {
                $method['is_active']   = !empty($method['is_active']);
                $method['sort_order']  = $i;
                $zone->methods()->create($method);
            }
        }

        return redirect()
            ->route('admin.settings.index', ['tab' => 'shipping'])
            ->with('status', 'Shipping zone created.');
    }

    public function edit(ShippingZone $zone)
    {
        $zone->load(['regions', 'methods']);

        return view('admin.shipping.zones.edit', compact('zone'));
    }

    public function update(Request $request, ShippingZone $zone): RedirectResponse
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'is_active'         => 'nullable',
            'regions'           => 'nullable|array',
            'regions.*.type'    => 'required_with:regions|in:pincode,state,country',
            'regions.*.value'   => 'required_with:regions|string|max:255',
            'methods'           => 'nullable|array',
            'methods.*.name'    => 'required_with:methods|string|max:255',
            'methods.*.type'    => 'required_with:methods|in:flat_rate,free_shipping,weight_based',
            'methods.*.cost'    => 'nullable|numeric|min:0',
            'methods.*.free_above'  => 'nullable|numeric|min:0',
            'methods.*.min_weight'  => 'nullable|numeric|min:0',
            'methods.*.max_weight'  => 'nullable|numeric|min:0',
            'methods.*.cost_per_kg' => 'nullable|numeric|min:0',
            'methods.*.is_active'   => 'nullable',
        ]);

        $zone->update([
            'name'      => $request->input('name'),
            'is_active' => $request->boolean('is_active'),
        ]);

        // Sync regions — delete all & recreate
        $zone->regions()->delete();
        foreach ($request->input('regions', []) as $region) {
            if (!empty($region['value'])) {
                $zone->regions()->create($region);
            }
        }

        // Sync methods
        $zone->methods()->delete();
        foreach ($request->input('methods', []) as $i => $method) {
            if (!empty($method['name'])) {
                $method['is_active']  = !empty($method['is_active']);
                $method['sort_order'] = $i;
                $zone->methods()->create($method);
            }
        }

        return redirect()
            ->route('admin.settings.index', ['tab' => 'shipping'])
            ->with('status', 'Shipping zone updated.');
    }

    public function destroy(ShippingZone $zone): RedirectResponse
    {
        $zone->regions()->delete();
        $zone->methods()->delete();
        $zone->delete();

        return redirect()
            ->route('admin.settings.index', ['tab' => 'shipping'])
            ->with('status', 'Shipping zone deleted.');
    }
}
