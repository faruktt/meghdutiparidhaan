<?php

namespace App\Http\Controllers;

use App\Models\LandingPage;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ShippingZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LandingPageFrontendController extends Controller
{
    public function show($slug)
    {
        $page = LandingPage::where('slug', $slug)
            ->where('is_active', true)
            ->with(['products' => function ($q) {
                $q->where('is_active', true)
                  ->with(['variants.color', 'variants.size', 'images']);
            }])
            ->firstOrFail();

        $shippingZones = ShippingZone::where('is_active', true)->orderBy('sort_order')->get();
        $defaultZone = $shippingZones->first();
        $shippingCharge = $defaultZone ? $defaultZone->charge : 60;

        return view('frontend.landing_page', compact('page', 'shippingZones', 'shippingCharge'));
    }

    public function order(Request $request, $slug)
    {
        $page = LandingPage::where('slug', $slug)->firstOrFail();

        $request->validate([
            'customer_name'    => 'required|string|max:255',
            'customer_phone'   => 'required|string|max:20',
            'customer_address' => 'required|string|max:500',
            'shipping_zone_id' => 'required|exists:shipping_zones,id',
            'product_id'       => 'required|exists:products,id',
            'quantity'         => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($request->product_id);
            $price = $product->current_price;
            $variantInfo = null;

            if ($request->filled('variant_id')) {
                $variant = ProductVariant::find($request->variant_id);
                if ($variant) {
                    $price += $variant->price_adjustment;
                    $variantInfo = $variant->display_info;
                    $variant->decrement('stock', $request->quantity);
                }
            }

            $subtotal = $price * $request->quantity;
            $shippingZone = ShippingZone::findOrFail($request->shipping_zone_id);
            $shippingCharge = $shippingZone->charge;
            $total = $subtotal + $shippingCharge;

            $order = Order::create([
                'order_number'     => Order::generateOrderNumber(),
                'customer_name'    => $request->customer_name,
                'customer_phone'   => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'customer_note'    => 'Landing Page: ' . $page->title . ($request->customer_note ? ' | ' . $request->customer_note : ''),
                'subtotal'         => $subtotal,
                'shipping_charge'  => $shippingCharge,
                'discount_amount'  => 0,
                'total'            => $total,
                'shipping_zone_id' => $shippingZone->id,
                'payment_method'   => $request->payment_method ?? 'cod',
                'payment_status'   => 'unpaid',
                'status'           => 'pending',
                'source'           => 'landing',
            ]);

            $order->items()->create([
                'product_id'         => $product->id,
                'product_variant_id' => $request->variant_id ?? null,
                'product_name'       => $product->name,
                'variant_info'       => $variantInfo,
                'price'              => $price,
                'quantity'           => $request->quantity,
                'subtotal'           => $subtotal,
            ]);

            DB::commit();

            return redirect()->route('checkout.success', $order->order_number);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'There was an issue processing your order: ' . $e->getMessage())->withInput();
        }
    }
}
