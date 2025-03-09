<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $cart = Session::get('cart', []);
            $totalQuantity = array_sum(array_column($cart, 'quantity'));

            $view->with('totalQuantity', auth()->check() ? $totalQuantity : 0);
        });
    }
}
