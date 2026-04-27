<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property int|null $blog_category_id
 * @property int|null $author_id
 * @property string $title
 * @property string $slug
 * @property string|null $excerpt
 * @property string|null $content
 * @property string|null $featured_image
 * @property string $status
 * @property \Carbon\Carbon|null $published_at
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property int $view_count
 * @property-read string|null $featured_image_url
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 */
class Blog extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'blog_category_id',
        'author_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'status',
        'published_at',
        'meta_title',
        'meta_description',
        'view_count',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected $appends = ['featured_image_url'];

    public function getFeaturedImageUrlAttribute(): ?string
    {
        if (empty($this->featured_image)) {
            return null;
        }

        if (str_starts_with($this->featured_image, 'http')) {
            return $this->featured_image;
        }

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');
        return $disk->url($this->featured_image);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
