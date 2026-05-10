<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MenuManagerController extends Controller
{
    public function index(): JsonResponse
    {
        $menus = Menu::with(['items.children'])->orderBy('name')->get();

        return response()->json(['success' => true, 'data' => $menus]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255|unique:menus,location',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $menu = Menu::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Menu created successfully.',
            'data' => $menu,
        ], 201);
    }

    public function update(Request $request, Menu $menu): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'location' => 'nullable|string|max:255|unique:menus,location,' . $menu->id,
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $menu->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Menu updated successfully.',
            'data' => $menu->fresh(),
        ]);
    }

    public function destroy(Menu $menu): JsonResponse
    {
        $menu->delete();

        return response()->json([
            'success' => true,
            'message' => 'Menu deleted successfully.',
        ]);
    }

    public function storeItem(Request $request, Menu $menu): JsonResponse
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:menu_items,id',
            'page_id' => 'nullable|exists:pages,id',
            'label' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'target' => 'nullable|in:_self,_blank',
            'css_class' => 'nullable|string|max:255',
            'position' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $item = $menu->items()->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Menu item created successfully.',
            'data' => $item,
        ], 201);
    }

    public function updateItem(Request $request, MenuItem $item): JsonResponse
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:menu_items,id',
            'page_id' => 'nullable|exists:pages,id',
            'label' => 'sometimes|required|string|max:255',
            'url' => 'nullable|string|max:255',
            'target' => 'nullable|in:_self,_blank',
            'css_class' => 'nullable|string|max:255',
            'position' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $item->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Menu item updated successfully.',
            'data' => $item->fresh(),
        ]);
    }

    public function destroyItem(MenuItem $item): JsonResponse
    {
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Menu item deleted successfully.',
        ]);
    }
}
