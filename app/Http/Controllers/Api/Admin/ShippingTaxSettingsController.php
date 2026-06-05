<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\ShippingSettings;
use App\Models\TaxSettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class ShippingTaxSettingsController extends BaseController
{
    /**
     * Get all shipping settings
     */
    public function getShipping(): JsonResponse
    {
        $settings = ShippingSettings::getInstance();

        return response()->json([
            'success' => true,
            'data' => $settings,
        ]);
    }

    /**
     * Update shipping settings
     */
    public function updateShipping(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'enabled' => 'boolean',
            'type' => 'in:flat_rate,weight_based,zone_based',
            'flat_rate_amount' => 'nullable|numeric|min:0',
            'free_shipping_threshold' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        $settings = ShippingSettings::getInstance();
        $settings->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Shipping settings updated successfully',
            'data' => $settings,
        ]);
    }

    /**
     * Get all tax settings
     */
    public function getTax(): JsonResponse
    {
        $settings = TaxSettings::getInstance();

        return response()->json([
            'success' => true,
            'data' => $settings,
        ]);
    }

    /**
     * Update tax settings
     */
    public function updateTax(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'enabled' => 'boolean',
            'type' => 'in:percentage,fixed_amount',
            'rate' => 'required|numeric|min:0|max:100',
            'apply_to_shipping' => 'boolean',
            'notes' => 'nullable|string|max:500',
        ]);

        $settings = TaxSettings::getInstance();
        $settings->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tax settings updated successfully',
            'data' => $settings,
        ]);
    }

    /**
     * Get both shipping and tax settings
     */
    public function getAll(): JsonResponse
    {
        $shipping = ShippingSettings::getInstance();
        $tax = TaxSettings::getInstance();

        return response()->json([
            'success' => true,
            'data' => [
                'shipping' => $shipping,
                'tax' => $tax,
            ],
        ]);
    }
}
