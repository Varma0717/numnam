<?php

namespace App\Providers;

use App\Models\CartItem;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('store.*', function ($view) {
            $user = auth()->user();

            if ($user) {
                $cartItemCount = (int) CartItem::query()
                    ->where('user_id', $user->id)
                    ->sum('qty');
            } else {
                $cartItemCount = collect(session('cart', []))
                    ->sum(function ($line) {
                        return (int) ($line['qty'] ?? 0);
                    });
            }

            $view->with('cartItemCount', $cartItemCount);
        });
    }
}
