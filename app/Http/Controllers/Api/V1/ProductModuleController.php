<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductModuleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $products = Product::query()
            ->with('productCategory:id,name,slug')
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = $request->string('search');
                $query->where(function ($nested) use ($term) {
                    $nested->where('name', 'like', '%' . $term . '%')
                        ->orWhere('description', 'like', '%' . $term . '%')
                        ->orWhere('ingredients', 'like', '%' . $term . '%');
                });
            })
            ->when($request->filled('product_category_id'), fn ($q) => $q->where('product_category_id', (int) $request->input('product_category_id')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->orderByDesc('is_featured')
            ->orderBy('name')
            ->paginate((int) $request->input('per_page', 12));

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    public function show(Product $product): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $product->load('productCategory:id,name,slug'),
        ]);
    }
}
