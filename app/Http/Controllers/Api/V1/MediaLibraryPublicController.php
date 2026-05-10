<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\MediaLibrary;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class MediaLibraryPublicController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        if (!Schema::hasTable('media_library') || !Schema::hasTable('media_library_links')) {
            return response()->json([
                'success' => true,
                'data' => [],
            ]);
        }

        $media = MediaLibrary::query()
            ->where('is_public', true)
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
            ->paginate((int) $request->input('per_page', 30));

        return response()->json([
            'success' => true,
            'data' => $media,
        ]);
    }

    public function show(MediaLibrary $media): JsonResponse
    {
        if (!Schema::hasTable('media_library') || !Schema::hasTable('media_library_links')) {
            return response()->json([
                'success' => false,
                'message' => 'Media tables are not migrated yet.',
            ], 400);
        }

        abort_unless($media->is_public, 404);

        return response()->json([
            'success' => true,
            'data' => $media->load('links'),
        ]);
    }
}
