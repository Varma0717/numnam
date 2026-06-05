<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $email
 * @property string $phone
 * @property string $name
 * @property string $address
 * @property string|null $razorpay_order_id
 * @property string|null $razorpay_payment_id
 * @property string $payment_status
 * @property array|null $payment_meta
 * @property string|null $source (tools_tracker, etc)
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class GuestCheckout extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'phone',
        'name',
        'address',
        'razorpay_order_id',
        'razorpay_payment_id',
        'payment_status',
        'payment_meta',
        'source',
    ];

    protected $casts = [
        'payment_meta' => 'array',
    ];
}
