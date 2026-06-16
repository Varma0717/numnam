<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class CommunityRoom extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'description',
        'icon',
        'color',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'room_id');
    }

    /**
     * Get online count - cached for 5 minutes
     */
    public function getOnlineCountAttribute()
    {
        $cacheKey = "room_online_count_{$this->id}";

        return Cache::remember($cacheKey, 300, function () {
            return ChatMessage::where('room_id', $this->id)
                ->where('created_at', '>=', now()->subHours(1))
                ->select('user_id')
                ->distinct()
                ->count();
        });
    }

    /**
     * Get last message - cached
     */
    public function getLastMessageAttribute()
    {
        $cacheKey = "room_last_message_{$this->id}";

        return Cache::remember($cacheKey, 600, function () {
            return $this->messages()
                ->with('user:id,name')
                ->latest('created_at')
                ->first();
        });
    }
}
