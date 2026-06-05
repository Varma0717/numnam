<?php

namespace Database\Seeders;

use App\Models\ShippingSettings;
use App\Models\TaxSettings;
use Illuminate\Database\Seeder;

class ShippingTaxSettingsSeeder extends Seeder
{
    public function run(): void
    {
        // Create shipping settings with defaults
        ShippingSettings::firstOrCreate([], [
            'enabled' => false,
            'type' => 'flat_rate',
            'flat_rate_amount' => 50,
            'free_shipping_threshold' => null,
            'notes' => 'Flat rate shipping disabled by default',
        ]);

        // Create tax settings with defaults
        TaxSettings::firstOrCreate([], [
            'enabled' => false,
            'type' => 'percentage',
            'rate' => 0,
            'apply_to_shipping' => false,
            'notes' => 'Tax settings disabled by default',
        ]);
    }
}
