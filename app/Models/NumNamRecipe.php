<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class NumNamRecipe extends Model
{
    protected $fillable = [
        'emoji',
        'name',
        'description',
        'min_age_months',
        'texture',
        'food_type', // veggie, fruit, protein, grain, dairy, mixed
        'preparation',
        'ingredients',
        'notes',
        'hearts_count',
        'is_featured',
    ];

    protected $casts = [
        'ingredients' => 'array',
        'is_featured' => 'boolean',
    ];

    public function likedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'recipe_likes', 'recipe_id', 'user_id');
    }

    public function toggleLike($userId)
    {
        if ($this->likedByUsers()->where('user_id', $userId)->exists()) {
            $this->likedByUsers()->detach($userId);
            $this->decrement('hearts_count');
        } else {
            $this->likedByUsers()->attach($userId);
            $this->increment('hearts_count');
        }
    }

    public static function getForAge($ageMonths)
    {
        return self::where('min_age_months', '<=', $ageMonths)
            ->orderBy('hearts_count', 'desc')
            ->get();
    }
}
