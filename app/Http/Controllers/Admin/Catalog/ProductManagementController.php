<?php

namespace App\Http\Controllers\Admin\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductManagementController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()
            ->with('category')
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = $request->string('q');
                $query->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', '%' . $search . '%')
                        ->orWhere('slug', 'like', '%' . $search . '%');
                });
            })
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::query()->orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatePayload($request);

        Product::create($data);

        return redirect()->route('admin.products.index')->with('status', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::query()->orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validatePayload($request, $product->id);

        $product->update($data);

        return redirect()->route('admin.products.index')->with('status', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('status', 'Product deleted successfully.');
    }

    private function validatePayload(Request $request, ?int $productId = null): array
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . ($productId ?? 'NULL') . ',id',
            'short_description' => 'nullable|string|max:800',
            'description' => 'nullable|string|max:5000',
            'ingredients' => 'nullable|string|max:5000',
            'age_group' => 'required|string|max:40',
            'type' => 'required|in:puree,puffs,cookies',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|string|max:1000',
            'gallery' => 'nullable|string',
            'badges' => 'nullable|string',
            'nutrition_facts' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['status'] = $validated['is_active'] ? 'published' : 'draft';

        $validated['gallery'] = collect(preg_split('/\r\n|\r|\n/', (string) ($validated['gallery'] ?? '')))
            ->map(fn ($value) => trim($value))
            ->filter()
            ->values()
            ->all();

        $validated['badges'] = collect(explode(',', (string) ($validated['badges'] ?? '')))
            ->map(fn ($value) => trim($value))
            ->filter()
            ->values()
            ->all();

        $nutritionRaw = trim((string) ($validated['nutrition_facts'] ?? ''));
        if ($nutritionRaw !== '') {
            $decoded = json_decode($nutritionRaw, true);
            $validated['nutrition_facts'] = is_array($decoded) ? $decoded : ['info' => $nutritionRaw];
        } else {
            $validated['nutrition_facts'] = [];
        }

        return $validated;
    }
}
