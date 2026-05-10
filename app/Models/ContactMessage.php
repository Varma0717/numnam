<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'source',
        'ip_address',
        'user_agent',
        'read_at',
        'replied_at',
        'notified_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'replied_at' => 'datetime',
        'notified_at' => 'datetime',
    ];
}
