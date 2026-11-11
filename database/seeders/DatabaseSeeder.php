<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use App\Models\ShippingZone;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create default admin
        Admin::firstOrCreate(
            ['email' => 'admin@sogo.com'],
            [
                'name'     => 'Super Admin',
                'email'    => 'admin@sogo.com',
                'password' => Hash::make('admin123'),
                'is_active' => true,
            ]
        );

        // Default settings
        $settings = [
            'site_name'        => 'SOGO',
            'site_tagline'     => 'Own The Fashion Wave',
            'site_email'       => 'hello@sogo.com',
            'site_phone'       => '+8809xxxxxxxxx',
            'site_address'     => 'Dhaka, Bangladesh',
            'logo'             => '',
            'favicon'          => '',
            'footer_text'      => '© ' . date('Y') . ' SOGO. All rights reserved.',
            'facebook_url'     => '#',
            'instagram_url'    => '#',
            'currency_symbol'  => '৳',
            'bkash_number'     => '',
            'low_stock_alert'  => '5',
        ];
        foreach ($settings as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }

        // Default colors
        $colors = [
            ['name' => 'White',      'hex_code' => '#FFFFFF'],
            ['name' => 'Black',      'hex_code' => '#000000'],
            ['name' => 'Red',        'hex_code' => '#EF4444'],
            ['name' => 'Blue',       'hex_code' => '#3B82F6'],
            ['name' => 'Green',      'hex_code' => '#22C55E'],
            ['name' => 'Yellow',     'hex_code' => '#EAB308'],
            ['name' => 'Navy',       'hex_code' => '#1E3A5F'],
            ['name' => 'Maroon',     'hex_code' => '#800000'],
            ['name' => 'Beige',      'hex_code' => '#F5F0E8'],
            ['name' => 'Grey',       'hex_code' => '#6B7280'],
            ['name' => 'Olive',      'hex_code' => '#808000'],
            ['name' => 'Off-White',  'hex_code' => '#FAF9F6'],
        ];
        foreach ($colors as $c) {
            Color::firstOrCreate(['name' => $c['name']], $c);
        }

        // Default sizes
        $shirtSizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];
        foreach ($shirtSizes as $i => $s) {
            Size::firstOrCreate(['name' => $s, 'type' => 'shirt'], ['sort_order' => $i]);
        }
        $pantSizes = ['28', '30', '32', '34', '36', '38', '40'];
        foreach ($pantSizes as $i => $s) {
            Size::firstOrCreate(['name' => $s, 'type' => 'pant'], ['sort_order' => $i]);
        }

        // Default shipping zones
        $zones = [
            ['name' => 'Inside Dhaka',    'areas' => json_encode(['Dhaka City']), 'charge' => 70],
            ['name' => 'Sub-Urban Dhaka', 'areas' => json_encode(['Savar', 'Keraniganj', 'Gazipur', 'Narayanganj']), 'charge' => 120],
            ['name' => 'Outside Dhaka',   'areas' => json_encode(['All Other Districts']), 'charge' => 150],
        ];
        foreach ($zones as $zone) {
            ShippingZone::firstOrCreate(['name' => $zone['name']], $zone);
        }

        // Default categories
        $cats = [
            ['name' => 'Shirts',    'slug' => 'shirts',    'show_in_menu' => true],
            ['name' => 'Katua',     'slug' => 'katua',     'show_in_menu' => true],
            ['name' => 'Panjabi',   'slug' => 'panjabi',   'show_in_menu' => true],
            ['name' => 'T-Shirt',   'slug' => 't-shirt',   'show_in_menu' => true],
            ['name' => 'Polo Shirt','slug' => 'polo-shirt','show_in_menu' => true],
            ['name' => 'Pant',      'slug' => 'pant',      'show_in_menu' => true],
            ['name' => 'Winter',    'slug' => 'winter',    'show_in_menu' => true],
        ];
        foreach ($cats as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
