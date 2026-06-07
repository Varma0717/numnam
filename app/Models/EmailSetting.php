<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'mail_driver',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'from_name',
        'from_address',
        'to_name',
        'to_address',
        'reply_to_name',
        'reply_to_address',
    ];

    protected $casts = [
        'mail_password' => 'encrypted',
    ];
}
