<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $template
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string $status
 * @property \Carbon\Carbon|null $published_at
 * @property int $sort_order
 * @property bool $is_homepage
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 */
class Page extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'template',
        'meta_title',
        'meta_description',
        'status',
        'published_at',
        'sort_order',
        'is_homepage',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_homepage' => 'boolean',
    ];

    public function sections(): HasMany
    {
        return $this->hasMany(PageSection::class)->orderBy('position');
    }

    public function menuItems(): HasMany
    {
        return $this->hasMany(MenuItem::class);
    }
}
