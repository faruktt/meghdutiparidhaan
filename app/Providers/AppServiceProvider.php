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

            $siteLogo = \App\Models\Setting::get('site_logo') ?: \App\Models\Setting::get('logo');
            $siteFavicon = \App\Models\Setting::get('site_favicon') ?: \App\Models\Setting::get('favicon');
            $siteLogoUrl = null;
            if ($siteLogo) {
                if (\Illuminate\Support\Str::startsWith($siteLogo, ['http://', 'https://'])) {
                    $siteLogoUrl = $siteLogo;
                } elseif (\Illuminate\Support\Str::startsWith($siteLogo, 'images/')) {
                    $siteLogoUrl = asset($siteLogo);
                } else {
                    $siteLogoUrl = asset('images/' . $siteLogo);
                }
            } elseif ($siteFavicon) {
                if (\Illuminate\Support\Str::startsWith($siteFavicon, ['http://', 'https://'])) {
                    $siteLogoUrl = $siteFavicon;
                } elseif (\Illuminate\Support\Str::startsWith($siteFavicon, 'images/')) {
                    $siteLogoUrl = asset($siteFavicon);
                } else {
                    $siteLogoUrl = asset('images/' . $siteFavicon);
                }
            }

            $siteName = \App\Models\Setting::get('site_name', 'Meghduti Paridhaan');

            $view->with('wishlistIds', $wishlistIds ?: []);
            $view->with('navCategories', $navCategories ?: collect());
            $view->with('siteLogoUrl', $siteLogoUrl);
            $view->with('logoUrl', $siteLogoUrl);
            $view->with('siteName', $siteName);
        });
    }
}
