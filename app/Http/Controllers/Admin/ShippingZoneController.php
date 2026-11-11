<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingZone;
use Illuminate\Http\Request;

class ShippingZoneController extends Controller
{
    public function index()
    {
        $zones = ShippingZone::orderBy('sort_order')->get();
        return view('admin.shipping.index', compact('zones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'charge'     => 'required|numeric|min:0',
            'sort_order' => 'nullable|integer',
        ]);

        ShippingZone::create([
            'name'       => $request->name,
            'charge'     => $request->charge,
            'is_active'  => $request->boolean('is_active', true),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return back()->with('success', 'Shipping zone added successfully!');
    }

    public function update(Request $request, ShippingZone $shipping)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'charge'     => 'required|numeric|min:0',
            'sort_order' => 'nullable|integer',
        ]);

        $shipping->update([
            'name'       => $request->name,
            'charge'     => $request->charge,
            'is_active'  => $request->boolean('is_active', true),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return back()->with('success', 'Shipping zone updated successfully!');
    }

    public function destroy(ShippingZone $shipping)
    {
        $shipping->delete();
        return back()->with('success', 'Shipping zone deleted successfully!');
    }
}
