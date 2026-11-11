<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        Paginator::defaultView('vendor.pagination.custom');
        Paginator::defaultSimpleView('vendor.pagination.simple-custom');

        View::composer(['frontend.*', 'layouts.app'], function ($view) {
            $wishlistIds = [];
            $navCategories = [];
            try {
                if (auth('web')->check()) {
                    $wishlistIds = \App\Models\Wishlist::where('user_id', auth('web')->id())->pluck('product_id')->toArray();
                } else {
                    $wishlistIds = session()->get('wishlist', []);
                }
            } catch (\Throwable $e) {
                $wishlistIds = session()->get('wishlist', []);
            }

            try {
                $navCategories = \App\Models\Category::where('is_active', true)
                    ->where('show_in_menu', true)
                    ->whereNull('parent_id')
                    ->with(['children' => function($q) {
                        $q->where('is_active', true)->where('show_in_menu', true)->orderBy('sort_order')->orderBy('name');
                    }])
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->get();
            } catch (\Throwable $e) {
                $navCategories = collect();
            }

            $view->with('wishlistIds', $wishlistIds ?: []);
            $view->with('navCategories', $navCategories ?: collect());
        });
    }
}
