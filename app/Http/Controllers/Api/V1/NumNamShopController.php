<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\NumNamProduct;
use Illuminate\Http\Request;

class NumNamShopController extends Controller
{
    /**
     * Get all products or filter by category
     */
    public function index(Request $request)
    {
        $category = $request->get('category', null);

        if ($category) {
            $products = NumNamProduct::getByCategory($category);
        } else {
            $products = NumNamProduct::getAll();
        }

        return response()->json([
            'category' => $category,
            'products' => $products,
            'count' => $products->count(),
        ]);
    }

    /**
     * Get single product
     */
    public function show(NumNamProduct $product)
    {
        return response()->json($product);
    }

    /**
     * Get products by category
     */
    public function byCategory($category)
    {
        $validated = $category;

        if (!in_array($category, ['purées', 'snacks', 'bundle', 'experience'])) {
            return response()->json(['error' => 'Invalid category'], 400);
        }

        $products = NumNamProduct::getByCategory($category);

        return response()->json([
            'category' => $category,
            'products' => $products,
            'count' => $products->count(),
        ]);
    }

    /**
     * Get featured products
     */
    public function featured()
    {
        $products = NumNamProduct::where('is_active', true)
            ->orderBy('display_order')
            ->limit(12)
            ->get();

        return response()->json([
            'featured' => $products,
        ]);
    }
}
