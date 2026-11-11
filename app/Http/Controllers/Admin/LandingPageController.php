<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingPage;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LandingPageController extends Controller
{
    public function index()
    {
        $pages = LandingPage::withCount('products')->latest()->paginate(15)->withQueryString();
        return view('admin.landing.index', compact('pages'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        return view('admin.landing.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'slug'         => 'nullable|string|max:255|unique:landing_pages,slug',
            'banner_image' => 'nullable|image|max:2048',
            'products'     => 'nullable|array',
            'products.*'   => 'exists:products,id',
        ]);

        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->title);
        $originalSlug = $slug;
        $counter = 1;
        while (LandingPage::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        $bannerName = null;
        if ($request->hasFile('banner_image')) {
            $file = $request->file('banner_image');
            $bannerName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/landing'), $bannerName);
        }

        $page = LandingPage::create([
            'title'            => $request->title,
            'slug'             => $slug,
            'urgency_text'     => $request->urgency_text,
            'description'      => $request->description,
            'details'          => $request->details,
            'banner_image'     => $bannerName,
            'feature_badge_1'  => $request->feature_badge_1,
            'feature_badge_2'  => $request->feature_badge_2,
            'meta_title'       => $request->meta_title,
            'meta_description' => $request->meta_description,
            'is_active'        => $request->boolean('is_active', true),
            'sort_order'       => $request->sort_order ?? 0,
        ]);

        if ($request->filled('products')) {
            $syncData = [];
            foreach ($request->products as $index => $productId) {
                $syncData[$productId] = ['sort_order' => $index];
            }
            $page->products()->sync($syncData);
        }

        return redirect()->route('admin.landing.index')->with('success', 'Landing page created successfully!');
    }

    public function edit(LandingPage $landing)
    {
        $landing->load('products');
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $selectedProductIds = $landing->products->pluck('id')->toArray();

        return view('admin.landing.edit', compact('landing', 'products', 'selectedProductIds'));
    }

    public function update(Request $request, LandingPage $landing)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'slug'         => 'nullable|string|max:255|unique:landing_pages,slug,' . $landing->id,
            'banner_image' => 'nullable|image|max:2048',
            'products'     => 'nullable|array',
            'products.*'   => 'exists:products,id',
        ]);

        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->title);
        $originalSlug = $slug;
        $counter = 1;
        while (LandingPage::where('slug', $slug)->where('id', '!=', $landing->id)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        $bannerName = $landing->banner_image;
        if ($request->hasFile('banner_image')) {
            if ($bannerName && file_exists(public_path('images/landing/' . $bannerName))) {
                unlink(public_path('images/landing/' . $bannerName));
            }
            $file = $request->file('banner_image');
            $bannerName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/landing'), $bannerName);
        }

        $landing->update([
            'title'            => $request->title,
            'slug'             => $slug,
            'urgency_text'     => $request->urgency_text,
            'description'      => $request->description,
            'details'          => $request->details,
            'banner_image'     => $bannerName,
            'feature_badge_1'  => $request->feature_badge_1,
            'feature_badge_2'  => $request->feature_badge_2,
            'meta_title'       => $request->meta_title,
            'meta_description' => $request->meta_description,
            'is_active'        => $request->boolean('is_active', true),
            'sort_order'       => $request->sort_order ?? 0,
        ]);

        $syncData = [];
        if ($request->filled('products')) {
            foreach ($request->products as $index => $productId) {
                $syncData[$productId] = ['sort_order' => $index];
            }
        }
        $landing->products()->sync($syncData);

        return redirect()->route('admin.landing.index')->with('success', 'Landing page updated successfully!');
    }

    public function destroy(LandingPage $landing)
    {
        if ($landing->banner_image && file_exists(public_path('images/landing/' . $landing->banner_image))) {
            unlink(public_path('images/landing/' . $landing->banner_image));
        }
        $landing->products()->detach();
        $landing->delete();

        return back()->with('success', 'Landing page deleted successfully!');
    }
}
