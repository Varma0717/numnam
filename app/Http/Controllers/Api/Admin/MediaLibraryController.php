<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaLibraryLink;
use App\Models\MediaLibrary;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class MediaLibraryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        if (!$this->isMediaReady()) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $media = MediaLibrary::query()
            ->with('links')
            ->when($request->filled('folder'), fn (Builder $q) => $q->where('folder', $request->string('folder')))
            ->when($request->filled('collection'), fn (Builder $q) => $q->where('collection', $request->string('collection')))
            ->when($request->filled('entity_type') && $request->filled('entity_id'), function (Builder $q) use ($request) {
                $q->whereHas('links', function (Builder $linkQuery) use ($request) {
                    $linkQuery->where('entity_type', $request->string('entity_type'))
                        ->where('entity_id', (int) $request->input('entity_id'));
                });
            })
            ->orderByDesc('created_at')
            ->paginate((int) $request->input('per_page', 20));

        return response()->json(['success' => true, 'data' => $media]);
    }

    public function store(Request $request): JsonResponse
    {
        if (!$this->isMediaReady()) {
            return response()->json(['success' => false, 'message' => 'Media tables are not migrated yet.'], 400);
        }

        $validated = $request->validate([
            'file' => 'required|file|max:10240',
            'folder' => 'nullable|string|max:100',
            'collection' => 'nullable|string|max:100',
            'title' => 'nullable|string|max:255',
            'alt_text' => 'nullable|string',
            'caption' => 'nullable|string',
            'is_public' => 'nullable|boolean',
        ]);

        $file = $request->file('file');
        $folder = $validated['folder'] ?? 'general';
        $collection = $validated['collection'] ?? 'default';
        $path = $file->store('cms-media/' . $folder . '/' . $collection, 'public');

        $media = MediaLibrary::create([
            'disk' => 'public',
            'folder' => $folder,
            'collection' => $collection,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'title' => $validated['title'] ?? null,
            'alt_text' => $validated['alt_text'] ?? null,
            'caption' => $validated['caption'] ?? null,
            'is_public' => $validated['is_public'] ?? true,
            'metadata' => [
                'extension' => $file->getClientOriginalExtension(),
                'url' => Storage::disk('public')->url($path),
            ],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Media uploaded successfully.',
            'data' => $media,
        ], 201);
    }

    public function show(MediaLibrary $media): JsonResponse
    {
        if (!$this->isMediaReady()) {
            return response()->json(['success' => false, 'message' => 'Media tables are not migrated yet.'], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $media->load('links'),
        ]);
    }

    public function update(Request $request, MediaLibrary $media): JsonResponse
    {
        if (!$this->isMediaReady()) {
            return response()->json(['success' => false, 'message' => 'Media tables are not migrated yet.'], 400);
        }

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'alt_text' => 'nullable|string',
            'caption' => 'nullable|string',
            'folder' => 'nullable|string|max:100',
            'collection' => 'nullable|string|max:100',
            'is_public' => 'nullable|boolean',
        ]);

        $media->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Media updated successfully.',
            'data' => $media->fresh()->load('links'),
        ]);
    }

    public function folders(): JsonResponse
    {
        if (!$this->isMediaReady()) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $folders = MediaLibrary::query()
            ->select(['folder', 'collection'])
            ->distinct()
            ->orderBy('folder')
            ->orderBy('collection')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $folders,
        ]);
    }

    public function attach(Request $request, MediaLibrary $media): JsonResponse
    {
        if (!$this->isMediaReady()) {
            return response()->json(['success' => false, 'message' => 'Media tables are not migrated yet.'], 400);
        }

        $validated = $request->validate([
            'entity_type' => 'required|in:page,product',
            'entity_id' => 'required|integer|min:1',
            'role' => 'nullable|string|max:50',
        ]);

        $this->assertEntityExists($validated['entity_type'], (int) $validated['entity_id']);

        $link = MediaLibraryLink::firstOrCreate([
            'media_library_id' => $media->id,
            'entity_type' => $validated['entity_type'],
            'entity_id' => (int) $validated['entity_id'],
            'role' => $validated['role'] ?? 'gallery',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Media linked successfully.',
            'data' => $link,
        ]);
    }

    public function detach(Request $request, MediaLibrary $media): JsonResponse
    {
        if (!$this->isMediaReady()) {
            return response()->json(['success' => false, 'message' => 'Media tables are not migrated yet.'], 400);
        }

        $validated = $request->validate([
            'entity_type' => 'required|in:page,product',
            'entity_id' => 'required|integer|min:1',
            'role' => 'nullable|string|max:50',
        ]);

        $query = $media->links()
            ->where('entity_type', $validated['entity_type'])
            ->where('entity_id', (int) $validated['entity_id']);

        if (!empty($validated['role'])) {
            $query->where('role', $validated['role']);
        }

        $query->delete();

        return response()->json([
            'success' => true,
            'message' => 'Media unlinked successfully.',
        ]);
    }

    public function destroy(MediaLibrary $media): JsonResponse
    {
        if (!$this->isMediaReady()) {
            return response()->json(['success' => false, 'message' => 'Media tables are not migrated yet.'], 400);
        }

        Storage::disk($media->disk)->delete($media->file_path);
        $media->delete();

        return response()->json([
            'success' => true,
            'message' => 'Media deleted successfully.',
        ]);
    }

    private function assertEntityExists(string $entityType, int $entityId): void
    {
        $exists = match ($entityType) {
            'page' => \App\Models\Page::query()->whereKey($entityId)->exists(),
            'product' => \App\Models\Product::query()->whereKey($entityId)->exists(),
            default => false,
        };

        if (!$exists) {
            abort(422, 'Selected entity was not found.');
        }
    }

    private function isMediaReady(): bool
    {
        return Schema::hasTable('media_library') && Schema::hasTable('media_library_links');
    }
}
