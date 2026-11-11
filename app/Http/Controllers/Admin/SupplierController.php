<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::withCount('purchases')->latest();
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%");
        }
        $suppliers = $query->paginate(20)->withQueryString();
        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:50',
            'email'   => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
        ]);

        Supplier::create($request->only('name', 'company_name', 'phone', 'email', 'address'));
        return back()->with('success', 'Supplier created successfully!');
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:50',
            'email'   => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
        ]);

        $supplier->update($request->only('name', 'company_name', 'phone', 'email', 'address', 'is_active'));
        return back()->with('success', 'Supplier updated successfully!');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return back()->with('success', 'Supplier deleted successfully!');
    }
}
