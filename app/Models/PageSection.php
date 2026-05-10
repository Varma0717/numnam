<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $page_id
 * @property string|null $section_key
 * @property string|null $section_type
 * @property string|null $title
 * @property string|null $content
 * @property array|null $settings
 * @property array|null $data
 * @property int $position
 * @property bool $is_active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class PageSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'section_key',
        'section_type',
        'title',
        'content',
        'settings',
        'data',
        'position',
        'is_active',
    ];

    protected $casts = [
        'settings' => 'array',
        'data' => 'array',
        'is_active' => 'boolean',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
