<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\PricingPlan;
use App\Models\Product;
use App\Support\HomepageSectionBlueprint;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function index(): View
    {
        if (!Schema::hasTable('pages') || !Schema::hasTable('page_sections')) {
            return view('home.index', [
                'homepage' => null,
                'sections' => collect(),
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

                    $products = Product::query()
                        ->when($ids, fn ($q) => $q->whereIn('id', $ids))
                        ->where('is_active', true)
                        ->orderByDesc('is_featured')
                        ->latest()
                        ->limit($limit > 0 ? $limit : 6)
                        ->get();

                    $section->render_items = $products;
                }

                if ($type === 'pricing_plans') {
                    $ids = array_filter((array) ($section->data['plan_ids'] ?? []));

                    $plans = PricingPlan::query()
                        ->when($ids, fn ($q) => $q->whereIn('id', $ids))
                        ->where('is_active', true)
                        ->orderBy('sort_order')
                        ->get();

                    $section->render_items = $plans;
                }

                return $section;
            })
            ->filter(fn ($section) => in_array($section->section_type, HomepageSectionBlueprint::TYPES, true))
            ->values();

        return view('home.index', [
            'homepage' => $homepage,
            'sections' => $sections,
        ]);
    }
}
