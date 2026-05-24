<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class StorageController extends Controller
{
    /**
     * Serve a file from the public storage disk.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function serve()
    {
        $path = Route::current()->parameter('path');

        $disk = Storage::disk('public');

        abort_unless($disk->exists($path), 404);

        $fullPath = storage_path('app/public/' . $path);

        return response()->file($fullPath);
    }
}
