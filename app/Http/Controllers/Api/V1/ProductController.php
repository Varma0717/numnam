<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /** GET /api/v1/products */
    public function index(Request $request): JsonResponse
    {
        $query = Product::with('category')->where('is_active', true);

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('age_group')) {
            $query->where('age_group', $request->age_group);
        }
        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        $products = $query->orderByDesc('is_featured')
                          ->orderBy('name')
                          ->paginate($request->input('per_page', 12));

        return response()->json($products);
    }

    /** GET /api/v1/products/{slug} */
    public function show(string $slug): JsonResponse
    {
        $product = Product::with('category')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return response()->json(['data' => $product]);
    }

    /** POST /api/v1/products  (admin only — protected by Sanctum middleware) */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'category_id'     => 'required|exists:categories,id',
            'name'            => 'required|string|max:255',
            'description'     => 'nullable|string',
            'ingredients'     => 'nullable|string',
            'age_group'       => 'required|string',
            'type'            => 'required|in:puree,puffs,cookies',
            'price'           => 'required|numeric|min:0',
            'sale_price'      => 'nullable|numeric|min:0|lt:price',
            'stock'           => 'required|integer|min:0',
            'image'           => 'nullable|string',
            'gallery'         => 'nullable|array',
            'badges'          => 'nullable|array',
            'nutrition_facts' => 'nullable|array',
            'is_featured'     => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $product = Product::create($validated);

        return response()->json(['data' => $product, 'message' => 'Product created.'], 201);
    }

    /** PUT /api/v1/products/{id} */
    public function update(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            'name'        => 'sometimes|string|max:255',
            'price'       => 'sometimes|numeric|min:0',
            'sale_price'  => 'nullable|numeric|min:0',
            'stock'       => 'sometimes|integer|min:0',
            'is_active'   => 'sometimes|boolean',
            'is_featured' => 'sometimes|boolean',
        ]);

        if (isset($validated['name'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $product->update($validated);

        return response()->json(['data' => $product, 'message' => 'Product updated.']);
    }

    /** DELETE /api/v1/products/{id} */
    public function destroy(Product $product): JsonResponse
    {
        $product->update(['is_active' => false]); // soft-deactivate instead of delete
        return response()->json(['message' => 'Product deactivated.']);
    }
}
