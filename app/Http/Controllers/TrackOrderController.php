<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class TrackOrderController extends Controller
{
    public function index(Request $request)
    {
        $order = null;
        if ($request->filled('search')) {
            $search = trim($request->search);
            $order = Order::where('order_number', $search)
                ->orWhere('customer_phone', $search)
                ->with(['items.product', 'shippingZone'])
                ->latest()
                ->first();
        }

        return view('frontend.track_order', compact('order'));
    }
}
