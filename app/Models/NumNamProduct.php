<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NumNamProduct extends Model
{
    protected $fillable = [
        'emoji',
        'name',
        'description',
        'category', // purées, snacks, bundle, experience
        'price',
        'badge_type', // new, hot, popular
        'badge_label',
        'stage', // 1, 2, or 0 for experience
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'price' => 'float',
        'is_active' => 'boolean',
    ];

    public static function getByCategory($category)
    {
        return self::where('category', $category)
            ->where('is_active', true)
            ->orderBy('display_order')
            ->get();
    }

    public static function getAll()
    {
        return self::where('is_active', true)
            ->orderBy('display_order')
            ->get();
    }
}
