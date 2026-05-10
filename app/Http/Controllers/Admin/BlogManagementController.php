<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogManagementController extends Controller
{
    public function index(Request $request)
    {
        $blogs = Blog::query()
            ->with('category', 'author')
            ->when($request->q, fn($q) => $q->where('title', 'like', "%{$request->q}%"))
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest('id')
            ->paginate(25)
            ->withQueryString();

        return view('admin.blogs.index', compact('blogs'));
    }

    public function create()
    {
        $categories = BlogCategory::orderBy('name')->get();
        return view('admin.blogs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'blog_category_id' => 'nullable|exists:blog_categories,id',
            'title'            => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:blogs,slug',
            'excerpt'          => 'nullable|string|max:500',
            'content'          => 'nullable|string',
            'featured_image'   => 'nullable|string|max:500',
            'status'           => 'required|in:draft,published',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['author_id'] = auth()->id();

        if ($data['status'] === 'published') {
            $data['published_at'] = now();
        }

        Blog::create($data);

        return redirect()->route('admin.blogs.index')->with('status', 'Blog post created.');
    }

    public function edit(Blog $blog)
    {
        $categories = BlogCategory::orderBy('name')->get();
        return view('admin.blogs.edit', compact('blog', 'categories'));
    }

    public function update(Request $request, Blog $blog)
    {
        $data = $request->validate([
            'blog_category_id' => 'nullable|exists:blog_categories,id',
            'title'            => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:blogs,slug,' . $blog->id,
            'excerpt'          => 'nullable|string|max:500',
            'content'          => 'nullable|string',
            'featured_image'   => 'nullable|string|max:500',
            'status'           => 'required|in:draft,published',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $data['slug'] = $data['slug'] ?: ($blog->slug ?: Str::slug($data['title']));

        if ($data['status'] === 'published' && !$blog->published_at) {
            $data['published_at'] = now();
        } elseif ($data['status'] === 'draft') {
            $data['published_at'] = null;
        }

        $blog->update($data);

        return redirect()->route('admin.blogs.index')->with('status', 'Blog post updated.');
    }

    public function destroy(Blog $blog)
    {
        $blog->delete();

        return redirect()->route('admin.blogs.index')->with('status', 'Blog post deleted.');
    }

    public function bulk(Request $request)
    {
        $request->validate([
            'bulk_action' => 'required|in:publish,draft,delete',
            'ids'         => 'required|array',
            'ids.*'       => 'integer|exists:blogs,id',
        ]);

        $ids = $request->input('ids');

        match ($request->input('bulk_action')) {
            'publish' => Blog::whereIn('id', $ids)->update(['status' => 'published', 'published_at' => now()]),
            'draft'   => Blog::whereIn('id', $ids)->update(['status' => 'draft', 'published_at' => null]),
            'delete'  => Blog::whereIn('id', $ids)->delete(),
        };

        return redirect()->route('admin.blogs.index')->with('status', 'Bulk action applied to ' . count($ids) . ' posts.');
    }
}
