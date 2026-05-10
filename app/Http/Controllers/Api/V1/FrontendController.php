<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\Product;
use App\Support\HomepageSectionBlueprint;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class FrontendController extends Controller
{
    public function pages(Request $request): JsonResponse
    {
        if (!$this->isPagesReady()) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $pages = Page::query()
            ->where('status', 'published')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->paginate((int) $request->input('per_page', 20));

        /** @var \Illuminate\Pagination\LengthAwarePaginator $pages */
        $payload = $pages->through(fn(Page $page) => $this->transformPageSummary($page));

        return response()->json([
            'success' => true,
            'data' => $payload,
        ]);
    }

    public function page(string $slug): JsonResponse
    {
        if (!$this->isPagesReady()) {
            return response()->json(['success' => false, 'message' => 'Page tables are not migrated yet.'], 400);
        }

        $page = Page::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->with(['sections' => fn($query) => $query->where('is_active', true)->orderBy('position')])
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $this->assemblePagePayload($page),
        ]);
    }

    public function homepageSections(): JsonResponse
    {
        if (!$this->isPagesReady()) {
            return response()->json(['success' => true, 'data' => ['sections' => [], 'seo' => []]]);
        }

        $homepage = Page::query()
            ->where(function (Builder $query) {
                $query->where('is_homepage', true)
                    ->orWhere('slug', 'home');
            })
            ->where('status', 'published')
            ->with(['sections' => fn($query) => $query->where('is_active', true)->orderBy('position')])
            ->first();

        if (!$homepage) {
            return response()->json(['success' => true, 'data' => ['sections' => [], 'seo' => []]]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'sections' => $this->transformSections($homepage),
                'seo' => $this->seoForPage($homepage),
            ],
        ]);
    }

    public function menus(Request $request): JsonResponse
    {
        if (!Schema::hasTable('menus') || !Schema::hasTable('menu_items')) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $menus = Menu::query()
            ->where('is_active', true)
            ->when($request->filled('location'), fn(Builder $query) => $query->where('location', $request->string('location')))
            ->with([
                'items' => fn($query) => $query->where('is_active', true)->with([
                    'page:id,slug,title',
                    'children' => fn($children) => $children->where('is_active', true)->with('page:id,slug,title')->orderBy('position'),
                ])->orderBy('position'),
            ])
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $menus->map(fn(Menu $menu) => [
                'id' => $menu->id,
                'name' => $menu->name,
                'location' => $menu->location,
                'items' => $menu->items->map(fn($item) => $this->transformMenuItem($item))->values(),
            ])->values(),
        ]);
    }

    public function products(Request $request): JsonResponse
    {
        if (!Schema::hasTable('products')) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $products = Product::query()
            ->where('is_active', true)
            ->with('productCategory:id,name,slug')
            ->when($request->filled('search'), function (Builder $query) use ($request) {
                $term = $request->string('search');
                $query->where(function (Builder $nested) use ($term) {
                    $nested->where('name', 'like', '%' . $term . '%')
                        ->orWhere('description', 'like', '%' . $term . '%')
                        ->orWhere('ingredients', 'like', '%' . $term . '%');
                });
            })
            ->orderByDesc('is_featured')
            ->orderBy('name')
            ->paginate((int) $request->input('per_page', 12));

        /** @var \Illuminate\Pagination\LengthAwarePaginator $products */
        $payload = $products->through(function (Product $product) {
            return [
                'id' => $product->id,
                'slug' => $product->slug,
                'name' => $product->name,
                'description' => $product->description,
                'ingredients' => $product->ingredients,
                'images' => [
                    'featured' => $product->image_url,
                    'gallery' => $product->gallery_urls,
                ],
                'nutrition_info' => $product->nutrition_info ?? $product->nutrition_facts,
                'pricing' => [
                    'price' => $product->price,
                    'sale_price' => $product->sale_price,
                ],
                'seo' => [
                    'title' => $product->meta_title ?: $product->name,
                    'description' => $product->meta_description ?: Str::limit(strip_tags((string) $product->description), 160),
                    'canonical_url' => url('/products/' . $product->slug),
                ],
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $payload,
        ]);
    }

    public function render(string $slug = 'home'): JsonResponse
    {
        if (!$this->isPagesReady()) {
            return response()->json(['success' => true, 'data' => ['page' => null, 'menus' => [], 'products' => []]]);
        }

        $page = Page::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->with(['sections' => fn($query) => $query->where('is_active', true)->orderBy('position')])
            ->first();

        if (!$page && $slug === 'home') {
            $page = Page::query()
                ->where(function (Builder $query) {
                    $query->where('is_homepage', true)
                        ->orWhere('slug', 'home');
                })
                ->where('status', 'published')
                ->with(['sections' => fn($query) => $query->where('is_active', true)->orderBy('position')])
                ->first();
        }

        $menus = Menu::query()
            ->where('is_active', true)
            ->with([
                'items' => fn($query) => $query->where('is_active', true)->with('children')->orderBy('position'),
            ])
            ->orderBy('name')
            ->get();

        $products = Product::query()
            ->where('is_active', true)
            ->orderByDesc('is_featured')
            ->limit(12)
            ->get(['id', 'name', 'slug', 'price', 'sale_price', 'image']);

        return response()->json([
            'success' => true,
            'data' => [
                'page' => $page ? $this->assemblePagePayload($page) : null,
                'menus' => $menus->map(fn(Menu $menu) => [
                    'id' => $menu->id,
                    'name' => $menu->name,
                    'location' => $menu->location,
                    'items' => $menu->items->map(fn($item) => $this->transformMenuItem($item))->values(),
                ])->values(),
                'products' => $products,
            ],
        ]);
    }

    private function assemblePagePayload(Page $page): array
    {
        return [
            'id' => $page->id,
            'slug' => $page->slug,
            'title' => $page->title,
            'status' => $page->status,
            'seo' => $this->seoForPage($page),
            'sections' => $this->transformSections($page),
        ];
    }

    private function transformPageSummary(Page $page): array
    {
        return [
            'id' => $page->id,
            'slug' => $page->slug,
            'title' => $page->title,
            'seo' => $this->seoForPage($page),
        ];
    }

    private function transformSections(Page $page): array
    {
        return $page->sections
            ->map(function (PageSection $section) {
                $type = $section->section_type ?: $section->section_key;
                $data = HomepageSectionBlueprint::normalize($type, $section->data);

                return [
                    'id' => $section->id,
                    'component' => $type,
                    'position' => $section->position,
                    'title' => $section->title,
                    'content' => $section->content,
                    'settings' => $section->settings,
                    'data' => $data,
                ];
            })
            ->values()
            ->all();
    }

    private function seoForPage(Page $page): array
    {
        $fallbackDescription = $page->sections->first()?->content ?: 'NumNam page';

        return [
            'title' => $page->meta_title ?: $page->title,
            'description' => $page->meta_description ?: Str::limit(strip_tags((string) $fallbackDescription), 160),
            'canonical_url' => url('/' . ltrim($page->slug, '/')),
            'robots' => 'index,follow',
        ];
    }

    private function transformMenuItem($item): array
    {
        $itemUrl = $item->url ?: ($item->page ? url('/' . ltrim($item->page->slug, '/')) : '#');

        return [
            'id' => $item->id,
            'label' => $item->label,
            'url' => $itemUrl,
            'target' => $item->target,
            'css_class' => $item->css_class,
            'children' => $item->children->map(fn($child) => $this->transformMenuItem($child))->values(),
        ];
    }

    private function isPagesReady(): bool
    {
        return Schema::hasTable('pages') && Schema::hasTable('page_sections');
    }
}
