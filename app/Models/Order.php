<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PaymentEvent;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'subtotal',
        'discount',
        'shipping_fee',
        'shipping_method_id',
        'tax_amount',
        'total',
        'coupon_code',
        'payment_method',
        'payment_status',
        'payment_gateway',
        'payment_reference',
        'payment_meta',
        'ship_name',
        'ship_phone',
        'ship_address',
        'ship_city',
        'ship_state',
        'ship_pincode',
        'tracking_number',
        'notes',
    ];

    protected $casts = [
        'subtotal'     => 'decimal:2',
        'discount'     => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'tax_amount'   => 'decimal:2',
        'total'        => 'decimal:2',
        'payment_meta' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function paymentEvents()
    {
        return $this->hasMany(PaymentEvent::class);
    }

    protected static function booted()
    {
        static::creating(function (Order $order) {
            if (! $order->order_number) {
                $order->order_number = 'NN-' . strtoupper(uniqid());
            }
        });
    }
}
