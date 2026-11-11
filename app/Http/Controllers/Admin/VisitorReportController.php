<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitorReportController extends Controller
{
    public function index(Request $request)
    {
        $days = (int) $request->get('days', 30);
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // High-level statistics
        $totalVisitors = Visitor::totalUniqueVisitors();
        $todayVisitors = Visitor::todayUniqueVisitors();
        $yesterdayVisitors = Visitor::yesterdayUniqueVisitors();
        $thisMonthVisitors = Visitor::thisMonthUniqueVisitors();
        $totalPageViews = Visitor::totalPageViews();
        $todayPageViews = Visitor::todayPageViews();

        // Daily breakdown query
        $query = Visitor::selectRaw('
            visit_date, 
            COUNT(DISTINCT ip_address) as unique_visitors, 
            COUNT(*) as page_views,
            SUM(CASE WHEN device_type = "Mobile" THEN 1 ELSE 0 END) as mobile_views,
            SUM(CASE WHEN device_type = "Desktop" THEN 1 ELSE 0 END) as desktop_views,
            SUM(CASE WHEN device_type = "Tablet" THEN 1 ELSE 0 END) as tablet_views
        ');

        if ($startDate && $endDate) {
            $query->whereBetween('visit_date', [$startDate, $endDate]);
        } else {
            $query->where('visit_date', '>=', Carbon::today()->subDays($days)->toDateString());
        }

        $dailyStats = $query->groupBy('visit_date')
            ->orderBy('visit_date', 'desc')
            ->paginate(30)
            ->withQueryString();

        // Top Visited Pages
        $topPages = Visitor::select('page_url', DB::raw('COUNT(*) as views_count'), DB::raw('COUNT(DISTINCT ip_address) as unique_count'))
            ->groupBy('page_url')
            ->orderByDesc('views_count')
            ->take(8)
            ->get();

        // Device Breakdown
        $deviceStats = Visitor::select('device_type', DB::raw('COUNT(*) as total'))
            ->groupBy('device_type')
            ->get();

        // Browser Breakdown
        $browserStats = Visitor::select('browser', DB::raw('COUNT(*) as total'))
            ->groupBy('browser')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        // Recent Visitor Activity
        $recentVisitors = Visitor::latest()->take(25)->get();

        return view('admin.reports.visitors', compact(
            'totalVisitors',
            'todayVisitors',
            'yesterdayVisitors',
            'thisMonthVisitors',
            'totalPageViews',
            'todayPageViews',
            'dailyStats',
            'topPages',
            'deviceStats',
            'browserStats',
            'recentVisitors',
            'days',
            'startDate',
            'endDate'
        ));
    }
}

