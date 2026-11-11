<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // If coming soon is enabled and not bypassed
        $comingSoon = Setting::get('coming_soon_mode', '0');
        if ($comingSoon == '1' && !$request->has('preview')) {
            return view('coming_soon');
        }

        $heroBanners = Banner::where('is_active', true)
            ->where(function($q) {
                $q->where('position', 'hero')->orWhereNull('position');
            })
            ->orderBy('sort_order')
            ->get();

        if ($heroBanners->isEmpty()) {
            $heroBanners = Banner::where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        }

        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // 1. New Arrival Products (Admin flag: is_new_arrival)
        $newArrivalProducts = Product::where('is_active', true)
            ->where('is_new_arrival', true)
            ->with(['variants', 'category'])
            ->latest()
            ->take(10)
            ->get();

        if ($newArrivalProducts->isEmpty()) {
            $newArrivalProducts = Product::where('is_active', true)
                ->with(['variants', 'category'])
                ->latest()
                ->take(5)
                ->get();
        }

        // 2. Top Selling Products (Admin flag: is_top_selling)
        $topSellingProducts = Product::where('is_active', true)
            ->where('is_top_selling', true)
            ->with(['variants', 'category'])
            ->latest()
            ->take(10)
            ->get();

        if ($topSellingProducts->isEmpty()) {
            $topSellingProducts = Product::where('is_active', true)
                ->where('is_featured', true)
                ->with(['variants', 'category'])
                ->latest()
                ->take(5)
                ->get();

            if ($topSellingProducts->isEmpty()) {
                $topSellingProducts = Product::where('is_active', true)
                    ->with(['variants', 'category'])
                    ->inRandomOrder()
                    ->take(5)
                    ->get();
            }
        }

        // Backwards compatibility for any script relying on trendingProducts
        $trendingProducts = $topSellingProducts;

        // All Products for main 5-col grid
        $allProducts = Product::where('is_active', true)
            ->with(['variants', 'category'])
            ->latest()
            ->take(20)
            ->get();

        return view('frontend.home', compact(
            'heroBanners',
            'categories',
            'newArrivalProducts',
            'topSellingProducts',
            'trendingProducts',
            'allProducts'
        ));
    }

    public function showPage($slug)
    {
        $page = \App\Models\Page::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view('frontend.page', compact('page'));
    }
}
