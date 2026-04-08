<?php

use App\Models\SiteSetting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $settings = [
            // ── General ──────────────────────────────────
            ['key' => 'store_name',             'value' => 'NumNam',       'type' => 'text',     'group' => 'general', 'is_public' => true],
            ['key' => 'store_email',            'value' => '',             'type' => 'text',     'group' => 'general', 'is_public' => true],
            ['key' => 'store_phone',            'value' => '',             'type' => 'text',     'group' => 'general', 'is_public' => true],
            ['key' => 'store_address',          'value' => '',             'type' => 'textarea', 'group' => 'general', 'is_public' => true],
            ['key' => 'store_city',             'value' => '',             'type' => 'text',     'group' => 'general', 'is_public' => true],
            ['key' => 'store_state',            'value' => '',             'type' => 'text',     'group' => 'general', 'is_public' => true],
            ['key' => 'store_pincode',          'value' => '',             'type' => 'text',     'group' => 'general', 'is_public' => true],
            ['key' => 'store_logo',             'value' => '',             'type' => 'text',     'group' => 'general', 'is_public' => true],
            ['key' => 'store_currency',         'value' => 'INR',          'type' => 'text',     'group' => 'general', 'is_public' => true],
            ['key' => 'store_currency_symbol',  'value' => '₹',           'type' => 'text',     'group' => 'general', 'is_public' => true],

            // ── Payment ──────────────────────────────────
            ['key' => 'payment_razorpay_enabled',      'value' => '1',    'type' => 'boolean',  'group' => 'payment', 'is_public' => false],
            ['key' => 'payment_cod_enabled',            'value' => '0',    'type' => 'boolean',  'group' => 'payment', 'is_public' => false],
            ['key' => 'payment_cod_min_order',          'value' => '0',    'type' => 'number',   'group' => 'payment', 'is_public' => false],
            ['key' => 'payment_cod_max_order',          'value' => '',     'type' => 'number',   'group' => 'payment', 'is_public' => false],
            ['key' => 'payment_cod_allowed_pincodes',   'value' => '',     'type' => 'textarea', 'group' => 'payment', 'is_public' => false],

            // ── Tax ──────────────────────────────────────
            ['key' => 'tax_gst_enabled',        'value' => '0',           'type' => 'boolean',  'group' => 'tax',     'is_public' => false],
            ['key' => 'tax_gst_rate',           'value' => '18',          'type' => 'number',   'group' => 'tax',     'is_public' => false],
            ['key' => 'tax_inclusive',           'value' => '1',           'type' => 'boolean',  'group' => 'tax',     'is_public' => false],

            // ── Email ────────────────────────────────────
            ['key' => 'email_from_name',                    'value' => 'NumNam',   'type' => 'text',    'group' => 'email', 'is_public' => false],
            ['key' => 'email_from_address',                 'value' => '',         'type' => 'text',    'group' => 'email', 'is_public' => false],
            ['key' => 'email_order_confirmation_enabled',   'value' => '1',        'type' => 'boolean', 'group' => 'email', 'is_public' => false],
            ['key' => 'email_order_shipped_enabled',        'value' => '1',        'type' => 'boolean', 'group' => 'email', 'is_public' => false],
            ['key' => 'email_order_delivered_enabled',      'value' => '1',        'type' => 'boolean', 'group' => 'email', 'is_public' => false],
            ['key' => 'email_admin_new_order_enabled',      'value' => '1',        'type' => 'boolean', 'group' => 'email', 'is_public' => false],
        ];

        foreach ($settings as $setting) {
            SiteSetting::firstOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    public function down(): void
    {
        SiteSetting::whereIn('group', ['general', 'payment', 'tax', 'email'])->delete();
    }
};
