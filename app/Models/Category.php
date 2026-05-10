<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $image
 * @property bool $is_active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'image', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
