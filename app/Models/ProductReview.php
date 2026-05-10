<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'title',
        'body',
        'is_approved',
        'moderation_status',
        'moderated_at',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean',
        'moderated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
