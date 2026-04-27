<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 * @property int $qty
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class CartItem extends Model
{
    protected $fillable = ['user_id', 'product_id', 'qty'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
