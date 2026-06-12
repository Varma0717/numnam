<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BabyProfile extends Model
{
    protected $fillable = [
        'user_id',
        'baby_name',
        'age_months',
        'weight_kg',
        'milk_type',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'age_months' => 'integer',
        'weight_kg' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(FeedLog::class);
    }

    public function getMilkTargetAttribute()
    {
        $targets = [
            6 => ['lo' => 800, 'hi' => 950],
            7 => ['lo' => 700, 'hi' => 850],
            8 => ['lo' => 650, 'hi' => 800],
            9 => ['lo' => 600, 'hi' => 750],
            10 => ['lo' => 500, 'hi' => 650],
            11 => ['lo' => 400, 'hi' => 600],
            12 => ['lo' => 350, 'hi' => 500],
        ];

        $age = $this->age_months;
        foreach (array_keys($targets) as $targetAge) {
            if ($age >= $targetAge) {
                $lastAge = $targetAge;
            }
        }

        return $targets[$lastAge ?? 6] ?? ['lo' => 800, 'hi' => 950];
    }

    public function getSolidTargetAttribute()
    {
        $targets = [
            6 => ['lo' => 5, 'hi' => 60, 'meals' => 1],
            7 => ['lo' => 80, 'hi' => 180, 'meals' => 2],
            8 => ['lo' => 120, 'hi' => 220, 'meals' => 2],
            9 => ['lo' => 150, 'hi' => 280, 'meals' => 3],
            10 => ['lo' => 180, 'hi' => 320, 'meals' => 3],
            11 => ['lo' => 200, 'hi' => 360, 'meals' => 3],
            12 => ['lo' => 220, 'hi' => 400, 'meals' => 3],
        ];

        $age = $this->age_months;
        foreach (array_keys($targets) as $targetAge) {
            if ($age >= $targetAge) {
                $lastAge = $targetAge;
            }
        }

        return $targets[$lastAge ?? 6] ?? ['lo' => 5, 'hi' => 60, 'meals' => 1];
    }

    public function getWaterTargetAttribute()
    {
        $targets = [
            6 => ['lo' => 0, 'hi' => 30],
            7 => ['lo' => 15, 'hi' => 60],
            8 => ['lo' => 20, 'hi' => 80],
            9 => ['lo' => 40, 'hi' => 100],
            10 => ['lo' => 60, 'hi' => 120],
            11 => ['lo' => 80, 'hi' => 150],
            12 => ['lo' => 100, 'hi' => 200],
        ];

        $age = $this->age_months;
        foreach (array_keys($targets) as $targetAge) {
            if ($age >= $targetAge) {
                $lastAge = $targetAge;
            }
        }

        return $targets[$lastAge ?? 6] ?? ['lo' => 0, 'hi' => 30];
    }

    public function getDailyCalorieNeedAttribute()
    {
        $kcalPK = [6 => 82, 7 => 82, 8 => 83, 9 => 83, 10 => 84, 11 => 84, 12 => 83];
        $weight = $this->weight_kg ?? $this->getMedianWeightAttribute();

        return round(($kcalPK[$this->age_months] ?? 82) * $weight);
    }

    public function getMedianWeightAttribute()
    {
        $weights = [6 => 7.3, 7 => 7.8, 8 => 8.3, 9 => 8.8, 10 => 9.2, 11 => 9.6, 12 => 9.9];
        return $weights[$this->age_months] ?? 8.5;
    }
}
