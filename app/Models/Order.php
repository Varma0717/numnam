<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PaymentEvent;

/**
 * @property int $id
 * @property int $user_id
 * @property string $order_number
 * @property string $status
 * @property string $subtotal
 * @property string $discount
 * @property string $shipping_fee
 * @property int|null $shipping_method_id
 * @property string $tax_amount
 * @property string $total
 * @property string|null $coupon_code
 * @property string|null $payment_method
 * @property string|null $payment_status
 * @property string|null $payment_gateway
 * @property string|null $payment_reference
 * @property array|null $payment_meta
 * @property string|null $ship_name
 * @property string|null $ship_phone
 * @property string|null $ship_address
 * @property string|null $ship_city
 * @property string|null $ship_state
 * @property string|null $ship_pincode
 * @property string|null $tracking_number
 * @property string|null $notes
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
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
