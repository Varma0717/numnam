<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = BlogCategory::query()
            ->withCount('blogs')
            ->with('parent')
            ->when($request->q, fn($q) => $q->where('name', 'like', "%{$request->q}%"))
            ->latest('id')
            ->paginate(25)
            ->withQueryString();

        return view('admin.blog-categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = BlogCategory::whereNull('parent_id')->orderBy('name')->get();
        return view('admin.blog-categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:120',
            'slug'        => 'nullable|string|max:120|unique:blog_categories,slug',
            'description' => 'nullable|string|max:500',
            'parent_id'   => 'nullable|exists:blog_categories,id',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        BlogCategory::create($data);

        return redirect()->route('admin.blog-categories.index')->with('status', 'Blog category created.');
    }

    public function edit(BlogCategory $blogCategory)
    {
        $parents = BlogCategory::whereNull('parent_id')
            ->where('id', '!=', $blogCategory->id)
            ->orderBy('name')
            ->get();
        return view('admin.blog-categories.edit', compact('blogCategory', 'parents'));
    }

    public function update(Request $request, BlogCategory $blogCategory)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:120',
            'slug'        => 'nullable|string|max:120|unique:blog_categories,slug,' . $blogCategory->id,
            'description' => 'nullable|string|max:500',
            'parent_id'   => 'nullable|exists:blog_categories,id',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        $blogCategory->update($data);

        return redirect()->route('admin.blog-categories.index')->with('status', 'Blog category updated.');
    }

    public function destroy(BlogCategory $blogCategory)
    {
        $blogCategory->delete();

        return redirect()->route('admin.blog-categories.index')->with('status', 'Blog category deleted.');
    }
}
