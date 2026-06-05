<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingSettings extends Model
{
    protected $table = 'shipping_settings';
    protected $fillable = [
        'enabled',
        'type',
        'flat_rate_amount',
        'free_shipping_threshold',
        'notes',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'flat_rate_amount' => 'decimal:2',
        'free_shipping_threshold' => 'decimal:2',
    ];

    /**
     * Get or create the settings singleton
     */
    public static function getInstance()
    {
        return self::firstOrCreate([], [
            'enabled' => false,
            'type' => 'flat_rate',
            'flat_rate_amount' => 0,
            'free_shipping_threshold' => null,
        ]);
    }

    /**
     * Calculate shipping cost based on subtotal
     */
    public function calculateShipping($subtotal = 0)
    {
        if (!$this->enabled) {
            return 0;
        }

        if ($this->type === 'flat_rate') {
            if ($this->free_shipping_threshold && $subtotal >= $this->free_shipping_threshold) {
                return 0;
            }
            return $this->flat_rate_amount ?? 0;
        }

        return 0;
    }
}
