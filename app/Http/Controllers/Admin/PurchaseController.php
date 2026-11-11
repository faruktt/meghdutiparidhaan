<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::with('supplier')->latest();
        if ($request->filled('supplier')) {
            $query->where('supplier_id', $request->supplier);
        }
        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }
        $purchases = $query->paginate(20)->withQueryString();
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        return view('admin.purchases.index', compact('purchases', 'suppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        $products  = Product::with(['variants.color', 'variants.size'])
            ->where('is_active', true)->orderBy('name')->get();
        return view('admin.purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id'     => 'nullable|exists:suppliers,id',
            'purchase_date'   => 'required|date',
            'items'           => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'items.*.cost_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $total = 0;
            $itemsData = [];

            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                $subtotal = $item['cost_price'] * $item['quantity'];
                $total += $subtotal;

                $variantInfo = null;
                if (!empty($item['product_variant_id'])) {
                    $variant = ProductVariant::find($item['product_variant_id']);
                    $variantInfo = $variant?->display_info;
                    // Increase stock
                    if ($variant) {
                        $variant->increment('stock', $item['quantity']);
                    }
                }

                $itemsData[] = [
                    'product_id'         => $item['product_id'],
                    'product_variant_id' => $item['product_variant_id'] ?? null,
                    'product_name'       => $product->name,
                    'variant_info'       => $variantInfo,
                    'cost_price'         => $item['cost_price'],
                    'quantity'           => $item['quantity'],
                    'subtotal'           => $subtotal,
                ];
            }

            $purchase = Purchase::create([
                'purchase_number' => Purchase::generatePurchaseNumber(),
                'supplier_id'     => $request->supplier_id,
                'total_amount'    => $total,
                'paid_amount'     => $request->paid_amount ?? 0,
                'payment_status'  => $request->payment_status ?? 'unpaid',
                'status'          => 'received',
                'purchase_date'   => $request->purchase_date,
                'note'            => $request->note,
            ]);

            foreach ($itemsData as $item) {
                $purchase->items()->create($item);
            }

            DB::commit();
            return redirect()->route('admin.purchases.index')
                ->with('success', 'Purchase recorded and stock updated!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'items.product', 'items.variant.color', 'items.variant.size']);
        return view('admin.purchases.show', compact('purchase'));
    }
}
