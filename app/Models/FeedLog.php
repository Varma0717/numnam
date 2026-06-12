<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeedLog extends Model
{
    protected $fillable = [
        'baby_profile_id',
        'type', // milk, solid, water, poop
        'volume_ml',
        'milk_type', // breast, formula
        'food_name',
        'food_type', // veggie, fruit, protein, grain, dairy, mixed
        'texture', // smooth, thick, mashed, lumpy, chopped
        'finish_level', // all, most, half, few, floor, refused
        'poop_type', // Type 1-6, Red/Undigested, Green/Mucous
        'notes',
        'logged_at',
    ];

    protected $casts = [
        'logged_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function babyProfile(): BelongsTo
    {
        return $this->belongsTo(BabyProfile::class);
    }

    public function getCaloriesAttribute()
    {
        if ($this->type === 'milk') {
            $kcalPerMl = $this->milk_type === 'breast' ? 0.70 : 0.67;
            return round($this->volume_ml * $kcalPerMl);
        }

        if ($this->type === 'solid') {
            $kcalPerMl = [
                'veggie' => 0.40,
                'fruit' => 0.55,
                'protein' => 0.90,
                'grain' => 0.75,
                'dairy' => 0.60,
                'mixed' => 0.65,
            ];

            return round($this->volume_ml * ($kcalPerMl[$this->food_type] ?? 0.65));
        }

        return 0;
    }

    public static function getTodayLogs($babyProfileId)
    {
        return self::where('baby_profile_id', $babyProfileId)
            ->whereDate('logged_at', today())
            ->orderBy('logged_at', 'asc')
            ->get();
    }
}
