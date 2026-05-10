<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $settings = SiteSetting::query()
            ->when($request->filled('group'), fn ($q) => $q->where('group', $request->string('group')))
            ->orderBy('group')
            ->orderBy('key')
            ->get();

        return response()->json(['success' => true, 'data' => $settings]);
    }

    public function upsert(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'settings' => 'required|array|min:1',
            'settings.*.key' => 'required|string|max:255',
            'settings.*.value' => 'nullable',
            'settings.*.type' => 'nullable|string|max:50',
            'settings.*.group' => 'nullable|string|max:50',
            'settings.*.is_public' => 'nullable|boolean',
            'settings.*.autoload' => 'nullable|boolean',
        ]);

        $saved = [];

        foreach ($validated['settings'] as $item) {
            $value = $item['value'] ?? null;

            if (is_array($value) || is_object($value)) {
                $value = json_encode($value);
            } elseif (!is_null($value)) {
                $value = (string) $value;
            }

            $saved[] = SiteSetting::updateOrCreate(
                ['key' => $item['key']],
                [
                    'value' => $value,
                    'type' => $item['type'] ?? 'string',
                    'group' => $item['group'] ?? 'general',
                    'is_public' => $item['is_public'] ?? false,
                    'autoload' => $item['autoload'] ?? true,
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Settings saved successfully.',
            'data' => $saved,
        ]);
    }
}
