<?php

namespace App\Http\Controllers\Api\V1\Mobile;

use App\Models\Product;
use App\Models\ProductReview;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileEngagementController extends BaseMobileController
{
    public function wishlist(Request $request): JsonResponse
    {
        $items = Wishlist::query()
            ->where('user_id', $request->user()->id)
            ->with('product')
            ->orderByDesc('id')
            ->get()
            ->map(function (Wishlist $wishlist) {
                $product = $wishlist->product;
                if (! $product || ! $product->is_active) {
                    return null;
                }

                return [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'image' => $product->image,
                    'price' => (float) $product->price,
                    'sale_price' => $product->sale_price !== null ? (float) $product->sale_price : null,
                    'in_stock' => (int) $product->stock > 0,
                ];
            })
            ->filter()
            ->values();

        return $this->success($items, 'Wishlist fetched successfully.');
    }

    public function addToWishlist(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $product = Product::query()->where('id', $validated['product_id'])->where('is_active', true)->first();
        if (! $product) {
            return $this->error('Product is not available.', 422);
        }

        Wishlist::query()->firstOrCreate([
            'user_id' => $request->user()->id,
            'product_id' => $product->id,
        ]);

        return $this->success([], 'Added to wishlist.', 201);
    }

    public function removeFromWishlist(Request $request, Product $product): JsonResponse
    {
        Wishlist::query()
            ->where('user_id', $request->user()->id)
            ->where('product_id', $product->id)
            ->delete();

        return $this->success([], 'Removed from wishlist.');
    }

    public function productReviews(Request $request, Product $product): JsonResponse
    {
        $reviews = ProductReview::query()
            ->where('product_id', $product->id)
            ->where(function ($query) use ($request) {
                $query->where('moderation_status', 'approved')
                    ->orWhere('user_id', $request->user()->id);
            })
            ->with('user:id,name')
            ->latest('id')
            ->get()
            ->map(function (ProductReview $review) {
                return [
                    'id' => $review->id,
                    'rating' => (int) $review->rating,
                    'title' => $review->title,
                    'body' => $review->body,
                    'is_approved' => (bool) $review->is_approved,
                    'created_at' => optional($review->created_at)->toIso8601String(),
                    'user' => [
                        'id' => $review->user?->id,
                        'name' => $review->user?->name,
                    ],
                ];
            })
            ->values();

        $summary = [
            'average_rating' => round((float) ProductReview::query()
                ->where('product_id', $product->id)
                ->where('moderation_status', 'approved')
                ->avg('rating'), 2),
            'approved_reviews_count' => ProductReview::query()
                ->where('product_id', $product->id)
                ->where('moderation_status', 'approved')
                ->count(),
        ];

        return $this->success([
            'summary' => $summary,
            'reviews' => $reviews,
        ], 'Product reviews fetched successfully.');
    }

    public function upsertProductReview(Request $request, Product $product): JsonResponse
    {
        if (! $product->is_active) {
            return $this->error('Product is not available for review.', 422);
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:150',
            'body' => 'required|string|min:10|max:2000',
        ]);

        ProductReview::query()->updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'product_id' => $product->id,
            ],
            [
                'rating' => $validated['rating'],
                'title' => $validated['title'] ?? null,
                'body' => $validated['body'],
                'is_approved' => false,
                'moderation_status' => 'pending',
                'moderated_at' => null,
            ]
        );

        return $this->success([], 'Review submitted successfully. It will be visible after moderation.');
    }

    public function updateReview(Request $request, ProductReview $review): JsonResponse
    {
        if ($review->user_id !== $request->user()->id) {
            return $this->error('You are not allowed to update this review.', 403);
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:150',
            'body' => 'required|string|min:10|max:2000',
        ]);

        $review->update([
            'rating' => $validated['rating'],
            'title' => $validated['title'] ?? null,
            'body' => $validated['body'],
            'is_approved' => false,
            'moderation_status' => 'pending',
            'moderated_at' => null,
        ]);

        return $this->success([], 'Review updated successfully. It will be visible after moderation.');
    }

    public function deleteReview(Request $request, ProductReview $review): JsonResponse
    {
        if ($review->user_id !== $request->user()->id) {
            return $this->error('You are not allowed to delete this review.', 403);
        }

        $review->delete();

        return $this->success([], 'Review deleted successfully.');
    }
}
