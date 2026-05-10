<?php

namespace App\Services\Commerce;

use App\Models\Coupon;
use App\Models\User;

class DiscountService
{
    public function resolve(?User $user, float $subtotal, ?string $couponCode = null): array
    {
        $couponDiscount = 0.0;
        $referralDiscount = 0.0;
        $coupon = null;

        if ($couponCode) {
            $coupon = $this->validCoupon($couponCode, $subtotal);
            if ($coupon) {
                if ($coupon->type === 'percent') {
                    $couponDiscount = round($subtotal * ((float) $coupon->value / 100), 2);
                } else {
                    $couponDiscount = min((float) $coupon->value, $subtotal);
                }
            }
        }

        // Referral welcome discount for referred users on their first order.
        if ($user && $user->referred_by) {
            $orderCount = $user->orders()->count();
            if ($orderCount === 0) {
                $referralDiscount = min(50.0, $subtotal);
            }
        }

        $totalDiscount = min($subtotal, $couponDiscount + $referralDiscount);

        return [
            'coupon' => $coupon,
            'coupon_discount' => $couponDiscount,
            'referral_discount' => $referralDiscount,
            'total_discount' => $totalDiscount,
        ];
    }

    private function validCoupon(string $couponCode, float $subtotal): ?Coupon
    {
        $coupon = Coupon::query()
            ->where('code', strtoupper(trim($couponCode)))
            ->where('is_active', true)
            ->first();

        if (! $coupon) {
            return null;
        }

        if ($coupon->starts_at && now()->lt($coupon->starts_at)) {
            return null;
        }

        if ($coupon->ends_at && now()->gt($coupon->ends_at)) {
            return null;
        }

        if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
            return null;
        }

        if ($coupon->min_subtotal !== null && $subtotal < (float) $coupon->min_subtotal) {
            return null;
        }

        return $coupon;
    }
}
