<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MessageComment extends Model
{
    protected $fillable = [
        'message_id',
        'user_id',
        'comment',
        'likes_count',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function message(): BelongsTo
    {
        return $this->belongsTo(ChatMessage::class, 'message_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function incrementLikes()
    {
        return $this->increment('likes_count');
    }

    public function decrementLikes()
    {
        return $this->decrement('likes_count');
    }
}
