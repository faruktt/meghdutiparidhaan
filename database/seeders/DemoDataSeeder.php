<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Color;
use App\Models\LandingPage;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Size;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Banner::truncate();
        Banner::create([
            'title'       => 'OWN THE FASHION Wave',
            'subtitle'    => 'New Winter & Festive Drop 2026',
            'image'       => 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?auto=format&fit=crop&w=1920&q=80',
            'link'        => '/shop',
            'position'    => 'hero',
            'sort_order'  => 1,
            'is_active'   => true,
        ]);

        // 2. Clear and populate the 12 exact arjobd shirts shown in screenshot
        Product::truncate();
        ProductVariant::truncate();

        $catShirts = Category::where('slug', 'shirts')->first();
        $catKatua  = Category::where('slug', 'katua')->first();
        $colors    = Color::all();
        $sizes     = Size::where('type', 'shirt')->get();

        $arjobdProducts = [
            [
                'name'              => 'Off-White Relaxed Cuban Collar Shirt',
                'regular_price'     => 1450,
                'sale_price'        => null,
                'category_id'       => $catShirts?->id,
                'thumbnail'         => 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name'              => 'Sky Blue Fine Striped Linen Shirt',
                'regular_price'     => 1750,
                'sale_price'        => 1550,
                'category_id'       => $catShirts?->id,
                'thumbnail'         => 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name'              => 'Sand Beige Minimal Button-Down Shirt',
                'regular_price'     => 1450,
                'sale_price'        => null,
                'category_id'       => $catShirts?->id,
                'thumbnail'         => 'https://images.unsplash.com/photo-1598033129183-c4f50c736f10?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name'              => 'Deep Maroon Luxury Cotton Shirt',
                'regular_price'     => 1850,
                'sale_price'        => 1650,
                'category_id'       => $catShirts?->id,
                'thumbnail'         => 'https://images.unsplash.com/photo-1603252109303-2751441dd157?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name'              => 'Soft Peach Oxford Casual Shirt',
                'regular_price'     => 1650,
                'sale_price'        => 1450,
                'category_id'       => $catShirts?->id,
                'thumbnail'         => 'https://images.unsplash.com/photo-1607345366928-199ea26cfe3e?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name'              => 'Navy Blue Pinstripe Casual Shirt',
                'regular_price'     => 1550,
                'sale_price'        => null,
                'category_id'       => $catShirts?->id,
                'thumbnail'         => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name'              => 'Crimson Red Signature Striped Shirt',
                'regular_price'     => 1850,
                'sale_price'        => 1650,
                'category_id'       => $catShirts?->id,
                'thumbnail'         => 'https://images.unsplash.com/photo-1620012253295-c15c429fbb41?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name'              => 'Dusty Lavender Textured Linen Shirt',
                'regular_price'     => 1450,
                'sale_price'        => null,
                'category_id'       => $catShirts?->id,
                'thumbnail'         => 'https://images.unsplash.com/photo-1608256246200-53e635b5b65f?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name'              => 'Olive Moss Minimal Collar Shirt',
                'regular_price'     => 1750,
                'sale_price'        => 1550,
                'category_id'       => $catShirts?->id,
                'thumbnail'         => 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name'              => 'Mustard Ochre Vintage Corduroy Shirt',
                'regular_price'     => 1950,
                'sale_price'        => 1750,
                'category_id'       => $catShirts?->id,
                'thumbnail'         => 'https://images.unsplash.com/photo-1589310243389-96a5483213a8?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name'              => 'Burgundy Dual Pocket Utility Shirt',
                'regular_price'     => 1650,
                'sale_price'        => null,
                'category_id'       => $catShirts?->id,
                'thumbnail'         => 'https://images.unsplash.com/photo-1603252109360-909baaf261c7?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name'              => 'Desert Khaki Safari Pocket Shirt',
                'regular_price'     => 1850,
                'sale_price'        => 1650,
                'category_id'       => $catShirts?->id,
                'thumbnail'         => 'https://images.unsplash.com/photo-1562157873-818bc0726f68?auto=format&fit=crop&w=800&q=80',
            ],
        ];

        foreach ($arjobdProducts as $idx => $pData) {
            $slug = Str::slug($pData['name']);
            $product = Product::create([
                'name'              => $pData['name'],
                'slug'              => $slug,
                'sku'               => 'SOGO-' . (1001 + $idx),
                'category_id'       => $pData['category_id'],
                'regular_price'     => $pData['regular_price'],
                'sale_price'        => $pData['sale_price'],
                'short_description' => 'Premium combed cotton fabric with artisanal tailoring and modern comfortable fit.',
                'description'       => 'Crafted for effortless daily luxury. Breathable, durable and carefully washed for supreme skin comfort. Features reinforced seams and branded buttons.',
                'more_information'  => '100% Combed Cotton | Wash with like colors in cold water.',
                'return_policy'     => '7 days hassle-free exchange or refund.',
                'thumbnail'         => $pData['thumbnail'],
                'is_active'         => true,
                'is_featured'       => true,
                'sort_order'        => $idx,
            ]);

            $sampleSizes = $sizes->take(4);
            $sampleColor = $colors->random();

            foreach ($sampleSizes as $sz) {
                ProductVariant::create([
                    'product_id'       => $product->id,
                    'color_id'         => $sampleColor->id,
                    'size_id'          => $sz->id,
                    'stock'            => rand(20, 50),
                    'price_adjustment' => 0,
                ]);
            }
        }

        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
