<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $pages = Page::query()
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->orderBy('sort_order')
            ->orderBy('title')
            ->paginate((int) $request->input('per_page', 20));

        return response()->json(['success' => true, 'data' => $pages]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'template' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'sort_order' => 'nullable|integer|min:0',
            'is_homepage' => 'nullable|boolean',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['title']);

        $page = Page::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Page created successfully.',
            'data' => $page,
        ], 201);
    }

    public function show(Page $page): JsonResponse
    {
        $page->load('sections');

        return response()->json(['success' => true, 'data' => $page]);
    }

    public function update(Request $request, Page $page): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug,' . $page->id,
            'template' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'status' => 'sometimes|required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'sort_order' => 'nullable|integer|min:0',
            'is_homepage' => 'nullable|boolean',
        ]);

        if (isset($validated['title']) && empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $page->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Page updated successfully.',
            'data' => $page->fresh(),
        ]);
    }

    public function destroy(Page $page): JsonResponse
    {
        $page->delete();

        return response()->json([
            'success' => true,
            'message' => 'Page deleted successfully.',
        ]);
    }
}
