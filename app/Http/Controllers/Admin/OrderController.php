<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['items', 'statusModel'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('payment')) {
            $query->where('payment_method', $request->payment);
        }
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $orders   = $query->paginate(20)->withQueryString();
        $statuses = Order::getStatuses();

        return view('admin.orders.index', compact('orders', 'statuses'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'items.variant.color', 'items.variant.size', 'shippingZone', 'statusModel']);
        $statuses = Order::getStatuses();
        return view('admin.orders.show', compact('order', 'statuses'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $allowed = array_keys(Order::getStatuses());
        $request->validate(['status' => 'required|in:' . implode(',', $allowed)]);

        $data = ['status' => $request->status];

        // Set timestamps
        if ($request->status === 'confirmed' && !$order->confirmed_at) {
            $data['confirmed_at'] = now();
        }
        if ($request->status === 'shipped' && !$order->shipped_at) {
            $data['shipped_at'] = now();
        }
        if ($request->status === 'delivered' && !$order->delivered_at) {
            $data['delivered_at'] = now();
            $data['payment_status'] = 'paid';
        }

        if ($request->filled('admin_note')) {
            $data['admin_note'] = $request->admin_note;
        }

        $order->update($data);

        return back()->with('success', 'Order status updated to ' . ($order->status_label) . '!');
    }

    public function invoice(Order $order)
    {
        $order->load(['items.product', 'items.variant.color', 'items.variant.size', 'shippingZone']);
        return view('admin.orders.invoice', compact('order'));
    }

    public function sendToCourier(Request $request, Order $order, \App\Services\CourierService $courierService)
    {
        $request->validate([
            'customer_name'    => 'required|string|max:255',
            'customer_phone'   => 'required|string|max:30',
            'customer_address' => 'required|string|max:500',
            'cod_amount'       => 'required|numeric|min:0',
            'note'             => 'nullable|string|max:255',
            'courier_name'     => 'nullable|string|in:steadfast,pathao,redx',
        ]);

        $result = $courierService->dispatchOrder($order, $request->all());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($result);
        }

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }

    public function checkPhoneFraud(Request $request, \App\Services\CourierService $courierService)
    {
        $request->validate([
            'phone' => 'required|string',
        ]);

        $result = $courierService->checkFraud($request->phone);

        return response()->json($result);
    }

    public function destroy(Order $order)
    {
        $orderNumber = $order->order_number;

        DB::transaction(function () use ($order) {
            // Restore product variant stock if order was not cancelled or returned
            if (!in_array($order->status, ['cancelled', 'returned'])) {
                foreach ($order->items as $item) {
                    if ($item->variant_id) {
                        ProductVariant::where('id', $item->variant_id)->increment('stock', $item->quantity);
                    }
                }
            }

            // Unlink any draft orders referencing this order
            \App\Models\DraftOrder::where('order_id', $order->id)->update(['order_id' => null]);

            // Delete the order (order_items are cascade deleted by DB foreign key)
            $order->delete();
        });

        return redirect()->route('admin.orders.index')->with('success', 'অর্ডার #' . $orderNumber . ' সফলভাবে ডিলিট করা হয়েছে।');
    }
}
