<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DraftOrder;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ShippingZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DraftOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = DraftOrder::query()->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('customer_address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default show pending drafts first
            $query->where('status', 'pending');
        }

        $draftOrders = $query->paginate(20)->withQueryString();

        $counts = [
            'pending'   => DraftOrder::where('status', 'pending')->count(),
            'converted' => DraftOrder::where('status', 'converted')->count(),
            'total'     => DraftOrder::count(),
        ];

        return view('admin.draft_orders.index', compact('draftOrders', 'counts'));
    }

    public function show(DraftOrder $draftOrder)
    {
        return view('admin.draft_orders.show', compact('draftOrder'));
    }

    public function convert(Request $request, DraftOrder $draftOrder)
    {
        if ($draftOrder->status === 'converted' && $draftOrder->order_id) {
            return redirect()->route('admin.orders.show', $draftOrder->order_id)
                ->with('info', 'This draft has already been converted to an order.');
        }

        if (empty($draftOrder->cart_data) || !is_array($draftOrder->cart_data)) {
            return back()->with('error', 'Cannot convert order with empty cart items.');
        }

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $orderItems = [];

            foreach ($draftOrder->cart_data as $item) {
                $product = Product::find($item['product_id']);
                if (!$product) {
                    continue;
                }

                $qty = max(1, (int)($item['quantity'] ?? 1));
                $price = (float)($item['price'] ?? $product->current_price);
                $itemSubtotal = $price * $qty;
                $subtotal += $itemSubtotal;

                if (!empty($item['variant_id'])) {
                    $variant = ProductVariant::find($item['variant_id']);
                    if ($variant) {
                        $variant->decrement('stock', $qty);
                    }
                }

                $orderItems[] = [
                    'product_id'         => $product->id,
                    'product_variant_id' => $item['variant_id'] ?? null,
                    'product_name'       => $item['name'] ?? $product->name,
                    'variant_info'       => $item['variant_info'] ?? null,
                    'price'              => $price,
                    'quantity'           => $qty,
                    'subtotal'           => $itemSubtotal,
                ];
            }

            if (empty($orderItems)) {
                throw new \Exception('No valid products found in this draft.');
            }

            $shippingCharge = (float)($draftOrder->shipping_charge ?? 0);
            $total = $subtotal + $shippingCharge;

            $order = Order::create([
                'order_number'     => Order::generateOrderNumber(),
                'customer_name'    => $draftOrder->customer_name ?: 'Guest Customer',
                'customer_phone'   => $draftOrder->customer_phone ?: '',
                'customer_address' => $draftOrder->customer_address ?: 'Address pending',
                'subtotal'         => $subtotal,
                'shipping_charge'  => $shippingCharge,
                'discount_amount'  => 0,
                'total'            => $total,
                'shipping_zone_id' => $draftOrder->shipping_zone_id,
                'payment_method'   => 'cod',
                'payment_status'   => 'unpaid',
                'status'           => 'pending',
                'source'           => 'draft_recovery',
                'admin_note'       => 'Recovered from Incomplete / Draft Order #' . $draftOrder->id,
            ]);

            foreach ($orderItems as $it) {
                $order->items()->create($it);
            }

            $draftOrder->update([
                'status'   => 'converted',
                'order_id' => $order->id,
            ]);

            DB::commit();

            return redirect()->route('admin.orders.show', $order->id)
                ->with('success', 'Draft order successfully converted to Official Order: ' . $order->order_number);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error converting draft order: ' . $e->getMessage());
        }
    }

    public function destroy(DraftOrder $draftOrder)
    {
        $draftOrder->delete();
        return back()->with('success', 'Draft order deleted successfully.');
    }
}

