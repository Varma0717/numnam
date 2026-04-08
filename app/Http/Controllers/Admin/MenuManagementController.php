<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MenuManagementController extends Controller
{
    public function index()
    {
        $menus = Menu::withCount('items')->latest('id')->paginate(20);

        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        $pages = Page::where('status', 'published')->orderBy('title')->get();

        return view('admin.menus.create', compact('pages'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'location'    => 'nullable|string|max:80',
            'description' => 'nullable|string|max:500',
            'is_active'   => 'nullable',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $menu = Menu::create($data);

        $this->saveItems($request, $menu);

        return redirect()->route('admin.menus.index')->with('status', 'Menu created.');
    }

    public function edit(Menu $menu)
    {
        $menu->load(['items.children']);
        $pages = Page::where('status', 'published')->orderBy('title')->get();

        return view('admin.menus.edit', compact('menu', 'pages'));
    }

    public function update(Request $request, Menu $menu): RedirectResponse
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'location'    => 'nullable|string|max:80',
            'description' => 'nullable|string|max:500',
            'is_active'   => 'nullable',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $menu->update($data);

        // Sync items
        $menu->items()->delete();
        $this->saveItems($request, $menu);

        return redirect()->route('admin.menus.index')->with('status', 'Menu updated.');
    }

    public function destroy(Menu $menu): RedirectResponse
    {
        $menu->items()->delete();
        $menu->delete();

        return redirect()->route('admin.menus.index')->with('status', 'Menu deleted.');
    }

    private function saveItems(Request $request, Menu $menu): void
    {
        foreach ($request->input('items', []) as $i => $item) {
            if (empty($item['label'])) {
                continue;
            }

            $menu->items()->create([
                'label'     => $item['label'],
                'url'       => $item['url'] ?? '',
                'page_id'   => !empty($item['page_id']) ? $item['page_id'] : null,
                'target'    => $item['target'] ?? '_self',
                'css_class' => $item['css_class'] ?? '',
                'position'  => $i,
                'is_active' => !empty($item['is_active']),
            ]);
        }
    }
}
