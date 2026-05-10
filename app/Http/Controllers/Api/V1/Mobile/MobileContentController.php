<?php

namespace App\Http\Controllers\Api\V1\Mobile;

use App\Models\Blog;
use App\Models\ContactMessage;
use App\Models\Menu;
use App\Models\Page;
use App\Models\PricingPlan;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class MobileContentController extends BaseMobileController
{
    public function homepage(): JsonResponse
    {
        if (! Schema::hasTable('pages')) {
            return $this->success([
                'homepage' => null,
                'sections' => [],
                'featured_products' => [],
                'pricing_plans' => [],
                'latest_blogs' => [],
                'menus' => [],
            ], 'Homepage data unavailable until migrations are run.');
        }

        $homepage = Page::query()
            ->where('is_homepage', true)
            ->where('status', 'published')
            ->first();

        $sections = [];
        if ($homepage && Schema::hasTable('page_sections')) {
            $sections = $homepage->sections()
                ->where('is_active', true)
                ->orderBy('position')
                ->get()
                ->map(function ($section) {
                    return [
                        'id' => $section->id,
                        'key' => $section->section_key,
                        'type' => $section->section_type ?: $section->section_key,
                        'title' => $section->title,
                        'content' => $section->content,
                        'settings' => $section->settings ?? [],
                        'data' => $section->data ?? [],
                        'position' => $section->position,
                    ];
                })
                ->values();
        }

        $featuredProducts = [];
        if (Schema::hasTable('products')) {
            $featuredProducts = Product::query()
                ->when(Schema::hasColumn('products', 'is_active'), fn ($q) => $q->where('is_active', true))
                ->when(Schema::hasColumn('products', 'status'), fn ($q) => $q->where('status', 'published'))
                ->when(Schema::hasColumn('products', 'is_featured'), fn ($q) => $q->where('is_featured', true))
                ->orderByDesc('id')
                ->limit(8)
                ->get(['id', 'name', 'slug', 'image', 'price', 'sale_price']);
        }

        $pricingPlans = [];
        if (Schema::hasTable('pricing_plans')) {
            $pricingPlans = PricingPlan::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->limit(6)
                ->get(['id', 'name', 'slug', 'price', 'duration', 'billing_cycle', 'features']);
        }

        $latestBlogs = [];
        if (Schema::hasTable('blogs')) {
            $latestBlogs = Blog::query()
                ->when(Schema::hasColumn('blogs', 'status'), fn ($q) => $q->where('status', 'published'))
                ->orderByDesc('published_at')
                ->limit(5)
                ->get(['id', 'title', 'slug', 'excerpt', 'featured_image', 'published_at']);
        }

        $menus = [];
        if (Schema::hasTable('menus')) {
            $menus = Menu::query()
                ->where('is_active', true)
                ->with(['items.children'])
                ->get();
        }

        return $this->success([
            'homepage' => $homepage ? [
                'id' => $homepage->id,
                'title' => $homepage->title,
                'slug' => $homepage->slug,
                'meta_title' => $homepage->meta_title,
                'meta_description' => $homepage->meta_description,
            ] : null,
            'sections' => $sections,
            'featured_products' => $featuredProducts,
            'pricing_plans' => $pricingPlans,
            'latest_blogs' => $latestBlogs,
            'menus' => $menus,
        ], 'Homepage payload fetched successfully.');
    }

    public function products(Request $request): JsonResponse
    {
        if (! Schema::hasTable('products')) {
            return $this->error('Products table is not migrated yet.', 503);
        }

        $query = Product::query()
            ->when(Schema::hasColumn('products', 'is_active'), fn ($q) => $q->where('is_active', true))
            ->when(Schema::hasColumn('products', 'status'), fn ($q) => $q->where('status', 'published'))
            ->with('productCategory:id,name,slug');

        if ($request->filled('category_id')) {
            $query->where('product_category_id', $request->integer('category_id'));
        }

        if ($request->filled('featured')) {
            $query->where('is_featured', filter_var($request->input('featured'), FILTER_VALIDATE_BOOL));
        }

        if ($request->filled('search')) {
            $keyword = $request->string('search')->toString();
            $query->where(function ($nested) use ($keyword) {
                $nested->where('name', 'like', "%{$keyword}%")
                    ->orWhere('short_description', 'like', "%{$keyword}%");
            });
        }

        $perPage = min(max((int) $request->integer('per_page', 10), 1), 50);
        $products = $query->orderByDesc('id')->paginate($perPage);

        return $this->success(
            $products->items(),
            'Products fetched successfully.',
            200,
            $this->paginationMeta($products)
        );
    }

    public function productShow(string $slug): JsonResponse
    {
        if (! Schema::hasTable('products')) {
            return $this->error('Products table is not migrated yet.', 503);
        }

        $product = Product::query()
            ->with(['productCategory:id,name,slug'])
            ->where('slug', $slug)
            ->when(Schema::hasColumn('products', 'is_active'), fn ($q) => $q->where('is_active', true))
            ->when(Schema::hasColumn('products', 'status'), fn ($q) => $q->where('status', 'published'))
            ->first();

        if (! $product) {
            return $this->error('Product not found.', 404);
        }

        return $this->success($product, 'Product details fetched successfully.');
    }

    public function pricingPlans(Request $request): JsonResponse
    {
        if (! Schema::hasTable('pricing_plans')) {
            return $this->error('Pricing plans table is not migrated yet.', 503);
        }

        $perPage = min(max((int) $request->integer('per_page', 10), 1), 50);
        $plans = PricingPlan::query()
            ->where('is_active', true)
            ->with(['products:id,name,slug,image,price,sale_price'])
            ->orderBy('sort_order')
            ->paginate($perPage);

        return $this->success(
            $plans->items(),
            'Pricing plans fetched successfully.',
            200,
            $this->paginationMeta($plans)
        );
    }

    public function pricingPlanShow(string $slug): JsonResponse
    {
        if (! Schema::hasTable('pricing_plans')) {
            return $this->error('Pricing plans table is not migrated yet.', 503);
        }

        $plan = PricingPlan::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->with(['products:id,name,slug,image,price,sale_price'])
            ->first();

        if (! $plan) {
            return $this->error('Pricing plan not found.', 404);
        }

        return $this->success($plan, 'Pricing plan details fetched successfully.');
    }

    public function blogs(Request $request): JsonResponse
    {
        if (! Schema::hasTable('blogs')) {
            return $this->error('Blogs table is not migrated yet.', 503);
        }

        $query = Blog::query()
            ->with(['category:id,name,slug'])
            ->when(Schema::hasColumn('blogs', 'status'), fn ($q) => $q->where('status', 'published'))
            ->orderByDesc('published_at');

        if ($request->filled('category_id')) {
            $query->where('blog_category_id', $request->integer('category_id'));
        }

        if ($request->filled('search')) {
            $keyword = $request->string('search')->toString();
            $query->where(function ($nested) use ($keyword) {
                $nested->where('title', 'like', "%{$keyword}%")
                    ->orWhere('excerpt', 'like', "%{$keyword}%");
            });
        }

        $perPage = min(max((int) $request->integer('per_page', 10), 1), 50);
        $blogs = $query->paginate($perPage);

        return $this->success(
            $blogs->items(),
            'Blogs fetched successfully.',
            200,
            $this->paginationMeta($blogs)
        );
    }

    public function blogShow(string $slug): JsonResponse
    {
        if (! Schema::hasTable('blogs')) {
            return $this->error('Blogs table is not migrated yet.', 503);
        }

        $blog = Blog::query()
            ->with(['category:id,name,slug', 'author:id,name'])
            ->where('slug', $slug)
            ->when(Schema::hasColumn('blogs', 'status'), fn ($q) => $q->where('status', 'published'))
            ->first();

        if (! $blog) {
            return $this->error('Blog not found.', 404);
        }

        return $this->success($blog, 'Blog details fetched successfully.');
    }

    public function menus(Request $request): JsonResponse
    {
        if (! Schema::hasTable('menus')) {
            return $this->error('Menus table is not migrated yet.', 503);
        }

        $query = Menu::query()
            ->where('is_active', true)
            ->with(['items.children']);

        if ($request->filled('location')) {
            $query->where('location', $request->string('location')->toString());
        }

        $menus = $query->orderBy('id')->get();

        return $this->success($menus, 'Menus fetched successfully.');
    }

    public function submitContactForm(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:30',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        if (! Schema::hasTable('contact_messages')) {
            return $this->error('Contact form table is not migrated yet.', 503);
        }

        $message = ContactMessage::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'subject' => $validated['subject'] ?? null,
            'message' => $validated['message'],
            'status' => 'new',
            'source' => 'mobile_app',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return $this->success([
            'id' => $message->id,
            'status' => $message->status,
            'submitted_at' => optional($message->created_at)->toIso8601String(),
        ], 'Contact form submitted successfully.', 201);
    }
}
