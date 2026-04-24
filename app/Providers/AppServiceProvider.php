<?php

namespace App\Providers;

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
            $cartItemCount = collect(session('cart', []))
                ->sum(function ($line) {
                    return (int) ($line['qty'] ?? 0);
                });

            $view->with('cartItemCount', $cartItemCount);
        });
    }
}
