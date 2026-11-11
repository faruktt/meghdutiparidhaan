<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'variants'])->latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }
        if ($request->filled('section')) {
            if ($request->section === 'new_arrival') {
                $query->where('is_new_arrival', true);
            } elseif ($request->section === 'top_selling') {
                $query->where('is_top_selling', true);
            }
        }

        $products   = $query->paginate(20)->withQueryString();
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $parentCategories = Category::where('is_active', true)->whereNull('parent_id')->orderBy('name')->get();
        $subcategories    = Category::where('is_active', true)->whereNotNull('parent_id')->orderBy('name')->get();
        $colors           = Color::where('is_active', true)->orderBy('name')->get();
        $sizes            = Size::where('is_active', true)->orderBy('sort_order')->get();

        return view('admin.products.create', compact('parentCategories', 'subcategories', 'colors', 'sizes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'category_id'    => 'nullable|exists:categories,id',
            'subcategory_id' => 'nullable|exists:categories,id',
            'regular_price'  => 'required|numeric|min:0',
            'sale_price'     => 'nullable|numeric|min:0',
            'thumbnail'      => 'nullable|image|max:2048',
            'images.*'       => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $slug = Str::slug($request->name);
            $origSlug = $slug;
            $counter = 1;
            while (Product::where('slug', $slug)->exists()) {
                $slug = $origSlug . '-' . $counter++;
            }

            // Thumbnail upload
            $thumbnailName = null;
            if ($request->hasFile('thumbnail')) {
                $thumbnailName = $this->uploadImage($request->file('thumbnail'), 'products');
            }

            $product = Product::create([
                'name'              => $request->name,
                'slug'              => $slug,
                'sku'               => $request->sku ?: ('SKU-' . strtoupper(uniqid())),
                'category_id'       => $request->category_id,
                'subcategory_id'    => $request->subcategory_id,
                'short_description' => $request->short_description,
                'description'       => $request->description,
                'more_information'  => $request->more_information,
                'return_policy'     => $request->return_policy,
                'regular_price'     => $request->regular_price,
                'sale_price'        => $request->sale_price,
                'thumbnail'         => $thumbnailName,
                'is_active'         => $request->boolean('is_active', true),
                'is_featured'       => $request->boolean('is_featured'),
                'is_new_arrival'    => $request->boolean('is_new_arrival'),
                'is_top_selling'    => $request->boolean('is_top_selling'),
                'sort_order'        => $request->sort_order ?? 0,
            ]);

            // Additional images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $i => $img) {
                    $imgName = $this->uploadImage($img, 'products');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image'      => $imgName,
                        'sort_order' => $i,
                    ]);
                }
            }

            // Variants
            if ($request->filled('variants')) {
                foreach ($request->variants as $index => $variant) {
                    if (isset($variant['color_id']) || isset($variant['size_id'])) {
                        $variantImage = null;
                        if ($request->hasFile("variants.{$index}.image")) {
                            $variantImage = $this->uploadImage($request->file("variants.{$index}.image"), 'products');
                        }
                        ProductVariant::create([
                            'product_id'       => $product->id,
                            'color_id'         => $variant['color_id'] ?: null,
                            'size_id'          => $variant['size_id'] ?: null,
                            'stock'            => $variant['stock'] ?? 0,
                            'price_adjustment' => $variant['price_adjustment'] ?? 0,
                            'image'            => $variantImage,
                            'sku'              => $variant['sku'] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.products.index')
                ->with('success', 'Product created successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Product $product)
    {
        $product->load(['images', 'variants.color', 'variants.size', 'category', 'subcategory']);
        $parentCategories = Category::where('is_active', true)->whereNull('parent_id')->orderBy('name')->get();
        $subcategories    = Category::where('is_active', true)->whereNotNull('parent_id')->orderBy('name')->get();
        $colors           = Color::where('is_active', true)->orderBy('name')->get();
        $sizes            = Size::where('is_active', true)->orderBy('sort_order')->get();

        return view('admin.products.edit', compact('product', 'parentCategories', 'subcategories', 'colors', 'sizes'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'category_id'    => 'nullable|exists:categories,id',
            'subcategory_id' => 'nullable|exists:categories,id',
            'regular_price'  => 'required|numeric|min:0',
            'sale_price'     => 'nullable|numeric|min:0',
            'thumbnail'      => 'nullable|image|max:2048',
            'images.*'       => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->only([
                'name', 'category_id', 'subcategory_id', 'short_description', 'description',
                'more_information', 'return_policy', 'regular_price',
                'sale_price', 'sort_order', 'sku',
            ]);
            $data['is_active']       = $request->boolean('is_active', true);
            $data['is_featured']     = $request->boolean('is_featured');
            $data['is_new_arrival']  = $request->boolean('is_new_arrival');
            $data['is_top_selling']  = $request->boolean('is_top_selling');

            if ($request->hasFile('thumbnail')) {
                // Delete old
                if ($product->thumbnail && file_exists(public_path('images/products/' . $product->thumbnail))) {
                    unlink(public_path('images/products/' . $product->thumbnail));
                }
                $data['thumbnail'] = $this->uploadImage($request->file('thumbnail'), 'products');
            }

            $product->update($data);

            // Additional images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $i => $img) {
                    $imgName = $this->uploadImage($img, 'products');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image'      => $imgName,
                        'sort_order' => $product->images()->count() + $i,
                    ]);
                }
            }

            // Sync variants
            if ($request->filled('variants')) {
                // Delete existing that are not in new list
                $existingIds = $product->variants->pluck('id')->toArray();
                $keepIds = [];
                foreach ($request->variants as $index => $variant) {
                    $variantImage = null;
                    if ($request->hasFile("variants.{$index}.image")) {
                        $variantImage = $this->uploadImage($request->file("variants.{$index}.image"), 'products');
                    }

                    if (!empty($variant['id'])) {
                        $keepIds[] = $variant['id'];
                        $updateData = [
                            'color_id'         => $variant['color_id'] ?: null,
                            'size_id'          => $variant['size_id'] ?: null,
                            'stock'            => $variant['stock'] ?? 0,
                            'price_adjustment' => $variant['price_adjustment'] ?? 0,
                            'sku'              => $variant['sku'] ?? null,
                        ];
                        if ($variantImage) {
                            $updateData['image'] = $variantImage;
                        }
                        ProductVariant::where('id', $variant['id'])->update($updateData);
                    } else {
                        $pv = ProductVariant::create([
                            'product_id'       => $product->id,
                            'color_id'         => $variant['color_id'] ?: null,
                            'size_id'          => $variant['size_id'] ?: null,
                            'stock'            => $variant['stock'] ?? 0,
                            'price_adjustment' => $variant['price_adjustment'] ?? 0,
                            'image'            => $variantImage,
                            'sku'              => $variant['sku'] ?? null,
                        ]);
                        $keepIds[] = $pv->id;
                    }
                }
                // Delete removed variants
                $toDelete = array_diff($existingIds, $keepIds);
                ProductVariant::whereIn('id', $toDelete)->delete();
            }

            DB::commit();
            return redirect()->route('admin.products.index')
                ->with('success', 'Product updated successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Product $product)
    {
        // Delete images
        if ($product->thumbnail && file_exists(public_path('images/products/' . $product->thumbnail))) {
            unlink(public_path('images/products/' . $product->thumbnail));
        }
        foreach ($product->images as $img) {
            if (file_exists(public_path('images/products/' . $img->image))) {
                unlink(public_path('images/products/' . $img->image));
            }
        }
        $product->delete();
        return back()->with('success', 'Product deleted successfully!');
    }

    public function deleteImage(ProductImage $image)
    {
        if (file_exists(public_path('images/products/' . $image->image))) {
            unlink(public_path('images/products/' . $image->image));
        }
        $image->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Upload image and return filename only
     */
    private function uploadImage($file, $folder)
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images/' . $folder), $filename);
        return $filename;
    }
}
