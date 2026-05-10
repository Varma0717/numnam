<?php

namespace Tests\Feature;

use App\Models\Coupon;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CheckoutCouponPreviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_preview_valid_coupon_discount(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        Product::query()->create([
            'name' => 'Preview Product',
            'slug' => 'preview-product',
            'price' => 500,
            'sale_price' => 450,
            'stock' => 10,
            'is_active' => true,
        ]);

        Coupon::query()->create([
            'code' => 'SAVE10',
            'type' => 'percent',
            'value' => 10,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)
            ->withSession([
                'cart' => [
                    '1' => ['product_id' => 1, 'qty' => 2],
                ],
            ])
            ->postJson(route('store.checkout.coupon-preview'), [
                'coupon_code' => 'save10',
            ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('discounts.coupon_code', 'SAVE10')
            ->assertJsonPath('summary.subtotal', 900.0)
            ->assertJsonPath('summary.coupon_discount', 90.0)
            ->assertJsonPath('summary.total', 909.0);
    }

    public function test_coupon_preview_rejects_invalid_coupon(): void
    {
        $user = User::factory()->create();

        Product::query()->create([
            'name' => 'Preview Product',
            'slug' => 'preview-product',
            'price' => 300,
            'stock' => 10,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)
            ->withSession([
                'cart' => [
                    '1' => ['product_id' => 1, 'qty' => 1],
                ],
            ])
            ->postJson(route('store.checkout.coupon-preview'), [
                'coupon_code' => 'NOPE',
            ]);

        $response->assertStatus(422)
            ->assertJsonPath('success', false)
            ->assertJsonPath('summary.total', 399.0);
    }
}
