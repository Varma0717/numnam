<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'plan_name', 'plan_type', 'duration', 'frequency',
        'price_per_cycle', 'discount_percent', 'status', 'next_billing_date', 'ends_at',
    ];

    protected $casts = [
        'price_per_cycle'   => 'decimal:2',
        'next_billing_date' => 'date',
        'ends_at'           => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
