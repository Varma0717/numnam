<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxSettings extends Model
{
    protected $table = 'tax_settings';
    protected $fillable = [
        'enabled',
        'type',
        'rate',
        'apply_to_shipping',
        'notes',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'apply_to_shipping' => 'boolean',
        'rate' => 'decimal:2',
    ];

    /**
     * Get or create the settings singleton
     */
    public static function getInstance()
    {
        return self::firstOrCreate([], [
            'enabled' => false,
            'type' => 'percentage',
            'rate' => 0,
            'apply_to_shipping' => false,
        ]);
    }

    /**
     * Calculate tax based on subtotal and optional shipping
     */
    public function calculateTax($subtotal = 0, $shippingCost = 0)
    {
        if (!$this->enabled) {
            return 0;
        }

        $taxableAmount = $subtotal;
        if ($this->apply_to_shipping) {
            $taxableAmount += $shippingCost;
        }

        if ($this->type === 'percentage') {
            return ($taxableAmount * $this->rate) / 100;
        }

        if ($this->type === 'fixed_amount') {
            return $this->rate;
        }

        return 0;
    }
}
