<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with(['variants.color', 'variants.size', 'category'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $shippingZones = ShippingZone::where('is_active', true)->get();

        return view('admin.pos.index', compact('products', 'categories', 'shippingZones'));
    }

    public function getProductVariants(Request $request)
    {
        $product = Product::with(['variants.color', 'variants.size', 'images'])
            ->findOrFail($request->product_id);

        return response()->json([
            'product'  => $product,
            'variants' => $product->variants->map(fn ($v) => [
                'id'           => $v->id,
                'color'        => $v->color?->name,
                'color_hex'    => $v->color?->hex_code,
                'size'         => $v->size?->name,
                'stock'        => $v->stock,
                'display_info' => $v->display_info,
                'price'        => $product->current_price + $v->price_adjustment,
            ]),
            'thumbnail' => $product->thumbnail_url,
            'price'     => $product->current_price,
        ]);
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'items'          => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $itemsData = [];

            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                $price = $product->current_price;

                $variantInfo = null;
                if (!empty($item['product_variant_id'])) {
                    $variant = ProductVariant::find($item['product_variant_id']);
                    if ($variant) {
                        $price += $variant->price_adjustment;
                        $variantInfo = $variant->display_info;
                        $variant->decrement('stock', $item['quantity']);
                    }
                } else {
                    $firstVariant = $product->variants()->first();
                    if ($firstVariant) {
                        $firstVariant->decrement('stock', $item['quantity']);
                    }
                }

                $lineSubtotal = $price * $item['quantity'];
                $subtotal += $lineSubtotal;

                $itemsData[] = [
                    'product_id'         => $item['product_id'],
                    'product_variant_id' => $item['product_variant_id'] ?? null,
                    'product_name'       => $product->name,
                    'variant_info'       => $variantInfo,
                    'price'              => $price,
                    'quantity'           => $item['quantity'],
                    'subtotal'           => $lineSubtotal,
                ];
            }

            $shippingCharge = ShippingZone::find($request->shipping_zone_id)?->charge ?? 0;
            $total = $subtotal + $shippingCharge;

            $order = Order::create([
                'order_number'     => Order::generateOrderNumber(),
                'branch_id'        => null,
                'customer_name'    => $request->customer_name,
                'customer_phone'   => $request->customer_phone,
                'customer_email'   => $request->customer_email,
                'customer_address' => $request->customer_address ?? 'Walk-in Customer - Dhaka',
                'customer_note'    => $request->customer_note,
                'subtotal'         => $subtotal,
                'shipping_charge'  => $shippingCharge,
                'discount_amount'  => 0,
                'total'            => $total,
                'shipping_zone_id' => $request->shipping_zone_id,
                'payment_method'   => $request->payment_method ?? 'cash',
                'payment_status'   => 'paid',
                'status'           => 'confirmed',
                'source'           => 'pos',
                'confirmed_at'     => now(),
            ]);

            foreach ($itemsData as $item) {
                $order->items()->create($item);
            }

            DB::commit();
            return response()->json([
                'success'      => true,
                'order_number' => $order->order_number,
                'total'        => $order->total,
                'order_id'     => $order->id,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
