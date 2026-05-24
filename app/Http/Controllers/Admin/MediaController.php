<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaLibrary;
use App\Services\ResponsiveImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function __construct(private ResponsiveImageService $responsiveImageService) {}

    public function index(Request $request): JsonResponse
    {
        if (!Schema::hasTable('media_library')) {
            return response()->json(['data' => [], 'meta' => ['total' => 0]]);
        }

        $query = MediaLibrary::query();

        // Only load uploader relationship if column exists
        if (Schema::hasColumn('media_library', 'uploaded_by')) {
            $query->with('uploader:id,name');
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('file_name', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('alt_text', 'like', "%{$search}%");
            });
        }

        // Filter by folder
        if ($request->filled('folder')) {
            $query->where('folder', $request->string('folder')->toString());
        }

        // Filter by collection
        if ($request->filled('collection')) {
            $query->where('collection', $request->string('collection')->toString());
        }

        // Filter by mime type
        if ($request->filled('type')) {
            $type = $request->string('type')->toString();
            $query->where('mime_type', 'like', $type . '%');
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date('date_to'));
        }

        // Sorting
        $sortBy = $request->string('sort_by', 'created_at')->toString();
        $sortOrder = $request->string('sort_order', 'desc')->toString();
        $query->orderBy($sortBy, $sortOrder);

        $perPage = min(max((int) $request->input('per_page', 60), 1), 100);
        $media = $query->paginate($perPage);

        // Add URLs to each media item
        $mediaItems = collect($media->items())->map(function ($item) {
            $disk = Storage::disk($item->disk);
            $fileUrl = $disk->exists($item->file_path)
                ? url('storage/' . str_replace('public/', '', $item->file_path))
                : '';

            return [
                'id' => $item->id,
                'file_name' => $item->file_name,
                'title' => $item->title,
                'alt_text' => $item->alt_text,
                'caption' => $item->caption,
                'folder' => $item->folder,
                'collection' => $item->collection,
                'mime_type' => $item->mime_type,
                'size' => $item->size,
                'size_formatted' => $this->formatBytes($item->size),
                'url' => $fileUrl,
                'responsive_urls' => $item->metadata['responsive'] ?? [],
                'path' => $item->file_path,
                'dimensions' => $item->metadata['dimensions'] ?? null,
                'uploaded_by' => $item->uploader?->name ?? 'Unknown',
                'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $item->updated_at->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json([
            'data' => $mediaItems,
            'meta' => [
                'current_page' => $media->currentPage(),
                'from' => $media->firstItem(),
                'last_page' => $media->lastPage(),
                'per_page' => $media->perPage(),
                'to' => $media->lastItem(),
                'total' => $media->total(),
            ],
        ]);
    }

    public function folders(): JsonResponse
    {
        if (!Schema::hasTable('media_library')) {
            return response()->json(['data' => []]);
        }

        $folders = MediaLibrary::query()
            ->select('folder')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('folder')
            ->orderBy('folder')
            ->get()
            ->map(fn($item) => [
                'name' => $item->folder,
                'count' => $item->count,
            ]);

        return response()->json(['data' => $folders]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|max:10240|mimes:jpg,jpeg,png,gif,webp,svg,pdf,doc,docx',
            'folder' => 'nullable|string|max:100',
            'collection' => 'nullable|string|max:100',
            'title' => 'nullable|string|max:255',
            'alt_text' => 'nullable|string|max:255',
            'caption' => 'nullable|string|max:500',
        ]);

        $file = $request->file('file');
        $folder = $request->input('folder', 'general');
        $collection = $request->input('collection', 'uploads');
        $path = $file->store('cms-media/' . $folder, 'public');

        // Get image dimensions if it's an image
        $dimensions = null;
        $responsive = [];
        if (str_starts_with($file->getClientMimeType(), 'image/')) {
            try {
                $fullPath = storage_path('app/public/' . $path);
                if (file_exists($fullPath)) {
                    [$width, $height] = getimagesize($fullPath);
                    $dimensions = ['width' => $width, 'height' => $height];
                }

                $responsive = $this->responsiveImageService->generateForPublicDisk($path, $file->getClientMimeType());
            } catch (\Exception $e) {
                // Ignore dimension extraction errors
            }
        }

        // Prepare data for creation
        $mediaData = [
            'disk' => 'public',
            'folder' => $folder,
            'collection' => $collection,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'title' => $request->input('title', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)),
            'alt_text' => $request->input('alt_text'),
            'caption' => $request->input('caption'),
            'uploaded_by' => Auth::id(),
            'metadata' => [
                'extension' => $file->getClientOriginalExtension(),
                'dimensions' => $dimensions,
                'responsive' => $responsive,
            ],
        ];

        // Only add is_public if the column exists
        if (Schema::hasColumn('media_library', 'is_public')) {
            $mediaData['is_public'] = true;
        }

        $media = MediaLibrary::create($mediaData);

        $fileUrl = url('storage/' . str_replace('public/', '', $media->file_path));

        return response()->json([
            'success' => true,
            'message' => 'File uploaded successfully',
            'data' => [
                'id' => $media->id,
                'file_name' => $media->file_name,
                'title' => $media->title,
                'url' => $fileUrl,
                'responsive_urls' => $responsive,
                'size' => $media->size,
                'size_formatted' => $this->formatBytes($media->size),
                'mime_type' => $media->mime_type,
                'dimensions' => $dimensions,
            ],
        ], 201);
    }

    public function update(Request $request, MediaLibrary $media): JsonResponse
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'alt_text' => 'nullable|string|max:255',
            'caption' => 'nullable|string|max:500',
            'folder' => 'nullable|string|max:100',
            'collection' => 'nullable|string|max:100',
        ]);

        $media->update($request->only(['title', 'alt_text', 'caption', 'folder', 'collection']));

        return response()->json([
            'success' => true,
            'message' => 'Media updated successfully',
            'data' => $media,
        ]);
    }

    public function bulkUpdate(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:media_library,id',
            'folder' => 'nullable|string|max:100',
            'collection' => 'nullable|string|max:100',
        ]);

        $updated = MediaLibrary::whereIn('id', $request->ids)
            ->update($request->only(['folder', 'collection']));

        return response()->json([
            'success' => true,
            'message' => "{$updated} media items updated successfully",
        ]);
    }

    public function destroy(MediaLibrary $media): JsonResponse
    {
        // Delete physical file
        if (Storage::disk($media->disk)->exists($media->file_path)) {
            Storage::disk($media->disk)->delete($media->file_path);
        }

        // Delete database record
        $media->delete();

        return response()->json([
            'success' => true,
            'message' => 'Media deleted successfully',
        ]);
    }

    public function bulkDelete(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:media_library,id',
        ]);

        $mediaItems = MediaLibrary::whereIn('id', $request->ids)->get();

        foreach ($mediaItems as $media) {
            if (Storage::disk($media->disk)->exists($media->file_path)) {
                Storage::disk($media->disk)->delete($media->file_path);
            }
            $media->delete();
        }

        return response()->json([
            'success' => true,
            'message' => count($mediaItems) . ' media items deleted successfully',
        ]);
    }

    public function show(MediaLibrary $media): JsonResponse
    {
        $fileUrl = url('storage/' . str_replace('public/', '', $media->file_path));

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $media->id,
                'file_name' => $media->file_name,
                'title' => $media->title,
                'alt_text' => $media->alt_text,
                'caption' => $media->caption,
                'folder' => $media->folder,
                'collection' => $media->collection,
                'mime_type' => $media->mime_type,
                'size' => $media->size,
                'size_formatted' => $this->formatBytes($media->size),
                'url' => $fileUrl,
                'responsive_urls' => $media->metadata['responsive'] ?? [],
                'path' => $media->file_path,
                'dimensions' => $media->metadata['dimensions'] ?? null,
                'uploaded_by' => $media->uploader?->name ?? 'Unknown',
                'created_at' => $media->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $media->updated_at->format('Y-m-d H:i:s'),
            ],
        ]);
    }

    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
