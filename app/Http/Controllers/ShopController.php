<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\Size;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('is_active', true)
            ->with(['category', 'variants.color', 'variants.size']);

        // Search
        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('sku', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $catSlug = $request->category;
            $category = Category::where('slug', $catSlug)->first();
            if ($category) {
                $categoryIds = [$category->id];
                if ($category->children()->count() > 0) {
                    $categoryIds = array_merge($categoryIds, $category->children->pluck('id')->toArray());
                }
                $query->where(function($q) use ($categoryIds) {
                    $q->whereIn('category_id', $categoryIds)
                      ->orWhereIn('subcategory_id', $categoryIds);
                });
            }
        }

        // Subcategory filter
        if ($request->filled('subcategory')) {
            $subSlug = $request->subcategory;
            $subCategory = Category::where('slug', $subSlug)->first();
            if ($subCategory) {
                $query->where(function($q) use ($subCategory) {
                    $q->where('subcategory_id', $subCategory->id)
                      ->orWhere('category_id', $subCategory->id);
                });
            }
        }

        // Section filter (new-arrival, top-selling)
        if ($request->filled('section')) {
            if ($request->section === 'new-arrival' || $request->section === 'new_arrival') {
                $query->where('is_new_arrival', true);
            } elseif ($request->section === 'top-selling' || $request->section === 'top_selling') {
                $query->where('is_top_selling', true);
            }
        }

        // Color filter
        if ($request->filled('color')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('color_id', $request->color);
            });
        }

        // Size filter
        if ($request->filled('size')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('size_id', $request->size);
            });
        }

        // Price range
        if ($request->filled('min_price')) {
            $query->where('regular_price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('regular_price', '<=', $request->max_price);
        }

        // Sort
        switch ($request->get('sort', 'latest')) {
            case 'price_low':
                $query->orderBy('regular_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('regular_price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'featured':
                $query->where('is_featured', true)->latest();
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(16)->withQueryString();
        $categories = Category::where('is_active', true)->whereNull('parent_id')->with('children')->get();
        $colors = Color::where('is_active', true)->get();
        $sizes = Size::where('is_active', true)->orderBy('sort_order')->get();

        return view('frontend.shop', compact('products', 'categories', 'colors', 'sizes'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with(['category', 'images', 'variants.color', 'variants.size', 'reviews' => fn($q) => $q->where('is_approved', true)])
            ->firstOrFail();

        $relatedProducts = Product::where('id', '!=', $product->id)
            ->where('is_active', true)
            ->when($product->category_id, fn($q) => $q->where('category_id', $product->category_id))
            ->with(['variants'])
            ->take(4)
            ->get();

        if ($relatedProducts->count() < 4) {
            $additional = Product::where('id', '!=', $product->id)
                ->where('is_active', true)
                ->whereNotIn('id', $relatedProducts->pluck('id'))
                ->take(4 - $relatedProducts->count())
                ->get();
            $relatedProducts = $relatedProducts->concat($additional);
        }

        return view('frontend.product_detail', compact('product', 'relatedProducts'));
    }

    public function storeReview(Request $request, $slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $request->validate([
            'name'   => 'required|string|max:100',
            'email'  => 'required|email|max:100',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:1000',
        ]);

        ProductReview::create([
            'product_id'  => $product->id,
            'user_id'     => auth('web')->id(),
            'name'        => $request->name,
            'email'       => $request->email,
            'rating'      => $request->rating,
            'review'      => $request->review,
            'is_approved' => true,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you! Your review has been submitted successfully.'
            ]);
        }

        return back()->with('success', 'Thank you! Your review has been submitted successfully.');
    }

    public function quickView($id)
    {
        $product = Product::where('id', $id)
            ->where('is_active', true)
            ->with(['category', 'images', 'variants.color', 'variants.size'])
            ->firstOrFail();

        return view('frontend.partials.quick_view_modal', compact('product'));
    }
}
