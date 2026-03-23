<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
            $megaCategories = Category::query()
                ->where('is_active', true)
                ->with(['products' => function ($query) {
                    $query->where('is_active', true)
                        ->select('id', 'category_id', 'name', 'slug', 'sale_price', 'price')
                        ->latest('id')
                        ->take(4);
                }])
                ->orderBy('name')
                ->take(8)
                ->get(['id', 'name', 'slug']);

            $view->with('megaCategories', $megaCategories);
        });
    }
}
