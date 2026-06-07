<?php

use App\Http\Controllers\Admin\EmailSettingsController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/settings/email')
    ->name('admin.email-settings.')
    ->middleware(['web', 'auth'])
    ->group(function (): void {
        Route::get('/', [EmailSettingsController::class, 'index'])->name('index');
        Route::put('/', [EmailSettingsController::class, 'update'])->name('update');
        Route::post('/test', [EmailSettingsController::class, 'sendTest'])->name('test');
    });
