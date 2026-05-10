<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PricingPlan;
use App\Models\Product;
use App\Support\HomepageSectionBlueprint;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Schema;

class HomepageController extends Controller
{
    public function sections(): JsonResponse
    {
        if (!Schema::hasTable('pages') || !Schema::hasTable('page_sections')) {
            return response()->json([
                'success' => true,
                'message' => 'Homepage CMS tables are not migrated yet.',
                'data' => [
                    'homepage' => null,
                    'sections' => [],
                ],
            ]);
        }

        $homepage = Page::query()
            ->where(function ($query) {
                $query->where('is_homepage', true)
                    ->orWhere('slug', 'home');
            })
            ->with(['sections' => fn ($q) => $q->where('is_active', true)->orderBy('position')])
            ->first();

        $sections = ($homepage?->sections ?? collect())
            ->map(function ($section) {
                $type = $section->section_type ?: $section->section_key;
                $section->section_type = $type;
                $section->data = HomepageSectionBlueprint::normalize($type, $section->data);

                if ($type === 'product_highlights') {
                    $ids = array_filter((array) ($section->data['product_ids'] ?? []));
                    $limit = (int) ($section->data['limit'] ?? 6);
                    $section->data['products'] = Product::query()
                        ->when($ids, fn ($q) => $q->whereIn('id', $ids))
                        ->where('is_active', true)
                        ->orderByDesc('is_featured')
                        ->latest()
                        ->limit($limit > 0 ? $limit : 6)
                        ->get(['id', 'name', 'slug', 'price', 'sale_price', 'image']);
                }

                if ($type === 'pricing_plans') {
                    $ids = array_filter((array) ($section->data['plan_ids'] ?? []));
                    $section->data['plans'] = PricingPlan::query()
                        ->when($ids, fn ($q) => $q->whereIn('id', $ids))
                        ->where('is_active', true)
                        ->orderBy('sort_order')
                        ->get(['id', 'name', 'slug', 'price', 'billing_cycle', 'features']);
                }

                return $section;
            })
            ->filter(fn ($section) => in_array($section->section_type, HomepageSectionBlueprint::TYPES, true))
            ->values();

        return response()->json([
            'success' => true,
            'data' => [
                'homepage' => $homepage ? [
                    'id' => $homepage->id,
                    'title' => $homepage->title,
                    'slug' => $homepage->slug,
                ] : null,
                'sections' => $sections,
            ],
        ]);
    }
}
