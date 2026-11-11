@extends('layouts.admin')

@section('title', 'Sales & Revenue Report')
@section('breadcrumb', 'Sales Report')

@section('content')
<!-- Filter Card Bar -->
<div class="filter-card-bar">
    <form method="GET" action="{{ route('admin.reports.sales') }}" class="filter-left-group">
        <span style="font-size: 12px; font-weight: 700; color: #4B5563;">তারিখ ফিল্টার:</span>
        <input type="date" name="from" value="{{ $from->format('Y-m-d') }}" class="filter-search-input" style="width: 150px; padding-left: 10px;">
        <span style="color: #9CA3AF; font-size: 12px;">থেকে</span>
        <input type="date" name="to" value="{{ $to->format('Y-m-d') }}" class="filter-search-input" style="width: 150px; padding-left: 10px;">

        <button type="submit" class="btn-orange-search">
            <i class="fa-solid fa-filter"></i> রিপোর্ট দেখুন
        </button>
    </form>
</div>

<!-- Stat Cards (Sohoj Theme) -->
<div class="stat-cards-grid">
    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-green">
            <i class="fa-solid fa-bangladeshi-taka-sign"></i>
        </div>
        <div>
            <div class="stat-card-sub">Total Revenue</div>
            <div class="stat-card-val">৳{{ number_format($totalRevenue, 2) }}</div>
        </div>
    </div>

    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-orange">
            <i class="fa-solid fa-cart-shopping"></i>
        </div>
        <div>
            <div class="stat-card-sub">Total Orders</div>
            <div class="stat-card-val">{{ $totalOrders }}</div>
        </div>
    </div>

    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-blue">
            <i class="fa-solid fa-receipt"></i>
        </div>
        <div>
            <div class="stat-card-sub">Average Order Value</div>
            <div class="stat-card-val">৳{{ number_format($avgOrderValue, 2) }}</div>
        </div>
    </div>

    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-purple">
            <i class="fa-solid fa-shirt"></i>
        </div>
        <div>
            <div class="stat-card-sub">Garments Sold</div>
            <div class="stat-card-val">{{ $totalItems }} pcs</div>
        </div>
    </div>
</div>

<!-- Top Products Table (Exact Sohoj Excel-like Layout) -->
<div class="table-card-container">
    <div style="padding: 10px 14px; border-bottom: 1px solid var(--admin-border); background: #FFFFFF; font-size: 13px; font-weight: 700; color: #111827;">
        সর্বোচ্চ বিক্রিত সেরা ১০টি প্রোডাক্ট (Top 10 Selling Products)
    </div>
    <table class="admin-table">
        <thead>
            <tr>
                <th style="width: 45px; text-align: center;">#</th>
                <th>প্রোডাক্টের নাম</th>
                <th style="text-align: center;">মোট বিক্রি</th>
                <th style="text-align: right;">মোট আয় (Revenue)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topProducts as $idx => $top)
                <tr>
                    <td style="text-align: center; color: #9CA3AF; font-weight: 600;">
                        {{ $idx + 1 }}
                    </td>
                    <td>
                        <strong style="color: #111827; font-size: 12.5px;">{{ $top->product_name }}</strong>
                    </td>
                    <td style="text-align: center;">
                        <span class="badge-status badge-info">{{ $top->total_qty }} pcs</span>
                    </td>
                    <td style="text-align: right;">
                        <span class="tbl-price" style="color: var(--admin-primary);">৳{{ number_format($top->total_revenue, 2) }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #9CA3AF; padding: 36px;">
                        এই সময়সীমার মধ্যে কোন বিক্রির তথ্য নেই।
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
