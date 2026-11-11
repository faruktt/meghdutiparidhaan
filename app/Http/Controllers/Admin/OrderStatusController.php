<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderStatusController extends Controller
{
    public function index()
    {
        $statuses = OrderStatus::withCount('orders')->orderBy('sort_order')->get();
        return view('admin.order_statuses.index', compact('statuses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'color'      => 'nullable|string|max:20',
            'bg_color'   => 'nullable|string|max:20',
            'row_bg'     => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer',
        ]);

        $slug = Str::slug($request->name);
        $base = $slug;
        $c = 1;
        while (OrderStatus::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $c++;
        }

        OrderStatus::create([
            'name'       => $request->name,
            'slug'       => $slug,
            'color'      => $request->color ?: '#4B5563',
            'bg_color'   => $request->bg_color ?: '#F3F4F6',
            'row_bg'     => $request->row_bg ?: '#FFFFFF',
            'sort_order' => $request->sort_order ?? (OrderStatus::count() + 1),
            'is_active'  => true,
            'is_default' => false,
        ]);

        return back()->with('success', 'Order status "' . $request->name . '" created successfully!');
    }

    public function update(Request $request, OrderStatus $orderStatus)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'color'      => 'nullable|string|max:20',
            'bg_color'   => 'nullable|string|max:20',
            'row_bg'     => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer',
        ]);

        $orderStatus->update([
            'name'       => $request->name,
            'color'      => $request->color ?: '#4B5563',
            'bg_color'   => $request->bg_color ?: '#F3F4F6',
            'row_bg'     => $request->row_bg ?: '#FFFFFF',
            'sort_order' => $request->sort_order ?? 0,
            'is_active'  => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Order status updated successfully!');
    }

    public function destroy(OrderStatus $orderStatus)
    {
        if ($orderStatus->is_default) {
            return back()->with('error', 'Default status cannot be deleted.');
        }

        $orderCount = Order::where('status', $orderStatus->slug)->count();
        if ($orderCount > 0) {
            return back()->with('error', "Cannot delete status because {$orderCount} order(s) currently use it.");
        }

        $orderStatus->delete();
        return back()->with('success', 'Order status deleted successfully!');
    }
}
