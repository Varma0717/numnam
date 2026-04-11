<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogManagerController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $blogs = Blog::with(['category', 'author'])
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->string('status')))
            ->orderByDesc('updated_at')
            ->paginate((int) $request->input('per_page', 20));

        return response()->json(['success' => true, 'data' => $blogs]);
    }

    public function categories(): JsonResponse
    {
        $categories = BlogCategory::orderBy('name')->get();

        return response()->json(['success' => true, 'data' => $categories]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'blog_category_id' => 'nullable|exists:blog_categories,id',
            'author_id' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blogs,slug',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['title']);

        $blog = Blog::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Blog created successfully.',
            'data' => $blog,
        ], 201);
    }

    public function show(Blog $blog): JsonResponse
    {
        $blog->load(['category', 'author']);

        return response()->json(['success' => true, 'data' => $blog]);
    }

    public function update(Request $request, Blog $blog): JsonResponse
    {
        $validated = $request->validate([
            'blog_category_id' => 'nullable|exists:blog_categories,id',
            'author_id' => 'nullable|exists:users,id',
            'title' => 'sometimes|required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blogs,slug,' . $blog->id,
            'excerpt' => 'nullable|string',
            'content' => 'sometimes|required|string',
            'featured_image' => 'nullable|string|max:255',
            'status' => 'sometimes|required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        if (isset($validated['title']) && empty($validated['slug'])) {
            $validated['slug'] = $blog->slug ?: Str::slug($validated['title']);
        }

        $blog->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Blog updated successfully.',
            'data' => $blog->fresh(),
        ]);
    }

    public function destroy(Blog $blog): JsonResponse
    {
        $blog->delete();

        return response()->json([
            'success' => true,
            'message' => 'Blog deleted successfully.',
        ]);
    }
}
