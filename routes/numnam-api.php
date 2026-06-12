<?php

use App\Http\Controllers\Api\V1\NumNamBabyController;
use App\Http\Controllers\Api\V1\NumNamCommunityController;
use App\Http\Controllers\Api\V1\NumNamFeedLogController;
use App\Http\Controllers\Api\V1\NumNamRecipeController;
use App\Http\Controllers\Api\V1\NumNamShopController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| NumNam Weaning App API Routes
|--------------------------------------------------------------------------
|
| These routes provide session-based authentication (auth:web)
| for the NumNam baby weaning tracker application.
|
| Base URL: https://www.numnam.com/api/v1/numnam/
|
*/

Route::prefix('v1/numnam')->middleware('auth:web')->group(function () {
    // Baby Profile
    Route::get('baby/profile', [NumNamBabyController::class, 'profile']);
    Route::post('baby/profile', [NumNamBabyController::class, 'updateProfile']);
    Route::get('baby/dashboard', [NumNamBabyController::class, 'dashboardSummary']);

    // Feed Logs
    Route::post('logs', [NumNamFeedLogController::class, 'store']);
    Route::get('logs/today', [NumNamFeedLogController::class, 'todayLogs']);
    Route::delete('logs/{feedLog}', [NumNamFeedLogController::class, 'destroy']);
    Route::delete('logs/today/clear', [NumNamFeedLogController::class, 'clearToday']);

    // Recipes
    Route::get('recipes', [NumNamRecipeController::class, 'index']);
    Route::get('recipes/{recipe}', [NumNamRecipeController::class, 'show']);
    Route::post('recipes/{recipe}/like', [NumNamRecipeController::class, 'toggleLike']);
    Route::get('recipes/type/{foodType}', [NumNamRecipeController::class, 'byType']);

    // Shop
    Route::get('shop/products', [NumNamShopController::class, 'index']);
    Route::get('shop/products/{product}', [NumNamShopController::class, 'show']);
    Route::get('shop/category/{category}', [NumNamShopController::class, 'byCategory']);
    Route::get('shop/featured', [NumNamShopController::class, 'featured']);

    // Community Chat
    Route::get('community/rooms', [NumNamCommunityController::class, 'rooms']);
    Route::get('community/rooms/{room}/messages', [NumNamCommunityController::class, 'roomMessages']);
    Route::post('community/rooms/{room}/messages', [NumNamCommunityController::class, 'sendMessage']);
    Route::post('community/messages/{message}/like', [NumNamCommunityController::class, 'likeMessage']);
    Route::get('community/rooms/{room}/search', [NumNamCommunityController::class, 'searchMessages']);
});
