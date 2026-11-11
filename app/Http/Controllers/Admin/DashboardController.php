<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\User;
use App\Models\Admin;
use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        // Sales Metrics
        $salesQuery = Order::whereNotIn('status', ['cancelled', 'returned']);
        $totalSales = (clone $salesQuery)->sum('total');
        $todaySales = (clone $salesQuery)->whereDate('created_at', $today)->sum('total');
        $monthSales = (clone $salesQuery)->where('created_at', '>=', $thisMonth)->sum('total');

        $posSales = (clone $salesQuery)->where('source', 'pos')->sum('total');
        $onlineSales = (clone $salesQuery)->where('source', '!=', 'pos')->sum('total');

        // Expense & Purchase
        $purchaseExpenses = Purchase::sum('total_amount');
        $totalExpenses = $purchaseExpenses;

        $totalIncome = $totalSales;
        $totalProfit = $totalIncome - $totalExpenses;
        $monthProfit = $monthSales;

        // Entities & Catalog Metrics
        $totalProducts   = Product::count();
        $totalCategories = Category::count();
        $totalCustomers  = User::count();

        // Orders Breakdown
        $totalOrders     = Order::count();
        $pendingOrders   = Order::where('status', 'pending')->count();
        $completedOrders = Order::whereIn('status', ['delivered', 'completed'])->count();

        // Recent orders
        $recentOrders = Order::with(['items', 'statusModel'])->latest()->take(10)->get();

        // Low stock products (stock <= 5 and > 0)
        $lowStockProducts = Product::with(['variants'])
            ->get()
            ->filter(fn ($p) => $p->total_stock <= 5 && $p->total_stock > 0)
            ->take(6);

        $outOfStockCount = Product::with(['variants'])
            ->get()
            ->filter(fn ($p) => $p->total_stock == 0)
            ->count();

        $lowStockCount = $lowStockProducts->count();

        // 7-day sales chart trend
        $salesData = [];
        $labels    = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->format('D');
            $q = Order::whereDate('created_at', $date)->whereNotIn('status', ['cancelled', 'returned']);
            $salesData[] = (float) $q->sum('total');
        }

        // Visitor Analytics
        $totalVisitors = Visitor::totalUniqueVisitors();
        $todayVisitors = Visitor::todayUniqueVisitors();
        $monthVisitors = Visitor::thisMonthUniqueVisitors();
        $dailyVisitors = Visitor::dailyStats(7);

        return view('admin.dashboard', compact(
            'totalSales', 'todaySales', 'monthSales', 'posSales', 'onlineSales',
            'totalExpenses', 'totalIncome', 'totalProfit', 'monthProfit',
            'totalProducts', 'totalCategories', 'totalCustomers',
            'totalOrders', 'pendingOrders', 'completedOrders',
            'recentOrders', 'lowStockProducts', 'lowStockCount', 'outOfStockCount',
            'salesData', 'labels',
            'totalVisitors', 'todayVisitors', 'monthVisitors', 'dailyVisitors'
        ));
    }
}
