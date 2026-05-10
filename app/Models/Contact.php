<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name', 'email', 'company', 'phone', 'query_type', 'message', 'is_read',
    ];

    protected $casts = ['is_read' => 'boolean'];
}
