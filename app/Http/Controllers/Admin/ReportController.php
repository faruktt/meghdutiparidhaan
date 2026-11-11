<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $from = $request->from ? Carbon::parse($request->from)->startOfDay() : Carbon::now()->startOfMonth();
        $to   = $request->to   ? Carbon::parse($request->to)->endOfDay()     : Carbon::now()->endOfDay();

        $ordersQuery = Order::with(['items'])
            ->whereBetween('created_at', [$from, $to])
            ->whereNotIn('status', ['cancelled', 'returned']);

        $orders = $ordersQuery->get();

        $totalRevenue  = $orders->sum('total');
        $totalOrders   = $orders->count();
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        $totalItems    = $orders->sum(fn ($o) => $o->items->sum('quantity'));

        // Daily breakdown
        $dailySales = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as orders'),
            DB::raw('SUM(total) as revenue')
        )
        ->whereBetween('created_at', [$from, $to])
        ->whereNotIn('status', ['cancelled', 'returned'])
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        // Top products
        $topProducts = OrderItem::select(
            'product_id', 'product_name',
            DB::raw('SUM(quantity) as total_qty'),
            DB::raw('SUM(subtotal) as total_revenue')
        )
        ->whereHas('order', function ($q) use ($from, $to) {
            $q->whereBetween('created_at', [$from, $to])
              ->whereNotIn('status', ['cancelled', 'returned']);
        })
        ->groupBy('product_id', 'product_name')
        ->orderByDesc('total_revenue')
        ->take(10)
        ->get();

        return view('admin.reports.sales', compact(
            'totalRevenue', 'totalOrders', 'avgOrderValue', 'totalItems',
            'dailySales', 'topProducts', 'from', 'to'
        ));
    }

    public function stock(Request $request)
    {
        $query = Product::with(['category', 'variants.color', 'variants.size']);

        // Search by Product Name, SKU, or Variant SKU
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhereHas('variants', function ($vq) use ($search) {
                      $vq->where('sku', 'like', "%{$search}%");
                  });
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $category = Category::with('children')->find($request->category);
            if ($category) {
                $catIds = [$category->id];
                if ($category->children && $category->children->count() > 0) {
                    $catIds = array_merge($catIds, $category->children->pluck('id')->toArray());
                }
                $query->where(function ($q) use ($catIds) {
                    $q->whereIn('category_id', $catIds)
                      ->orWhereIn('subcategory_id', $catIds);
                });
            }
        }

        // Stock Status filter (low, out, in_stock)
        $stockFilter = $request->stock_status ?? $request->filter;
        if ($stockFilter === 'low') {
            $query->whereHas('variants', fn ($q) => $q->where('stock', '>', 0)->where('stock', '<=', 5));
        } elseif ($stockFilter === 'out') {
            $query->whereDoesntHave('variants', fn ($q) => $q->where('stock', '>', 0));
        } elseif ($stockFilter === 'in_stock') {
            $query->whereHas('variants', fn ($q) => $q->where('stock', '>', 5));
        }

        // Sorting
        $sort = $request->get('sort', 'stock_desc');
        switch ($sort) {
            case 'stock_asc':
                $query->withSum('variants as total_variant_stock', 'stock')
                      ->orderBy('total_variant_stock', 'asc');
                break;
            case 'stock_desc':
                $query->withSum('variants as total_variant_stock', 'stock')
                      ->orderByDesc('total_variant_stock');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'value_desc':
                $query->withSum('variants as total_variant_stock', 'stock')
                      ->orderByRaw('(SELECT COALESCE(SUM(stock), 0) FROM product_variants WHERE product_variants.product_id = products.id) * products.regular_price DESC');
                break;
            case 'value_asc':
                $query->withSum('variants as total_variant_stock', 'stock')
                      ->orderByRaw('(SELECT COALESCE(SUM(stock), 0) FROM product_variants WHERE product_variants.product_id = products.id) * products.regular_price ASC');
                break;
            default:
                $query->withSum('variants as total_variant_stock', 'stock')
                      ->orderByDesc('total_variant_stock');
                break;
        }

        $products = $query->paginate(30)->withQueryString();

        // Overall Global Statistics (All warehouse items & variants)
        $totalProducts      = Product::count();
        $totalStockQuantity = (int) ProductVariant::sum('stock');
        $totalStockValue    = (float) ProductVariant::join('products', 'product_variants.product_id', '=', 'products.id')
            ->sum(DB::raw('product_variants.stock * products.regular_price'));

        $outOfStock = Product::get()->filter(fn ($p) => $p->total_stock === 0)->count();
        $lowStock   = Product::get()->filter(fn ($p) => $p->total_stock > 0 && $p->total_stock <= 5)->count();

        // Active Categories for the filter dropdown
        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->with(['children' => fn ($q) => $q->where('is_active', true)->orderBy('name')])
            ->orderBy('name')
            ->get();

        return view('admin.reports.stock', compact(
            'products',
            'totalProducts',
            'totalStockQuantity',
            'totalStockValue',
            'outOfStock',
            'lowStock',
            'categories'
        ));
    }

    public function purchase(Request $request)
    {
        $from = $request->from ? Carbon::parse($request->from)->startOfDay() : Carbon::now()->startOfMonth();
        $to   = $request->to   ? Carbon::parse($request->to)->endOfDay()     : Carbon::now()->endOfDay();

        $purchases = Purchase::with(['supplier', 'items'])
            ->whereBetween('created_at', [$from, $to])
            ->latest()
            ->get();

        $totalPurchaseAmount = $purchases->sum('total_amount');
        $totalPaid           = $purchases->sum('paid_amount');
        $totalDue            = $totalPurchaseAmount - $totalPaid;

        return view('admin.reports.purchase', compact(
            'purchases', 'totalPurchaseAmount', 'totalPaid', 'totalDue', 'from', 'to'
        ));
    }
}
