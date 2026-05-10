<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_name',
        'plan_type',
        'duration',
        'frequency',
        'price_per_cycle',
        'discount_percent',
        'status',
        'next_billing_date',
        'ends_at',
        'billing_retry_count',
        'last_billing_attempt_at',
        'last_billing_error',
    ];

    protected $casts = [
        'price_per_cycle'   => 'decimal:2',
        'next_billing_date' => 'date',
        'ends_at'           => 'date',
        'billing_retry_count' => 'integer',
        'last_billing_attempt_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
