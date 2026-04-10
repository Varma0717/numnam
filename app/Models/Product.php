<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'product_category_id',
        'name',
        'slug',
        'sku',
        'description',
        'short_description',
        'content',
        'ingredients',
        'age_group',
        'type',
        'price',
        'sale_price',
        'stock',
        'image',
        'gallery',
        'is_active',
        'is_featured',
        'status',
        'published_at',
        'meta_title',
        'meta_description',
        'badges',
        'nutrition_facts',
        'nutrition_info',
    ];

    protected $casts = [
        'price'           => 'decimal:2',
        'sale_price'      => 'decimal:2',
        'gallery'         => 'array',
        'badges'          => 'array',
        'nutrition_facts' => 'array',
        'nutrition_info'  => 'array',
        'published_at'    => 'datetime',
        'is_active'       => 'boolean',
        'is_featured'     => 'boolean',
    ];

    protected $appends = ['image_url', 'gallery_urls'];

    public function getImageUrlAttribute(): ?string
    {
        if (empty($this->image)) {
            return null;
        }

        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        return Storage::disk('public')->url($this->image);
    }

    public function getGalleryUrlsAttribute(): array
    {
        $gallery = $this->gallery ?? [];

        return array_map(function ($path) {
            if (empty($path)) {
                return null;
            }
            if (str_starts_with($path, 'http')) {
                return $path;
            }
            return Storage::disk('public')->url($path);
        }, $gallery);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function pricingPlans()
    {
        return $this->belongsToMany(PricingPlan::class, 'pricing_plan_products')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    /** Effective selling price (sale_price if set, otherwise price) */
    public function getEffectivePriceAttribute(): float
    {
        return (float) ($this->sale_price ?? $this->price ?? 0);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(ProductReview::class)->where('moderation_status', 'approved');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
