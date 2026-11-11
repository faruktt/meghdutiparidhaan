<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Setting;
use App\Models\Size;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class MeghdutiDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Settings update
        $settings = [
            'site_name'        => 'Meghduti Paridhaan',
            'site_tagline'     => 'Exclusive Saree & Women Boutique Collection',
            'site_email'       => 'apon1001@gmail.com',
            'site_phone'       => '88-01983380065',
            'site_address'     => 'Dhaka',
            'currency_symbol'  => '৳',
            'facebook_url'     => 'https://facebook.com',
            'instagram_url'    => 'https://instagram.com',
            'whatsapp_number'  => '01983380065',
            'footer_text'      => 'Powered by Hishabee Business Manager',
        ];

        foreach ($settings as $k => $v) {
            Setting::updateOrCreate(['key' => $k], ['value' => $v]);
        }

        // 2. Clear old products and categories
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        ProductVariant::truncate();
        Product::truncate();
        Category::truncate();

        // 3. Create Categories
        $parentCat = Category::create([
            'name'         => 'Woman Clothing',
            'slug'         => 'woman-clothing',
            'is_active'    => true,
            'show_in_menu' => true,
            'sort_order'   => 1,
        ]);

        $subcategories = [
            ['name' => 'Jamdani Saree',     'slug' => 'jamdani-saree',      'parent_id' => $parentCat->id],
            ['name' => 'Manipuri Saree',    'slug' => 'manipuri-saree',     'parent_id' => $parentCat->id],
            ['name' => 'Cotton Handloom',   'slug' => 'cotton-handloom',    'parent_id' => $parentCat->id],
            ['name' => 'Silk Saree',        'slug' => 'silk-saree',         'parent_id' => $parentCat->id],
            ['name' => 'Boutique 3-Piece',  'slug' => 'boutique-three-piece','parent_id' => $parentCat->id],
        ];

        foreach ($subcategories as $sc) {
            Category::create(array_merge($sc, [
                'is_active'    => true,
                'show_in_menu' => true,
                'sort_order'   => 2,
            ]));
        }

        $jamdaniCat  = Category::where('slug', 'jamdani-saree')->first();
        $manipuriCat = Category::where('slug', 'manipuri-saree')->first();

        // Default colors & sizes for saree
        $colors = Color::all();
        $freeSize = Size::firstOrCreate(['name' => 'Regular Free Size', 'type' => 'saree'], ['sort_order' => 1]);

        // Beautiful curated high-quality saree photography matching the boutique style
        $sareeImages = [
            'https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&w=700&q=80', // Pink Jamdani / boutique saree
            'https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?auto=format&fit=crop&w=700&q=80', // Offwhite / white gold
            'https://images.unsplash.com/photo-1617627143750-d86bc21e42bb?auto=format&fit=crop&w=700&q=80', // Teal / Blue silk
            'https://images.unsplash.com/photo-1610030469668-935cb7462a6e?auto=format&fit=crop&w=700&q=80', // Red & Gold saree
            'https://images.unsplash.com/photo-1609357605129-26f69add5d6e?auto=format&fit=crop&w=700&q=80', // Yellow magenta
            'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?auto=format&fit=crop&w=700&q=80', // Pink elegant saree
            'https://images.unsplash.com/photo-1549439602-43ebca2327af?auto=format&fit=crop&w=700&q=80', // Coffee / brown
            'https://images.unsplash.com/photo-1601924994987-69e26d50dc26?auto=format&fit=crop&w=700&q=80', // Blue floral saree
            'https://images.unsplash.com/photo-1610030469858-37d45e0d37e4?auto=format&fit=crop&w=700&q=80', // Yellow white saree
            'https://images.unsplash.com/photo-1617627143750-d86bc21e42bb?auto=format&fit=crop&w=700&q=80', // Teal color
            'https://images.unsplash.com/photo-1558769132-cb1aea458c5e?auto=format&fit=crop&w=700&q=80', // Black saree
            'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?auto=format&fit=crop&w=700&q=80', // Sky blue
            'https://images.unsplash.com/photo-1583391733975-0010c735d491?auto=format&fit=crop&w=700&q=80', // Nova pink
            'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?auto=format&fit=crop&w=700&q=80', // Sea green
            'https://images.unsplash.com/photo-1609357605129-26f69add5d6e?auto=format&fit=crop&w=700&q=80', // Teal Gonga
            'https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&w=700&q=80', // Light Blue white
            'https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?auto=format&fit=crop&w=700&q=80', // White red
            'https://images.unsplash.com/photo-1617627143750-d86bc21e42bb?auto=format&fit=crop&w=700&q=80', // Yellow Majenda
            'https://images.unsplash.com/photo-1610030469668-935cb7462a6e?auto=format&fit=crop&w=700&q=80', // Offwhite Gonga
            'https://images.unsplash.com/photo-1609357605129-26f69add5d6e?auto=format&fit=crop&w=700&q=80', // Red Saree
            'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?auto=format&fit=crop&w=700&q=80', // Offwhite Yellow
            'https://images.unsplash.com/photo-1549439602-43ebca2327af?auto=format&fit=crop&w=700&q=80', // Yellow saree
            'https://images.unsplash.com/photo-1601924994987-69e26d50dc26?auto=format&fit=crop&w=700&q=80', // Purple Yellow
            'https://images.unsplash.com/photo-1610030469858-37d45e0d37e4?auto=format&fit=crop&w=700&q=80', // Multi color
        ];

        // 4. Curated 24 Products exactly from the user's provided screenshot
        $productsData = [
            // Trending 5
            [
                'name'          => 'Jamdani 60/80 Count Coffee colour',
                'regular_price' => 4200,
                'category_id'   => $jamdaniCat->id,
                'is_featured'   => true,
                'stock'         => 18,
            ],
            [
                'name'          => 'Manipuri Shoul/ Off white gonga Jamuna',
                'regular_price' => 2500,
                'category_id'   => $manipuriCat->id,
                'is_featured'   => true,
                'stock'         => 22,
            ],
            [
                'name'          => 'Jamdani 40 count Teal Color',
                'regular_price' => 2000,
                'category_id'   => $jamdaniCat->id,
                'is_featured'   => true,
                'stock'         => 15,
            ],
            [
                'name'          => 'Manipuri white red matching',
                'regular_price' => 1550,
                'category_id'   => $manipuriCat->id,
                'is_featured'   => true,
                'stock'         => 30,
            ],
            [
                'name'          => 'Jamdani Machbut/ Yellow Majenda',
                'regular_price' => 1200,
                'category_id'   => $jamdaniCat->id,
                'is_featured'   => true,
                'stock'         => 25,
            ],

            // All Products Grid
            [
                'name'          => 'Jamdani Machbut pink',
                'regular_price' => 1200,
                'category_id'   => $jamdaniCat->id,
                'is_featured'   => false,
                'stock'         => 12,
            ],
            [
                'name'          => 'Jamdani 60/80 Count Coffee colour Exclusive',
                'regular_price' => 4200,
                'category_id'   => $jamdaniCat->id,
                'is_featured'   => false,
                'stock'         => 10,
            ],
            [
                'name'          => 'Jamdani Machbut/ Blue',
                'regular_price' => 1200,
                'category_id'   => $jamdaniCat->id,
                'is_featured'   => false,
                'stock'         => 14,
            ],
            [
                'name'          => 'Manipuri Shoul/ Off white gonga Jamuna Classic',
                'regular_price' => 2500,
                'category_id'   => $manipuriCat->id,
                'is_featured'   => false,
                'stock'         => 8,
            ],
            [
                'name'          => 'Manipuri Yellow white Matching',
                'regular_price' => 1550,
                'category_id'   => $manipuriCat->id,
                'is_featured'   => false,
                'stock'         => 16,
            ],
            [
                'name'          => 'Jamdani 40 count Teal Color Festive',
                'regular_price' => 2000,
                'category_id'   => $jamdaniCat->id,
                'is_featured'   => false,
                'stock'         => 20,
            ],
            [
                'name'          => 'Jamdani Machbut/ Black',
                'regular_price' => 1200,
                'category_id'   => $jamdaniCat->id,
                'is_featured'   => false,
                'stock'         => 15,
            ],
            [
                'name'          => 'Jamdani Machbut/ Sky',
                'regular_price' => 1200,
                'category_id'   => $jamdaniCat->id,
                'is_featured'   => false,
                'stock'         => 9,
            ],
            [
                'name'          => 'Jamdani Machbut/ Nova Pink',
                'regular_price' => 1200,
                'category_id'   => $jamdaniCat->id,
                'is_featured'   => false,
                'stock'         => 11,
            ],
            // Exactly as in screenshot: OUT OF STOCK item (row 2, col 5)
            [
                'name'          => 'Jamdani Machbut/ Sea Green',
                'regular_price' => 1200,
                'category_id'   => $jamdaniCat->id,
                'is_featured'   => false,
                'stock'         => 0, // OUT OF STOCK!
            ],
            [
                'name'          => 'Manipuri Shoul/ Teal Gonga Jamuna',
                'regular_price' => 2650,
                'category_id'   => $manipuriCat->id,
                'is_featured'   => false,
                'stock'         => 14,
            ],
            [
                'name'          => 'Manipuri Shoul/ Light Blue white Matching',
                'regular_price' => 2600,
                'category_id'   => $manipuriCat->id,
                'is_featured'   => false,
                'stock'         => 18,
            ],
            [
                'name'          => 'Manipuri white red matching Classic',
                'regular_price' => 1550,
                'category_id'   => $manipuriCat->id,
                'is_featured'   => false,
                'stock'         => 20,
            ],
            [
                'name'          => 'Jamdani Machbut/ Yellow Majenda Contrast',
                'regular_price' => 1200,
                'category_id'   => $jamdaniCat->id,
                'is_featured'   => false,
                'stock'         => 12,
            ],
            [
                'name'          => 'Manipuri Offwhite Gonga Jamuna',
                'regular_price' => 1550,
                'category_id'   => $manipuriCat->id,
                'is_featured'   => false,
                'stock'         => 22,
            ],
            [
                'name'          => 'Jamdani Machbut Red',
                'regular_price' => 1200,
                'category_id'   => $jamdaniCat->id,
                'is_featured'   => false,
                'stock'         => 17,
            ],
            [
                'name'          => 'Manipuri Offwhite Yellow Matching',
                'regular_price' => 1550,
                'category_id'   => $manipuriCat->id,
                'is_featured'   => false,
                'stock'         => 15,
            ],
            [
                'name'          => 'Jamdani Machbut/ Yellow',
                'regular_price' => 1200,
                'category_id'   => $jamdaniCat->id,
                'is_featured'   => false,
                'stock'         => 19,
            ],
            [
                'name'          => 'Jamdani Machbut/ Purple Yellow',
                'regular_price' => 1200,
                'category_id'   => $jamdaniCat->id,
                'is_featured'   => false,
                'stock'         => 13,
            ],
            [
                'name'          => 'Manipuri White red Multi Color',
                'regular_price' => 1550,
                'category_id'   => $manipuriCat->id,
                'is_featured'   => false,
                'stock'         => 14,
            ],
        ];

        foreach ($productsData as $idx => $p) {
            $slug = Str::slug($p['name']);
            if (Product::where('slug', $slug)->exists()) {
                $slug .= '-' . ($idx + 1);
            }

            $imgUrl = $sareeImages[$idx % count($sareeImages)];

            $product = Product::create([
                'name'              => $p['name'],
                'slug'              => $slug,
                'sku'               => 'MP-' . (2001 + $idx),
                'category_id'       => $p['category_id'],
                'regular_price'     => $p['regular_price'],
                'sale_price'        => null,
                'short_description' => 'মেঘদূতী পরিধান এর ঐতিহ্যবাহী জামদানি ও মণিপুরি শাড়ি কালেকশন। আরামদায়ক ও নিখুঁত সুতার কাজ।',
                'description'       => 'মেঘদূতী পরিধান এর অনন্য শাড়ি কালেকশন। ১০০% খাঁটি সুতি ও তাতের কাজ, নিখুঁত আঁচল ও পাড়ের নকশা। বিশেষ উৎসব, অনুষ্ঠান এবং নিয়মিত ব্যবহারের জন্য অত্যন্ত মানানসই। ডেলিভারি সারা বাংলাদেশে।',
                'more_information'  => 'উপাদান: খাঁটি সুতি / জামদানি সুতা | দৈর্ঘ্য: ১২ হাত (ব্লাউজ পিস সহ) | যত্ন: ড্রাই ওয়াশ বা নরমাল শ্যাম্পু ওয়াশ।',
                'return_policy'     => 'ডেলিভারির সময় দেখে নিন। কোনো সমস্যা হলে সাথে সাথে রিটার্ন করতে পারবেন।',
                'thumbnail'         => $imgUrl,
                'is_active'         => true,
                'is_featured'       => $p['is_featured'],
                'sort_order'        => $idx,
            ]);

            // Add variant
            ProductVariant::create([
                'product_id'       => $product->id,
                'color_id'         => $colors->isNotEmpty() ? $colors->random()->id : null,
                'size_id'          => $freeSize->id,
                'stock'            => $p['stock'],
                'price_adjustment' => 0,
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}

