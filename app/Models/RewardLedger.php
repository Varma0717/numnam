<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardLedger extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'type',
        'amount',
        'description',
        'meta',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'meta' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
