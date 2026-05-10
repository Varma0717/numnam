<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'gateway',
        'event_type',
        'external_reference',
        'fingerprint',
        'status',
        'amount',
        'currency',
        'signature_valid',
        'note',
        'payload',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'signature_valid' => 'boolean',
        'payload' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
