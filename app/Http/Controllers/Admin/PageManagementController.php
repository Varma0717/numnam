<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageManagementController extends Controller
{
    public function index(Request $request)
    {
        $pages = Page::query()
            ->withCount('sections')
            ->when($request->filled('q'), fn($query) => $query->where('title', 'like', '%' . $request->string('q') . '%'))
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:pages,slug',
            'template'         => 'nullable|string|max:80',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'status'           => 'required|in:draft,published',
            'sort_order'       => 'nullable|integer',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);

        if ($data['status'] === 'published') {
            $data['published_at'] = now();
        }

        $page = Page::create($data);

        // Save sections
        $this->saveSections($request, $page);

        return redirect()->route('admin.pages.index')->with('status', 'Page created.');
    }

    public function edit(Page $page)
    {
        $page->load('sections');

        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:pages,slug,' . $page->id,
            'template'         => 'nullable|string|max:80',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'status'           => 'required|in:draft,published',
            'sort_order'       => 'nullable|integer',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);

        if ($data['status'] === 'published' && !$page->published_at) {
            $data['published_at'] = now();
        } elseif ($data['status'] === 'draft') {
            $data['published_at'] = null;
        }

        $page->update($data);

        // Sync sections
        $page->sections()->delete();
        $this->saveSections($request, $page);

        return redirect()->route('admin.pages.index')->with('status', 'Page updated.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        $page->sections()->delete();
        $page->delete();

        return redirect()->route('admin.pages.index')->with('status', 'Page deleted.');
    }

    private function saveSections(Request $request, Page $page): void
    {
        foreach ($request->input('sections', []) as $i => $section) {
            if (empty($section['section_key']) && empty($section['content'])) {
                continue;
            }

            $page->sections()->create([
                'section_key'  => $section['section_key'] ?? 'section_' . $i,
                'section_type' => $section['section_type'] ?? 'content',
                'title'        => $section['title'] ?? '',
                'content'      => $section['content'] ?? '',
                'position'     => $i,
                'is_active'    => !empty($section['is_active']),
            ]);
        }
    }
}
