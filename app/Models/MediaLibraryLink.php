<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MediaLibraryLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'media_library_id',
        'entity_type',
        'entity_id',
        'role',
    ];

    public function media(): BelongsTo
    {
        return $this->belongsTo(MediaLibrary::class, 'media_library_id');
    }
}
