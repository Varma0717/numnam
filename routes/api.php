<?php

use App\Http\Controllers\Api\Admin\AdminProductController;
use App\Http\Controllers\Api\Admin\BlogManagerController;
use App\Http\Controllers\Api\Admin\ContactMessageController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\HomepageSectionController;
use App\Http\Controllers\Api\Admin\MediaLibraryController;
use App\Http\Controllers\Api\Admin\MenuManagerController;
use App\Http\Controllers\Api\Admin\PageController;
use App\Http\Controllers\Api\Admin\PricingPlanController;
use App\Http\Controllers\Api\Admin\SiteSettingController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ContactController;
use App\Http\Controllers\Api\V1\ContactFormController;
use App\Http\Controllers\Api\V1\FrontendController;
use App\Http\Controllers\Api\V1\HomepageController;
use App\Http\Controllers\Api\V1\MediaLibraryPublicController;
use App\Http\Controllers\Api\V1\Mobile\MobileAuthController;
use App\Http\Controllers\Api\V1\Mobile\MobileContentController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\Payments\PaymentWebhookController;
use App\Http\Controllers\Api\V1\PricingPlanModuleController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\ProductModuleController;
use App\Http\Controllers\Api\V1\SubscriptionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| NumNam API v1 Routes
| Base URL: http://localhost/numnam-api/public/api/v1
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // ── Mobile API (JWT, Flutter-ready) ────────────────────────────────
    Route::prefix('mobile')->group(function () {
        Route::prefix('auth')->group(function () {
            Route::post('register', [MobileAuthController::class, 'register']);
            Route::post('login', [MobileAuthController::class, 'login']);

            Route::middleware('jwt.auth')->group(function () {
                Route::get('me', [MobileAuthController::class, 'me']);
                Route::post('refresh', [MobileAuthController::class, 'refresh']);
            });
        });

        Route::middleware('jwt.auth')->group(function () {
            Route::get('homepage', [MobileContentController::class, 'homepage']);

            Route::get('products', [MobileContentController::class, 'products']);
            Route::get('products/{slug}', [MobileContentController::class, 'productShow']);

            Route::get('pricing-plans', [MobileContentController::class, 'pricingPlans']);
            Route::get('pricing-plans/{slug}', [MobileContentController::class, 'pricingPlanShow']);

            Route::get('blogs', [MobileContentController::class, 'blogs']);
            Route::get('blogs/{slug}', [MobileContentController::class, 'blogShow']);

            Route::post('contact-forms', [MobileContentController::class, 'submitContactForm']);

            Route::get('menus', [MobileContentController::class, 'menus']);
        });
    });

    // ── Admin CMS (AJAX JSON API) ───────────────────────────────────────
    Route::prefix('admin')->middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index']);

        Route::apiResource('pages', PageController::class);

        Route::get('homepage-sections', [HomepageSectionController::class, 'index']);
        Route::get('homepage-sections/schema', [HomepageSectionController::class, 'schema']);
        Route::post('homepage-sections', [HomepageSectionController::class, 'store']);
        Route::post('homepage-sections/upsert', [HomepageSectionController::class, 'upsert']);
        Route::put('homepage-sections/{section}', [HomepageSectionController::class, 'update']);
        Route::delete('homepage-sections/{section}', [HomepageSectionController::class, 'destroy']);

        Route::get('products/categories', [AdminProductController::class, 'categories']);
        Route::apiResource('products', AdminProductController::class);

        Route::apiResource('pricing-plans', PricingPlanController::class);

        Route::get('blogs/categories', [BlogManagerController::class, 'categories']);
        Route::apiResource('blogs', BlogManagerController::class);

        Route::get('media', [MediaLibraryController::class, 'index']);
        Route::get('media/folders', [MediaLibraryController::class, 'folders']);
        Route::post('media', [MediaLibraryController::class, 'store']);
        Route::get('media/{media}', [MediaLibraryController::class, 'show']);
        Route::put('media/{media}', [MediaLibraryController::class, 'update']);
        Route::post('media/{media}/attach', [MediaLibraryController::class, 'attach']);
        Route::delete('media/{media}/detach', [MediaLibraryController::class, 'detach']);
        Route::delete('media/{media}', [MediaLibraryController::class, 'destroy']);

        Route::get('menus', [MenuManagerController::class, 'index']);
        Route::post('menus', [MenuManagerController::class, 'store']);
        Route::put('menus/{menu}', [MenuManagerController::class, 'update']);
        Route::delete('menus/{menu}', [MenuManagerController::class, 'destroy']);
        Route::post('menus/{menu}/items', [MenuManagerController::class, 'storeItem']);
        Route::put('menu-items/{item}', [MenuManagerController::class, 'updateItem']);
        Route::delete('menu-items/{item}', [MenuManagerController::class, 'destroyItem']);

        Route::get('contact-messages', [ContactMessageController::class, 'index']);
        Route::get('contact-messages/{message}', [ContactMessageController::class, 'show']);
        Route::patch('contact-messages/{message}/status', [ContactMessageController::class, 'updateStatus']);
        Route::delete('contact-messages/{message}', [ContactMessageController::class, 'destroy']);

        // Website contact form leads
        Route::get('leads/contact-messages', [ContactFormController::class, 'adminIndex']);
        Route::get('leads/contact-messages/{message}', [ContactFormController::class, 'adminShow']);

        Route::get('site-settings', [SiteSettingController::class, 'index']);
        Route::post('site-settings', [SiteSettingController::class, 'upsert']);
    });

    // ── Auth ────────────────────────────────────────────────────────────
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login',    [AuthController::class, 'login']);

        // Requires valid Sanctum token
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::get('me',     [AuthController::class, 'me']);
        });
    });

    // ── Public: Products ─────────────────────────────────────────────────
    // GET  /api/v1/products              list (filterable by type, age_group, category, featured)
    // GET  /api/v1/products/{slug}       single product detail
    Route::get('products',       [ProductController::class, 'index']);
    Route::get('products/{slug}', [ProductController::class, 'show']);

    // ── Public: Categories ───────────────────────────────────────────────
    Route::get('categories',        [CategoryController::class, 'index']);
    Route::get('categories/{slug}', [CategoryController::class, 'show']);

    // ── Public: Product Module ───────────────────────────────────────────
    Route::get('module/products', [ProductModuleController::class, 'index']);
    Route::get('module/products/{product}', [ProductModuleController::class, 'show']);

    // ── Public: Pricing Plan Module ──────────────────────────────────────
    Route::get('module/pricing-plans', [PricingPlanModuleController::class, 'index']);
    Route::get('module/pricing-plans/{pricingPlan}', [PricingPlanModuleController::class, 'show']);

    // ── Public: Homepage Sections ────────────────────────────────────────
    Route::get('homepage/sections', [HomepageController::class, 'sections']);

    // ── Frontend Dynamic Rendering APIs (no Blade required) ─────────────
    Route::prefix('frontend')->group(function () {
        Route::get('pages', [FrontendController::class, 'pages']);
        Route::get('pages/{slug}', [FrontendController::class, 'page']);
        Route::get('homepage-sections', [FrontendController::class, 'homepageSections']);
        Route::get('menus', [FrontendController::class, 'menus']);
        Route::get('products', [FrontendController::class, 'products']);
        Route::get('render/{slug?}', [FrontendController::class, 'render']);
    });

    // ── Public: Media Retrieval ──────────────────────────────────────────
    Route::get('media', [MediaLibraryPublicController::class, 'index']);
    Route::get('media/{media}', [MediaLibraryPublicController::class, 'show']);

    // ── Public: Subscription Plans ───────────────────────────────────────
    Route::get('subscriptions/plans', [SubscriptionController::class, 'plans']);

    // ── Public: Contact Form ─────────────────────────────────────────────
    Route::post('contact', [ContactController::class, 'store']);
    Route::post('contact-form/submit', [ContactFormController::class, 'submit']);

    // ── Protected Routes (requires Sanctum token) ────────────────────────
    Route::middleware('auth:sanctum')->group(function () {

        // Orders
        Route::get('orders',             [OrderController::class, 'index']);
        Route::post('orders',            [OrderController::class, 'store']);
        Route::get('orders/{number}',    [OrderController::class, 'show']);
        Route::patch('orders/{order}/cancel', [OrderController::class, 'cancel']);

        // Subscriptions
        Route::get('subscriptions',                          [SubscriptionController::class, 'index']);
        Route::post('subscriptions',                         [SubscriptionController::class, 'store']);
        Route::patch('subscriptions/{subscription}/pause',   [SubscriptionController::class, 'pause']);
        Route::patch('subscriptions/{subscription}/resume',  [SubscriptionController::class, 'resume']);
        Route::delete('subscriptions/{subscription}',        [SubscriptionController::class, 'destroy']);

        // Admin-only: product / category / contact management
        // In production, guard these with a role middleware (e.g. ->middleware('role:admin'))
        Route::post('products',            [ProductController::class, 'store']);
        Route::put('products/{product}',   [ProductController::class, 'update']);
        Route::delete('products/{product}',[ProductController::class, 'destroy']);

        Route::post('categories',                [CategoryController::class, 'store']);
        Route::put('categories/{category}',      [CategoryController::class, 'update']);
        Route::delete('categories/{category}',   [CategoryController::class, 'destroy']);

        // Contact inbox (admin)
        Route::get('contact',                    [ContactController::class, 'index']);
        Route::patch('contact/{contact}/read',   [ContactController::class, 'markRead']);
    });
});

// Health-check endpoint
Route::get('health', fn () => response()->json([
    'status'  => 'ok',
    'service' => 'NumNam API',
    'version' => '1.0.0',
]));

Route::prefix('v1/payments/webhooks')->group(function () {
    Route::post('razorpay', [PaymentWebhookController::class, 'razorpay']);
    Route::post('stripe', [PaymentWebhookController::class, 'stripe']);
});
