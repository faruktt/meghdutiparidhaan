<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title'       => 'About Us',
                'slug'        => 'about-us',
                'sort_order'  => 1,
                'is_active'   => true,
                'description' => '<h3>Welcome to Meghduti Paridhaan</h3><p>Meghduti Paridhaan is your premier destination for traditional and contemporary ethnic clothing. We specialize in handcrafted jamdani, pure silk, handloom, and exquisite boutique fashion designed to celebrate the richness of Bengal\'s weaving heritage with modern elegance.</p><h4>Our Story & Mission</h4><p>Founded with a passion for quality fabrics and authentic artisanal craftsmanship, our mission is to deliver authentic, high-grade sarees and attire directly to your doorstep across Bangladesh with utmost care and transparency.</p>',
            ],
            [
                'title'       => 'Privacy Policy',
                'slug'        => 'privacy-policy',
                'sort_order'  => 2,
                'is_active'   => true,
                'description' => '<h3>Privacy Policy</h3><p>At Meghduti Paridhaan, we value your privacy and are committed to safeguarding your personal information.</p><h4>Information We Collect</h4><p>When you place an order or create an account, we collect necessary details such as your name, delivery address, phone number, and email to process your shipments and provide order status updates.</p><h4>Data Protection</h4><p>We do not sell, rent, or trade your personal information with third parties. Your data is used exclusively for order fulfillment, courier delivery, and customer service.</p>',
            ],
            [
                'title'       => 'Terms of Conditions',
                'slug'        => 'terms-conditions',
                'sort_order'  => 3,
                'is_active'   => true,
                'description' => '<h3>Terms of Conditions</h3><p>Welcome to Meghduti Paridhaan. By browsing our website and placing orders, you agree to abide by our terms and conditions.</p><h4>Product Details & Pricing</h4><p>We make every effort to display the colors and textures of our boutique collections as accurately as possible. Prices are quoted in Bangladeshi Taka (BDT) and are subject to change without prior notice.</p><h4>Order Confirmation & Delivery</h4><p>All orders are confirmed via phone or automated verification prior to dispatch. Delivery times are estimated according to standard courier schedules.</p>',
            ],
            [
                'title'       => 'Refund Policy',
                'slug'        => 'refund-policy',
                'sort_order'  => 4,
                'is_active'   => true,
                'description' => '<h3>Refund Policy</h3><p>Your satisfaction is our primary goal. If you encounter any defects or issues with your order, we are here to help.</p><h4>Eligibility for Refunds</h4><p>Refunds or exchanges must be requested within 3 days of receiving your parcel. The product must remain unworn, unwashed, and in its original packaging with tags intact.</p><h4>Refund Process</h4><p>Once your returned item is received and inspected, we will notify you of the approval or rejection of your refund. Approved refunds are processed via mobile banking (bKash/Nagad) or bank transfer within 3-5 business days.</p>',
            ],
            [
                'title'       => 'Cancellation Policy',
                'slug'        => 'cancellation-policy',
                'sort_order'  => 5,
                'is_active'   => true,
                'description' => '<h3>Cancellation Policy</h3><p>We understand that circumstances may change. Here is our cancellation guidelines:</p><h4>Order Cancellation</h4><p>You may cancel your order at any time before it has been dispatched to the courier service. To cancel, please contact our customer support hotline or send us a WhatsApp message with your Order ID.</p><h4>Post-Dispatch</h4><p>Once a parcel has been handed over to the courier service, it cannot be cancelled mid-transit. In such cases, standard exchange or return policies will apply upon delivery.</p>',
            ],
        ];

        foreach ($pages as $pageData) {
            Page::firstOrCreate(
                ['slug' => $pageData['slug']],
                $pageData
            );
        }
    }
}

