<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminPagesController;
use App\Http\Controllers\Admin\Catalog\ProductManagementController;
use App\Http\Controllers\Admin\Commerce\CouponManagementController;
use App\Http\Controllers\Admin\Commerce\OrderManagementController;
use App\Http\Controllers\Admin\Commerce\ReferralManagementController;
use App\Http\Controllers\Web\CustomerAuthController;
use App\Http\Controllers\Web\Payments\CheckoutPaymentController;
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
Route::get('/category/{slug}', function (string $slug) {
    return redirect()->route('store.products', ['category' => $slug]);
})->name('store.category.redirect');
Route::get('/aboutus', [StorefrontController::class, 'about'])->name('store.about');
Route::get('/general-5', [StorefrontController::class, 'recipes'])->name('store.recipes');
Route::get('/faq', [StorefrontController::class, 'faq'])->name('store.faq');
Route::get('/refer-friends', [StorefrontController::class, 'referFriends'])->name('store.refer-friends');

Route::get('/pricing', [StorefrontController::class, 'pricing'])->name('store.pricing');
Route::post('/pricing/{plan}/subscribe', [StorefrontController::class, 'subscribe'])
    ->middleware('auth')
    ->name('store.pricing.subscribe');

Route::get('/blog', [StorefrontController::class, 'blogIndex'])->name('store.blog.index');
Route::get('/blog/{blog:slug}', [StorefrontController::class, 'blogShow'])->name('store.blog.show');

Route::get('/contact', [StorefrontController::class, 'contact'])->name('store.contact');
Route::post('/contact', [StorefrontController::class, 'contactSubmit'])->name('store.contact.submit');

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

    Route::post('/checkout/pay/{order}', [CheckoutPaymentController::class, 'createSession'])
        ->name('store.checkout.payment.session');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('store.login');
    Route::post('/login', [CustomerAuthController::class, 'login'])->name('store.login.submit');
    Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('store.register');
    Route::post('/register', [CustomerAuthController::class, 'register'])->name('store.register.submit');
});

Route::post('/logout', [CustomerAuthController::class, 'logout'])
    ->middleware('auth')
    ->name('store.logout');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])
            ->middleware('throttle:8,1')
            ->name('login.submit');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

        Route::get('/orders', [OrderManagementController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderManagementController::class, 'show'])->name('orders.show');
        Route::put('/orders/{order}', [OrderManagementController::class, 'update'])->name('orders.update');
        Route::post('/orders/{order}/timeline-note', [OrderManagementController::class, 'addTimelineNote'])
            ->name('orders.timeline-note');

        Route::resource('coupons', CouponManagementController::class)->except('show');
        Route::resource('products', ProductManagementController::class)->except('show');

        Route::get('/referrals', [ReferralManagementController::class, 'index'])->name('referrals.index');
        Route::post('/referrals/adjustments', [ReferralManagementController::class, 'storeAdjustment'])
            ->name('referrals.adjustment.store');
        Route::delete('/referrals/rewards/{reward}', [ReferralManagementController::class, 'destroyReward'])
            ->name('referrals.reward.destroy');

        Route::get('/media', function () {
            return view('admin.media.index');
        })->name('media');

        Route::get('/customers', [AdminPagesController::class, 'customers'])->name('customers.index');
        Route::get('/categories', [AdminPagesController::class, 'categories'])->name('categories.index');
        Route::get('/blogs', [AdminPagesController::class, 'blogs'])->name('blogs.index');
        Route::get('/contacts', [AdminPagesController::class, 'contacts'])->name('contacts.index');
        Route::get('/subscriptions', [AdminPagesController::class, 'subscriptions'])->name('subscriptions.index');
        Route::get('/settings', [AdminPagesController::class, 'settings'])->name('settings.index');
    });
});
