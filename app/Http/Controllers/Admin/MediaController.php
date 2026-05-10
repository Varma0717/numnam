<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaLibrary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        if (!Schema::hasTable('media_library')) {
            return response()->json(['data' => []]);
        }

        $media = MediaLibrary::query()
            ->when($request->filled('folder'), fn($q) => $q->where('folder', $request->string('folder')))
            ->orderByDesc('created_at')
            ->paginate((int) $request->input('per_page', 60));

        return response()->json(['data' => $media]);
    }

    public function folders(): JsonResponse
    {
        if (!Schema::hasTable('media_library')) {
            return response()->json(['data' => []]);
        }

        $folders = MediaLibrary::query()
            ->select('folder')
            ->distinct()
            ->orderBy('folder')
            ->pluck('folder');

        return response()->json(['data' => $folders]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|max:10240|mimes:jpg,jpeg,png,gif,webp,svg',
            'folder' => 'nullable|string|max:100',
            'title' => 'nullable|string|max:255',
            'alt_text' => 'nullable|string|max:255',
        ]);

        $file = $request->file('file');
        $folder = $request->input('folder', 'general');
        $path = $file->store('cms-media/' . $folder, 'public');

        $media = MediaLibrary::create([
            'disk' => 'public',
            'folder' => $folder,
            'collection' => 'images',
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'title' => $request->input('title', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)),
            'alt_text' => $request->input('alt_text'),
            'is_public' => true,
            'metadata' => [
                'extension' => $file->getClientOriginalExtension(),
                'url' => Storage::disk('public')->url($path),
            ],
        ]);

        return response()->json(['success' => true, 'data' => $media], 201);
    }

    public function destroy(MediaLibrary $media): JsonResponse
    {
        Storage::disk($media->disk)->delete($media->file_path);
        $media->delete();

        return response()->json(['success' => true]);
    }
}
