<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\ShippingSettings;
use App\Models\TaxSettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class SettingsController extends BaseController
{
    /**
     * Get shipping settings
     */
    public function shippingSettings(): JsonResponse
    {
        $settings = ShippingSettings::getInstance();

        return response()->json([
            'enabled' => $settings->enabled,
            'type' => $settings->type,
            'flat_rate_amount' => $settings->flat_rate_amount,
            'free_shipping_threshold' => $settings->free_shipping_threshold,
        ]);
    }

    /**
     * Get tax settings
     */
    public function taxSettings(): JsonResponse
    {
        $settings = TaxSettings::getInstance();

        return response()->json([
            'enabled' => $settings->enabled,
            'type' => $settings->type,
            'rate' => $settings->rate,
            'apply_to_shipping' => $settings->apply_to_shipping,
        ]);
    }

    /**
     * Get both shipping and tax settings (for checkout)
     */
    public function checkoutSettings(): JsonResponse
    {
        $shipping = ShippingSettings::getInstance();
        $tax = TaxSettings::getInstance();

        return response()->json([
            'shipping' => [
                'enabled' => $shipping->enabled,
                'type' => $shipping->type,
                'flat_rate_amount' => $shipping->flat_rate_amount,
                'free_shipping_threshold' => $shipping->free_shipping_threshold,
            ],
            'tax' => [
                'enabled' => $tax->enabled,
                'type' => $tax->type,
                'rate' => $tax->rate,
                'apply_to_shipping' => $tax->apply_to_shipping,
            ],
        ]);
    }

    /**
     * Calculate totals for checkout
     */
    public function calculateTotals(): JsonResponse
    {
        $subtotal = request()->input('subtotal', 0);

        $shipping = ShippingSettings::getInstance();
        $tax = TaxSettings::getInstance();

        $shippingCost = $shipping->calculateShipping($subtotal);
        $taxAmount = $tax->calculateTax($subtotal, $shippingCost);
        $total = $subtotal + $shippingCost + $taxAmount;

        return response()->json([
            'subtotal' => $subtotal,
            'shipping' => $shippingCost,
            'tax' => $taxAmount,
            'total' => $total,
        ]);
    }
}
