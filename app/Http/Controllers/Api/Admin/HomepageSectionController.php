<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageSection;
use App\Support\HomepageSectionBlueprint;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class HomepageSectionController extends Controller
{
    public function index(): JsonResponse
    {
        if (!$this->isCmsReady()) {
            return response()->json([
                'success' => true,
                'message' => 'CMS tables are not migrated yet.',
                'data' => [
                    'homepage' => null,
                    'sections' => [],
                    'section_catalog' => HomepageSectionBlueprint::TYPES,
                    'defaults' => HomepageSectionBlueprint::defaults(),
                ],
            ]);
        }

        $homepage = Page::query()
            ->where('is_homepage', true)
            ->orWhere('slug', 'home')
            ->with(['sections' => fn ($q) => $q->orderBy('position')])
            ->first();

        $sections = ($homepage?->sections ?? collect())
            ->map(function (PageSection $section) {
                $type = $section->section_type ?: $section->section_key;
                $section->section_type = $type;
                $section->data = HomepageSectionBlueprint::normalize($type, $section->data);
                return $section;
            })
            ->values();

        return response()->json([
            'success' => true,
            'data' => [
                'homepage' => $homepage,
                'sections' => $sections,
                'section_catalog' => HomepageSectionBlueprint::TYPES,
                'defaults' => HomepageSectionBlueprint::defaults(),
            ],
        ]);
    }

    public function schema(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'section_catalog' => HomepageSectionBlueprint::TYPES,
                'defaults' => HomepageSectionBlueprint::defaults(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        if (!$this->isCmsReady()) {
            return response()->json(['success' => false, 'message' => 'CMS tables are not migrated yet.'], 400);
        }

        $validated = $request->validate([
            'page_id' => 'required|exists:pages,id',
            'section_type' => 'required|in:' . implode(',', HomepageSectionBlueprint::TYPES),
            'section_key' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'settings' => 'nullable|array',
            'data' => 'nullable|array',
            'position' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['section_key'] = $validated['section_key'] ?? $validated['section_type'];
        $validated['data'] = HomepageSectionBlueprint::normalize($validated['section_type'], $validated['data'] ?? []);

        $section = PageSection::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Homepage section created successfully.',
            'data' => $section,
        ], 201);
    }

    public function update(Request $request, PageSection $section): JsonResponse
    {
        if (!$this->isCmsReady()) {
            return response()->json(['success' => false, 'message' => 'CMS tables are not migrated yet.'], 400);
        }

        $validated = $request->validate([
            'section_type' => 'sometimes|required|in:' . implode(',', HomepageSectionBlueprint::TYPES),
            'section_key' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'settings' => 'nullable|array',
            'data' => 'nullable|array',
            'position' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if (isset($validated['section_type']) && empty($validated['section_key'])) {
            $validated['section_key'] = $validated['section_type'];
        }

        if (array_key_exists('data', $validated)) {
            $type = $validated['section_type'] ?? $section->section_type ?? $section->section_key;
            $validated['data'] = HomepageSectionBlueprint::normalize($type, $validated['data'] ?? []);
        }

        $section->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Homepage section updated successfully.',
            'data' => $section->fresh(),
        ]);
    }

    public function destroy(PageSection $section): JsonResponse
    {
        if (!$this->isCmsReady()) {
            return response()->json(['success' => false, 'message' => 'CMS tables are not migrated yet.'], 400);
        }

        $section->delete();

        return response()->json([
            'success' => true,
            'message' => 'Homepage section deleted successfully.',
        ]);
    }

    public function upsert(Request $request): JsonResponse
    {
        if (!$this->isCmsReady()) {
            return response()->json(['success' => false, 'message' => 'CMS tables are not migrated yet.'], 400);
        }

        $validated = $request->validate([
            'page_id' => 'required|exists:pages,id',
            'section_type' => 'required|in:' . implode(',', HomepageSectionBlueprint::TYPES),
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'settings' => 'nullable|array',
            'data' => 'nullable|array',
            'position' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $payload = [
            'section_key' => $validated['section_type'],
            'title' => $validated['title'] ?? null,
            'content' => $validated['content'] ?? null,
            'settings' => $validated['settings'] ?? null,
            'data' => HomepageSectionBlueprint::normalize($validated['section_type'], $validated['data'] ?? []),
            'position' => $validated['position'] ?? 0,
            'is_active' => $validated['is_active'] ?? true,
        ];

        $section = PageSection::updateOrCreate(
            [
                'page_id' => $validated['page_id'],
                'section_type' => $validated['section_type'],
            ],
            $payload,
        );

        return response()->json([
            'success' => true,
            'message' => 'Homepage section saved successfully.',
            'data' => $section,
        ]);
    }

    private function isCmsReady(): bool
    {
        return Schema::hasTable('pages') && Schema::hasTable('page_sections');
    }
}
