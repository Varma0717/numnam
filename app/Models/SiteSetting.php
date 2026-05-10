<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'is_public',
        'autoload',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'autoload' => 'boolean',
    ];
}
