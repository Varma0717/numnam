<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /** GET /api/v1/categories */
    public function index(): JsonResponse
    {
        $categories = Category::where('is_active', true)
            ->withCount('products')
            ->get();

        return response()->json(['data' => $categories]);
    }

    /** GET /api/v1/categories/{slug} */
    public function show(string $slug): JsonResponse
    {
        $category = Category::where('slug', $slug)
            ->withCount('products')
            ->firstOrFail();

        return response()->json(['data' => $category]);
    }

    /** POST /api/v1/categories */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255|unique:categories,name',
            'image' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $category = Category::create($validated);

        return response()->json(['data' => $category, 'message' => 'Category created.'], 201);
    }

    /** PUT /api/v1/categories/{id} */
    public function update(Request $request, Category $category): JsonResponse
    {
        $validated = $request->validate([
            'name'      => 'sometimes|string|max:255',
            'image'     => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $category->update($validated);

        return response()->json(['data' => $category, 'message' => 'Category updated.']);
    }

    /** DELETE /api/v1/categories/{id} */
    public function destroy(Category $category): JsonResponse
    {
        $category->update(['is_active' => false]);
        return response()->json(['message' => 'Category deactivated.']);
    }
}
