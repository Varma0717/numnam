<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogManagementController;
use App\Http\Controllers\Admin\Catalog\CategoryManagementController;
use App\Http\Controllers\Admin\Catalog\ProductManagementController;
use App\Http\Controllers\Admin\Commerce\CouponManagementController;
use App\Http\Controllers\Admin\Commerce\OrderManagementController;
use App\Http\Controllers\Admin\Commerce\ReviewModerationController;
use App\Http\Controllers\Admin\Commerce\ReferralManagementController;
use App\Http\Controllers\Admin\ContactManagementController;
use App\Http\Controllers\Admin\CustomerManagementController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ShippingController;
use App\Http\Controllers\Admin\PageManagementController;
use App\Http\Controllers\Admin\MenuManagementController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\SubscriptionManagementController;
use App\Http\Controllers\Web\CustomerAuthController;
use App\Http\Controllers\Web\Payments\CheckoutPaymentController;
use App\Http\Controllers\Web\PasswordResetController;
use App\Http\Controllers\Web\StorefrontController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [StorefrontController::class, 'home'])->name('store.home');

Route::get('/products', [StorefrontController::class, 'products'])->name('store.products');
Route::get('/products/{product:slug}', [StorefrontController::class, 'product'])->name('store.product.show');
Route::get('/search/suggestions', [StorefrontController::class, 'searchSuggestions'])->name('store.search.suggestions');
Route::get('/categories/{category:slug}', [StorefrontController::class, 'category'])->name('store.category.show');
Route::get('/category/{slug}', function (string $slug) {
    return redirect()->route('store.category.show', ['category' => $slug]);
})->name('store.category.redirect');
Route::get('/aboutus', [StorefrontController::class, 'about'])->name('store.about');
Route::get('/recipes', [StorefrontController::class, 'recipes'])->name('store.recipes');
Route::get('/faq', [StorefrontController::class, 'faq'])->name('store.faq');
Route::get('/refer-friends', [StorefrontController::class, 'referFriends'])->name('store.refer-friends');

Route::get('/pricing', [StorefrontController::class, 'pricing'])->name('store.pricing');
Route::post('/pricing/{plan}/subscribe', [StorefrontController::class, 'subscribe'])
    ->middleware('auth')
    ->name('store.pricing.subscribe');
Route::get('/subscription-checkout', [StorefrontController::class, 'subscriptionCheckout'])
    ->middleware('auth')
    ->name('store.subscription.checkout');
Route::post('/subscription-checkout', [StorefrontController::class, 'placeSubscriptionOrder'])
    ->middleware('auth')
    ->name('store.subscription.checkout.place-order');

Route::get('/blog', [StorefrontController::class, 'blogIndex'])->name('store.blog.index');
Route::get('/blog/{blog:slug}', [StorefrontController::class, 'blogShow'])->name('store.blog.show');

Route::get('/contact', [StorefrontController::class, 'contact'])->name('store.contact');
Route::post('/contact', [StorefrontController::class, 'contactSubmit'])
    ->middleware('throttle:5,1')
    ->name('store.contact.submit');

Route::get('/terms-conditions', [StorefrontController::class, 'legal'])->defaults('slug', 'terms-conditions')->name('store.legal.terms');
Route::get('/privacy-policy', [StorefrontController::class, 'legal'])->defaults('slug', 'privacy-policy')->name('store.legal.privacy');
Route::get('/shipping-policy', [StorefrontController::class, 'legal'])->defaults('slug', 'shipping-policy')->name('store.legal.shipping');
Route::get('/refund-policy', [StorefrontController::class, 'legal'])->defaults('slug', 'refund-policy')->name('store.legal.refund');
Route::get('/cookie-policy', [StorefrontController::class, 'legal'])->defaults('slug', 'cookie-policy')->name('store.legal.cookie');
Route::get('/payment-methods', [StorefrontController::class, 'legal'])->defaults('slug', 'payment-methods')->name('store.legal.payment-methods');

Route::get('/cart', [StorefrontController::class, 'cart'])->name('store.cart');
Route::post('/cart/add/{product}', [StorefrontController::class, 'addToCart'])->name('store.cart.add');
Route::post('/cart/update/{product}', [StorefrontController::class, 'updateCart'])->name('store.cart.update');
Route::delete('/cart/remove/{product}', [StorefrontController::class, 'removeFromCart'])->name('store.cart.remove');

Route::middleware('auth')->group(function () {
    Route::get('/checkout', [StorefrontController::class, 'checkout'])->name('store.checkout');
    Route::post('/checkout', [StorefrontController::class, 'placeOrder'])->name('store.checkout.place-order');
    Route::get('/order-success/{order}', [StorefrontController::class, 'orderSuccess'])->name('store.order.success');
    Route::get('/account', [StorefrontController::class, 'account'])->name('store.account');
    Route::patch('/account/profile', [StorefrontController::class, 'updateProfile'])->name('store.account.update-profile');
    Route::post('/account/password', [StorefrontController::class, 'changePassword'])->name('store.account.change-password');

    // Wishlist
    Route::get('/wishlist', [StorefrontController::class, 'wishlist'])->name('store.wishlist');
    Route::post('/wishlist/toggle/{product}', [StorefrontController::class, 'toggleWishlist'])->name('store.wishlist.toggle');

    // Product reviews
    Route::post('/products/{product:slug}/reviews', [StorefrontController::class, 'storeReview'])->name('store.review.store');

    Route::post('/checkout/pay/{order}', [CheckoutPaymentController::class, 'createSession'])
        ->name('store.checkout.payment.session');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('store.login');
    Route::post('/login', [CustomerAuthController::class, 'login'])->name('store.login.submit');
    Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('store.register');
    Route::post('/register', [CustomerAuthController::class, 'register'])->name('store.register.submit');

    // Password reset
    Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('store.password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('store.password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('store.password.update');
});

Route::post('/logout', [CustomerAuthController::class, 'logout'])
    ->middleware('auth')
    ->name('store.logout');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])
        ->middleware('throttle:8,1')
        ->name('login.submit');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::match(['get', 'post'], '/logout', [AdminAuthController::class, 'logout'])->name('logout');

        Route::get('/orders', [OrderManagementController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderManagementController::class, 'show'])->name('orders.show');
        Route::put('/orders/{order}', [OrderManagementController::class, 'update'])->name('orders.update');
        Route::post('/orders/{order}/timeline-note', [OrderManagementController::class, 'addTimelineNote'])
            ->name('orders.timeline-note');

        Route::resource('coupons', CouponManagementController::class)->except('show');
        Route::resource('products', ProductManagementController::class)->except('show');
        Route::resource('categories', CategoryManagementController::class)->except('show');
        Route::resource('blogs', BlogManagementController::class)->except('show');
        Route::resource('blog-categories', BlogCategoryController::class)->except('show');

        Route::get('/referrals', [ReferralManagementController::class, 'index'])->name('referrals.index');
        Route::post('/referrals/adjustments', [ReferralManagementController::class, 'storeAdjustment'])
            ->name('referrals.adjustment.store');
        Route::delete('/referrals/rewards/{reward}', [ReferralManagementController::class, 'destroyReward'])
            ->name('referrals.reward.destroy');

        Route::get('/media', function () {
            return view('admin.media.index');
        })->name('media');

        // Media JSON endpoints (session-authed, used by media picker modal)
        Route::get('/media/json', [MediaController::class, 'index'])->name('media.json');
        Route::get('/media/json/folders', [MediaController::class, 'folders'])->name('media.json.folders');
        Route::post('/media/json/upload', [MediaController::class, 'store'])->name('media.json.upload');
        Route::delete('/media/json/{media}', [MediaController::class, 'destroy'])->name('media.json.destroy');

        // Customers
        Route::get('/customers', [CustomerManagementController::class, 'index'])->name('customers.index');
        Route::get('/customers/{customer}', [CustomerManagementController::class, 'show'])->name('customers.show');

        // Contacts
        Route::get('/contacts', [ContactManagementController::class, 'index'])->name('contacts.index');
        Route::get('/contacts/{contact}', [ContactManagementController::class, 'show'])->name('contacts.show');
        Route::delete('/contacts/{contact}', [ContactManagementController::class, 'destroy'])->name('contacts.destroy');

        // Subscriptions
        Route::get('/subscriptions', [SubscriptionManagementController::class, 'index'])->name('subscriptions.index');
        Route::get('/subscriptions/{subscription}', [SubscriptionManagementController::class, 'show'])->name('subscriptions.show');
        Route::put('/subscriptions/{subscription}', [SubscriptionManagementController::class, 'update'])->name('subscriptions.update');

        // Reviews
        Route::get('/reviews', [ReviewModerationController::class, 'index'])->name('reviews.index');
        Route::patch('/reviews/{review}/approve', [ReviewModerationController::class, 'approve'])->name('reviews.approve');
        Route::patch('/reviews/{review}/reject', [ReviewModerationController::class, 'reject'])->name('reviews.reject');
        Route::delete('/reviews/{review}', [ReviewModerationController::class, 'destroy'])->name('reviews.destroy');

        // Settings
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::get('/settings/create', [SettingsController::class, 'create'])->name('settings.create');
        Route::post('/settings/store', [SettingsController::class, 'store'])->name('settings.store');
        Route::delete('/settings/{setting}', [SettingsController::class, 'destroy'])->name('settings.destroy');

        // Shipping Zones (settings sub-resource)
        Route::resource('shipping/zones', ShippingController::class)
            ->parameters(['zones' => 'zone'])
            ->names([
                'index'   => 'shipping.zones.index',
                'create'  => 'shipping.zones.create',
                'store'   => 'shipping.zones.store',
                'edit'    => 'shipping.zones.edit',
                'update'  => 'shipping.zones.update',
                'destroy' => 'shipping.zones.destroy',
            ])
            ->except('show');

        // Pages
        Route::resource('pages', PageManagementController::class)->except('show');

        // Menus
        Route::resource('menus', MenuManagementController::class)->except('show');

        // Reports
        Route::get('/reports/sales', [ReportsController::class, 'sales'])->name('reports.sales');
        Route::get('/reports/customers', [ReportsController::class, 'customers'])->name('reports.customers');
        Route::get('/reports/stock', [ReportsController::class, 'stock'])->name('reports.stock');

        // Bulk actions
        Route::post('/products/bulk', [ProductManagementController::class, 'bulk'])->name('products.bulk');
        Route::post('/orders/bulk', [OrderManagementController::class, 'bulk'])->name('orders.bulk');
        Route::post('/blogs/bulk', [BlogManagementController::class, 'bulk'])->name('blogs.bulk');
        Route::post('/coupons/bulk', [CouponManagementController::class, 'bulk'])->name('coupons.bulk');
    });
});

Route::get('/{slug}', [StorefrontController::class, 'showPage'])->name('store.page.show');
