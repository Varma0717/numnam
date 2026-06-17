<?php

namespace App\Http\Controllers\Admin\Commerce;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ShippingSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShippingController extends Controller
{
    /**
     * Show shipping settings page
     */
    public function index(): View
    {
        $settings = ShippingSettings::getInstance();
        $excludedProducts = Product::where('exclude_from_shipping', true)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        
        $allProducts = Product::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.shipping.settings', compact('settings', 'excludedProducts', 'allProducts'));
    }

    /**
     * Update shipping settings
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'enabled' => 'nullable|boolean',
            'type' => 'required|in:flat_rate,weight_based,zone_based',
            'flat_rate_amount' => 'required|numeric|min:0|max:99999.99',
            'free_shipping_threshold' => 'nullable|numeric|min:0|max:999999.99',
            'notes' => 'nullable|string|max:1000',
        ]);

        $settings = ShippingSettings::getInstance();
        $settings->update([
            'enabled' => (bool) ($validated['enabled'] ?? false),
            'type' => $validated['type'],
            'flat_rate_amount' => (float) $validated['flat_rate_amount'],
            'free_shipping_threshold' => $validated['free_shipping_threshold'] ? (float) $validated['free_shipping_threshold'] : null,
            'notes' => $validated['notes'] ?? null,
        ]);

        return back()->with('status', '✓ Shipping settings updated successfully!');
    }

    /**
     * Toggle exclude_from_shipping for a product
     */
    public function toggleShipping(Request $request, Product $product): RedirectResponse
    {
        $product->update([
            'exclude_from_shipping' => !$product->exclude_from_shipping,
        ]);

        $action = $product->exclude_from_shipping ? 'excluded from' : 'included in';
        return back()->with('status', "✓ {$product->name} has been {$action} shipping charges.");
    }
}
