<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MediaLibrary extends Model
{
    use HasFactory;

    protected $table = 'media_library';

    protected $fillable = [
        'disk',
        'folder',
        'collection',
        'file_name',
        'file_path',
        'mime_type',
        'size',
        'title',
        'alt_text',
        'caption',
        'uploaded_by',
        'metadata',
        'is_public',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_public' => 'boolean',
    ];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function links(): HasMany
    {
        return $this->hasMany(MediaLibraryLink::class, 'media_library_id');
    }
}
