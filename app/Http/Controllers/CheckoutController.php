<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ShippingZone;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $cart = session()->get('cart', []);

        // Direct Buy feature (if product_id is sent directly)
        if ($request->filled('direct_product_id')) {
            $product = Product::findOrFail($request->direct_product_id);
            $variant = null;
            $variantKey = '0';
            $price = $product->current_price;
            $variantInfo = null;

            if ($request->filled('direct_variant_id')) {
                $variant = ProductVariant::with(['color', 'size'])->find($request->direct_variant_id);
                if ($variant) {
                    $variantKey = $variant->id;
                    $price += $variant->price_adjustment;
                    $variantInfo = $variant->display_info;
                }
            }

            $cartKey = $product->id . '_' . $variantKey;
            $qty = max(1, (int)$request->get('direct_qty', 1));

            // Set cart to this direct item
            $cart = [
                $cartKey => [
                    'product_id'    => $product->id,
                    'variant_id'    => $variant ? $variant->id : null,
                    'name'          => $product->name,
                    'price'         => $price,
                    'regular_price' => $product->regular_price,
                    'quantity'      => $qty,
                    'image'         => ($variant && $variant->image_url) ? $variant->image_url : $product->thumbnail_url,
                    'variant_info'  => $variantInfo,
                    'slug'          => $product->slug,
                ]
            ];
            session()->put('cart', $cart);
        }

        if (empty($cart)) {
            return redirect()->route('shop.index')->with('warning', 'Your shopping bag is empty. Please select products.');
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $shippingZones = ShippingZone::where('is_active', true)->orderBy('sort_order')->get();
        $defaultZone = $shippingZones->first();
        $shippingCharge = $defaultZone ? $defaultZone->charge : 60;

        return view('frontend.checkout', compact('cart', 'subtotal', 'shippingZones', 'shippingCharge'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'customer_name'    => 'required|string|max:255',
            'customer_phone'   => 'required|string|max:20',
            'customer_address' => 'required|string|max:500',
            'shipping_zone_id' => 'required|exists:shipping_zones,id',
            'payment_method'   => 'required|in:cod,bkash,nagad',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('shop.index')->with('error', 'Your shopping bag is empty.');
        }

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $orderItems = [];

            foreach ($cart as $item) {
                $product = Product::find($item['product_id']);
                if (!$product) continue;

                $price = $item['price'];
                $itemSubtotal = $price * $item['quantity'];
                $subtotal += $itemSubtotal;

                // Deduct variant stock if variant exists
                if (!empty($item['variant_id'])) {
                    $variant = ProductVariant::find($item['variant_id']);
                    if ($variant) {
                        $variant->decrement('stock', $item['quantity']);
                    }
                }

                $orderItems[] = [
                    'product_id'         => $product->id,
                    'product_variant_id' => $item['variant_id'] ?? null,
                    'product_name'       => $item['name'],
                    'variant_info'       => $item['variant_info'] ?? null,
                    'price'              => $price,
                    'quantity'           => $item['quantity'],
                    'subtotal'           => $itemSubtotal,
                ];
            }

            $shippingZone = ShippingZone::findOrFail($request->shipping_zone_id);
            $shippingCharge = $shippingZone->charge;
            $total = $subtotal + $shippingCharge;

            $userId = Auth::guard('web')->id();

            // If user is guest, optionally check or link with registered phone
            if (!$userId && $request->filled('customer_phone')) {
                $user = User::where('phone', $request->customer_phone)->first();
                if ($user) {
                    $userId = $user->id;
                }
            }

            $order = Order::create([
                'order_number'     => Order::generateOrderNumber(),
                'user_id'          => $userId,
                'customer_name'    => $request->customer_name,
                'customer_phone'   => $request->customer_phone,
                'customer_email'   => $request->customer_email,
                'customer_address' => $request->customer_address,
                'customer_note'    => $request->customer_note,
                'subtotal'         => $subtotal,
                'shipping_charge'  => $shippingCharge,
                'discount_amount'  => 0,
                'total'            => $total,
                'shipping_zone_id' => $shippingZone->id,
                'payment_method'   => $request->payment_method,
                'payment_status'   => 'unpaid',
                'status'           => 'pending',
                'source'           => $request->source ?? 'web',
            ]);

            foreach ($orderItems as $item) {
                $order->items()->create($item);
            }

            DB::commit();

            // Mark draft order as converted if exists
            if ($request->filled('customer_phone')) {
                \App\Models\DraftOrder::where('customer_phone', $request->customer_phone)
                    ->where('status', 'pending')
                    ->update([
                        'status'   => 'converted',
                        'order_id' => $order->id,
                    ]);
            }
            if (session()->has('draft_order_id')) {
                \App\Models\DraftOrder::where('id', session('draft_order_id'))
                    ->update([
                        'status'   => 'converted',
                        'order_id' => $order->id,
                    ]);
                session()->forget('draft_order_id');
            }

            // Clear Cart
            session()->forget('cart');

            return redirect()->route('checkout.success', $order->order_number);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'There was a problem processing your order: ' . $e->getMessage())->withInput();
        }
    }

    public function saveDraft(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return response()->json(['success' => false, 'message' => 'Cart is empty']);
        }

        $phone = trim($request->input('customer_phone', ''));
        $name = trim($request->input('customer_name', ''));
        $address = trim($request->input('customer_address', ''));
        $zoneId = $request->input('shipping_zone_id');

        // Only save draft if at least name or phone is provided
        if (empty($phone) && empty($name)) {
            return response()->json(['success' => false, 'message' => 'No contact info provided']);
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $shippingCharge = 0;
        if ($zoneId) {
            $zone = ShippingZone::find($zoneId);
            if ($zone) $shippingCharge = $zone->charge;
        } else {
            $firstZone = ShippingZone::where('is_active', true)->orderBy('sort_order')->first();
            if ($firstZone) $shippingCharge = $firstZone->charge;
        }

        $total = $subtotal + $shippingCharge;

        // Find existing pending draft by phone or session
        $draft = null;
        if (!empty($phone)) {
            $draft = \App\Models\DraftOrder::where('customer_phone', $phone)
                ->where('status', 'pending')
                ->latest()
                ->first();
        }

        if (!$draft && session()->has('draft_order_id')) {
            $draft = \App\Models\DraftOrder::where('id', session('draft_order_id'))
                ->where('status', 'pending')
                ->first();
        }

        $data = [
            'customer_name'    => $name ?: ($draft?->customer_name ?? null),
            'customer_phone'   => $phone ?: ($draft?->customer_phone ?? null),
            'customer_address' => $address ?: ($draft?->customer_address ?? null),
            'shipping_zone_id' => $zoneId ?: ($draft?->shipping_zone_id ?? null),
            'cart_data'        => array_values($cart),
            'subtotal'         => $subtotal,
            'shipping_charge'  => $shippingCharge,
            'total'            => $total,
            'ip_address'       => $request->ip(),
            'user_agent'       => substr($request->userAgent() ?? '', 0, 500),
            'status'           => 'pending',
        ];

        if ($draft) {
            $draft->update($data);
        } else {
            $draft = \App\Models\DraftOrder::create($data);
            session()->put('draft_order_id', $draft->id);
        }

        return response()->json([
            'success'  => true,
            'draft_id' => $draft->id,
            'message'  => 'Draft order saved successfully',
        ]);
    }

    public function success($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['items.product', 'shippingZone'])
            ->firstOrFail();

        return view('frontend.order_success', compact('order'));
    }
}
