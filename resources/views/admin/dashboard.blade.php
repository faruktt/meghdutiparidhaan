@extends('layouts.admin')

@section('title', 'Executive Dashboard')
@section('breadcrumb', 'Dashboard')

@push('styles')
<style>
    .dash-row-4 {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 16px;
    }
    .dash-main-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.8fr) minmax(320px, 1.2fr);
        gap: 18px;
        align-items: start;
    }
    @media (max-width: 1100px) {
        .dash-row-4 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        .dash-main-grid {
            grid-template-columns: 1fr;
        }
    }
    @media (max-width: 640px) {
        .dash-row-4 {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<!-- Top Header Action & Quick Info Bar -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
    <div>
        <h2 style="font-size: 18px; font-weight: 800; color: #0F172A; margin: 0; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-chart-pie" style="color: var(--admin-primary);"></i> Meghduti Paridhaan - Store Dashboard
        </h2>
        <div style="font-size: 12px; color: #64748B; margin-top: 2px;">
            Overview of Sales, Revenue, Orders and Inventory
        </div>
    </div>

    <div style="display: flex; align-items: center; gap: 8px;">
        <span style="background: #F1F5F9; border: 1px solid #E2E8F0; color: #475569; padding: 6px 12px; border-radius: 6px; font-size: 11.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa-regular fa-calendar" style="color: #64748B;"></i> {{ date('d M, Y') }}
        </span>
        <a href="{{ route('admin.reports.sales') }}" class="btn-admin-secondary" style="font-size: 12px; padding: 6px 12px; height: 34px;">
            <i class="fa-solid fa-chart-line"></i> Sales Report
        </a>
        <a href="{{ route('admin.pos.index') }}" class="btn-orange-add">
            <i class="fa-solid fa-cash-register"></i> POS / New Order
        </a>
    </div>
</div>

<!-- Row 1: Sales & Financial Metrics -->
<div class="dash-row-4">
    <!-- Total Sales -->
    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-green">
            <i class="fa-solid fa-chart-line"></i>
        </div>
        <div>
            <div class="stat-card-sub">Total Sales</div>
            <div class="stat-card-val" style="color: #16A34A;">৳{{ number_format($totalSales, 2) }}</div>
        </div>
    </div>

    <!-- Today Sales -->
    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-green">
            <i class="fa-solid fa-calendar-day"></i>
        </div>
        <div>
            <div class="stat-card-sub">Today's Sales</div>
            <div class="stat-card-val">৳{{ number_format($todaySales, 2) }}</div>
        </div>
    </div>

    <!-- This Month Sales -->
    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-blue">
            <i class="fa-solid fa-calendar-days"></i>
        </div>
        <div>
            <div class="stat-card-sub">This Month Sales</div>
            <div class="stat-card-val" style="color: #2563EB;">৳{{ number_format($monthSales, 2) }}</div>
        </div>
    </div>

    <!-- Total Orders -->
    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-orange">
            <i class="fa-solid fa-cart-shopping"></i>
        </div>
        <div>
            <div class="stat-card-sub">Total Orders</div>
            <div class="stat-card-val">
                {{ $totalOrders }}
                @if($pendingOrders > 0)
                    <span style="font-size: 10.5px; color: #EA580C; font-weight: 700; margin-left: 2px;">({{ $pendingOrders }} pending)</span>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Row 2: Catalog & Performance -->
<div class="dash-row-4">
    <!-- Total Products -->
    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-purple">
            <i class="fa-solid fa-shirt"></i>
        </div>
        <div>
            <div class="stat-card-sub">Total Products</div>
            <div class="stat-card-val">
                {{ $totalProducts }}
                @if($lowStockCount > 0)
                    <span style="font-size: 10.5px; color: #EA580C; font-weight: 700; margin-left: 2px;">({{ $lowStockCount }} low)</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Total Categories -->
    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-orange">
            <i class="fa-solid fa-tags"></i>
        </div>
        <div>
            <div class="stat-card-sub">Total Categories</div>
            <div class="stat-card-val">{{ $totalCategories }}</div>
        </div>
    </div>

    <!-- Total Customers -->
    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-blue">
            <i class="fa-solid fa-users"></i>
        </div>
        <div>
            <div class="stat-card-sub">Registered Customers</div>
            <div class="stat-card-val">{{ $totalCustomers }}</div>
        </div>
    </div>

    <!-- Completed Orders -->
    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-green">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div>
            <div class="stat-card-sub">Completed Orders</div>
            <div class="stat-card-val" style="color: #16A34A;">{{ $completedOrders }}</div>
        </div>
    </div>
</div>

<!-- Row 3: Live Visitor Analytics (ওয়েবসাইট ভিজিটর হিসাব) -->
<div class="dash-row-4">
    <!-- Total Unique Visitors -->
    <div class="stat-card-compact">
        <div class="stat-icon-wrap" style="background: #EFF6FF; color: #2563EB;">
            <i class="fa-solid fa-users"></i>
        </div>
        <div>
            <div class="stat-card-sub">সর্বমোট ইউনিক ভিজিটর</div>
            <div class="stat-card-val" style="color: #2563EB;">{{ number_format($totalVisitors) }}</div>
        </div>
    </div>

    <!-- Today's Visitors -->
    <div class="stat-card-compact">
        <div class="stat-icon-wrap" style="background: #ECFDF5; color: #059669;">
            <i class="fa-solid fa-user-check"></i>
        </div>
        <div>
            <div class="stat-card-sub">আজকের ভিজিটর (Today)</div>
            <div class="stat-card-val" style="color: #059669;">{{ number_format($todayVisitors) }}</div>
        </div>
    </div>

    <!-- This Month Visitors -->
    <div class="stat-card-compact">
        <div class="stat-icon-wrap" style="background: #F3E8FF; color: #7C3AED;">
            <i class="fa-solid fa-calendar-days"></i>
        </div>
        <div>
            <div class="stat-card-sub">চলতি মাসের ভিজিটর</div>
            <div class="stat-card-val" style="color: #7C3AED;">{{ number_format($monthVisitors) }}</div>
        </div>
    </div>

    <!-- Visitor Report Shortcut -->
    <a href="{{ route('admin.reports.visitors') }}" class="stat-card-compact" style="text-decoration: none; border-color: #DBEAFE; background: #F8FAFC; transition: all 0.2s;" onmouseover="this.style.background='#EFF6FF'" onmouseout="this.style.background='#F8FAFC'">
        <div class="stat-icon-wrap" style="background: #E0E7FF; color: #4F46E5;">
            <i class="fa-solid fa-chart-pie"></i>
        </div>
        <div>
            <div class="stat-card-sub" style="color: #4F46E5; font-weight: 700;">ভিজিটর রিপোর্ট ও গ্রাফ</div>
            <div style="font-size: 13px; font-weight: 800; color: #1E293B; margin-top: 2px;">
                দিনভিত্তিক রিপোর্ট দেখুন →
            </div>
        </div>
    </a>
</div>

<!-- Main Section: Left Tables & Right Highlights -->
<div class="dash-main-grid">
    
    <!-- Left Column: Recent Orders Table -->
    <div style="display: flex; flex-direction: column; gap: 18px;">
        <div class="table-card-container" style="margin-bottom: 0;">
            <div style="padding: 12px 16px; border-bottom: 1px solid var(--admin-border); background: #FFFFFF; display: flex; justify-content: space-between; align-items: center;">
                <strong style="color: #111827; font-size: 13.5px; display: flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-cart-shopping" style="color: var(--admin-primary);"></i> Recent Orders
                </strong>
                <a href="{{ route('admin.orders.index') }}" class="btn-orange-search" style="font-size: 11px; padding: 4px 12px; height: 28px;">
                    View All Orders
                </a>
            </div>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Source</th>
                        <th>Customer</th>
                        <th style="text-align: right;">Amount</th>
                        <th style="text-align: center;">Status</th>
                        <th style="text-align: right; width: 75px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr style="background-color: {{ $order->status_row_bg }};">
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="invoice-orange">
                                    {{ $order->order_number }}
                                </a>
                                <div style="font-size: 10px; color: #94A3B8;">{{ $order->created_at->diffForHumans() }}</div>
                            </td>
                            <td>
                                @if($order->source === 'pos')
                                    <span class="badge" style="background: #FFF7ED; color: #EA580C; font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 4px;">
                                        POS Order
                                    </span>
                                @else
                                    <span class="badge" style="background: #EFF6FF; color: #2563EB; font-size: 10px; font-weight: 600; padding: 2px 6px; border-radius: 4px;">
                                        Online Store
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight: 700; color: #111827; font-size: 12px;">{{ $order->customer_name }}</div>
                                <div style="font-size: 10.5px; color: #64748B;">{{ $order->customer_phone }}</div>
                            </td>
                            <td style="text-align: right;">
                                <span style="font-weight: 800; color: #111827; font-size: 12px;">৳{{ number_format($order->total, 2) }}</span>
                            </td>
                            <td style="text-align: center;">
                                <span class="badge-status" style="background: {{ $order->status_badge_bg }}; color: {{ $order->status_badge_color }}; font-weight: 700; font-size: 10.5px;">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <div class="action-btns-wrap" style="justify-content: flex-end;">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn-tbl-action btn-tbl-view" title="View Order">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" class="btn-tbl-action btn-tbl-invoice" title="Invoice">
                                        <i class="fa-solid fa-print"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: #94A3B8; padding: 25px;">No orders recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Daily Visitor Breakdown (Recent 7 Days) -->
        <div class="table-card-container" style="margin-bottom: 0;">
            <div style="padding: 12px 16px; border-bottom: 1px solid var(--admin-border); background: #FFFFFF; display: flex; justify-content: space-between; align-items: center;">
                <strong style="color: #111827; font-size: 13.5px; display: flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-users-viewfinder" style="color: #2563EB;"></i> দিনভিত্তিক ভিজিটর রিপোর্ট (Daily Visitors - Last 7 Days)
                </strong>
                <a href="{{ route('admin.reports.visitors') }}" class="btn-orange-search" style="font-size: 11px; padding: 4px 12px; height: 28px; background: #2563EB; border-color: #2563EB;">
                    বিস্তারিত রিপোর্ট দেখুন →
                </a>
            </div>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>তারিখ (Date)</th>
                        <th style="text-align: center;">ইউনিক ভিজিটর (Visitors)</th>
                        <th style="text-align: center;">পেজ ভিউ (Page Views)</th>
                        <th style="text-align: right;">ডিভাইস (Mobile / Desktop)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dailyVisitors as $v)
                        @php
                            $cDate = \Carbon\Carbon::parse($v->visit_date);
                            $isToday = $cDate->isToday();
                            $isYesterday = $cDate->isYesterday();
                        @endphp
                        <tr>
                            <td>
                                <strong style="color: #0F172A; font-size: 12.5px;">{{ $cDate->format('d M, Y') }}</strong>
                                <span style="font-size: 11px; color: #64748B;">({{ $cDate->format('l') }})</span>
                                @if($isToday)
                                    <span style="background: #DCFCE7; color: #15803D; font-size: 10px; padding: 1px 5px; border-radius: 4px; font-weight: 800; margin-left: 4px;">আজ</span>
                                @elseif($isYesterday)
                                    <span style="background: #FEF3C7; color: #B45309; font-size: 10px; padding: 1px 5px; border-radius: 4px; font-weight: 800; margin-left: 4px;">গতকাল</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <span style="background: #EFF6FF; color: #1D4ED8; font-weight: 800; font-size: 12.5px; padding: 2px 10px; border-radius: 99px;">
                                    <i class="fa-solid fa-user" style="font-size: 10px;"></i> {{ number_format($v->unique_visitors) }} জন
                                </span>
                            </td>
                            <td style="text-align: center; font-weight: 700; color: #334155;">
                                {{ number_format($v->page_views) }} ভিউ
                            </td>
                            <td style="text-align: right; font-size: 11.5px; color: #475569;">
                                <i class="fa-solid fa-mobile-screen" style="color: #2563EB;"></i> {{ $v->mobile_views }}
                                <span style="color: #CBD5E1; margin: 0 4px;">|</span>
                                <i class="fa-solid fa-laptop" style="color: #059669;"></i> {{ $v->desktop_views }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: #94A3B8; padding: 20px;">এখনো কোন ভিজিটর রেকর্ড নেই।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Right Column: Financial Overview, Low Stock & Shortcuts -->
    <div style="display: flex; flex-direction: column; gap: 16px;">
        
        <!-- Financial Summary Breakdown Card -->
        <div class="card" style="padding: 16px; margin-bottom: 0; border-radius: 10px;">
            <div style="border-bottom: 1px solid #E2E8F0; padding-bottom: 8px; margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center;">
                <strong style="font-size: 13px; color: #111827; display: flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-wallet" style="color: #16A34A;"></i> Sales Channels Breakdown
                </strong>
            </div>

            <div style="display: flex; flex-direction: column; gap: 8px; font-size: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 6px; border-bottom: 1px dashed #F1F5F9;">
                    <span style="color: #64748B;">POS In-Store Sales:</span>
                    <strong style="color: #16A34A;">৳{{ number_format($posSales, 2) }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 6px; border-bottom: 1px dashed #F1F5F9;">
                    <span style="color: #64748B;">Online Store Sales:</span>
                    <strong style="color: #2563EB;">৳{{ number_format($onlineSales, 2) }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 6px; border-bottom: 1px dashed #F1F5F9;">
                    <span style="color: #64748B;">Supplier Purchases:</span>
                    <strong style="color: #DC2626;">৳{{ number_format($totalExpenses, 2) }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 4px; font-size: 13px;">
                    <strong style="color: #111827;">Total Store Revenue:</strong>
                    <strong style="font-size: 14px; color: #16A34A;">
                        ৳{{ number_format($totalSales, 2) }}
                    </strong>
                </div>
            </div>
        </div>

        <!-- Low Stock Alerts Card -->
        <div class="card" style="padding: 16px; margin-bottom: 0; border-radius: 10px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; padding-bottom: 8px; border-bottom: 1px solid var(--admin-border);">
                <strong style="color: #111827; font-size: 13px; display: flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-triangle-exclamation" style="color: #EA580C;"></i> Low Stock Alerts
                </strong>
                <a href="{{ route('admin.reports.stock', ['filter' => 'low']) }}" class="btn-admin-secondary" style="font-size: 10.5px; padding: 2px 8px; height: 24px;">
                    Stock Report
                </a>
            </div>

            @if($lowStockProducts->count() > 0)
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    @foreach($lowStockProducts as $p)
                        <div style="display: flex; align-items: center; justify-content: space-between; padding-bottom: 6px; border-bottom: 1px dashed #F1F5F9;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 28px; height: 28px; border-radius: 4px; background: #F8FAFC; overflow: hidden; display: flex; align-items: center; justify-content: center; border: 1px solid #E2E8F0;">
                                    @if($p->thumbnail)
                                        <img src="{{ $p->thumbnail_url }}" alt="{{ $p->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <i class="fa-solid fa-shirt" style="font-size: 12px; color: #94A3B8;"></i>
                                    @endif
                                </div>
                                <div>
                                    <div style="font-weight: 700; font-size: 11.5px; color: #111827; max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $p->name }}</div>
                                    <div style="font-size: 10px; color: #94A3B8;">SKU: {{ $p->sku ?? '—' }}</div>
                                </div>
                            </div>
                            <span class="badge-status badge-danger" style="font-size: 10px; padding: 1px 6px;">
                                {{ $p->total_stock }} left
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; color: #16A34A; padding: 18px 0;">
                    <i class="fa-solid fa-circle-check" style="font-size: 24px; margin-bottom: 4px;"></i>
                    <div style="font-weight: 700; font-size: 12px;">All Products Stock Healthy</div>
                </div>
            @endif
        </div>

        <!-- Quick ERP Shortcuts -->
        <div class="card" style="padding: 16px; margin-bottom: 0; border-radius: 10px;">
            <div style="border-bottom: 1px solid #E2E8F0; padding-bottom: 8px; margin-bottom: 10px;">
                <strong style="font-size: 13px; color: #111827; display: flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-bolt" style="color: #EA580C;"></i> Quick Shortcuts
                </strong>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                <a href="{{ route('admin.pos.index') }}" style="display: flex; align-items: center; gap: 6px; padding: 8px; border-radius: 6px; background: #FFF7ED; border: 1px solid #FFEDD5; color: #EA580C; font-size: 11.5px; font-weight: 700; text-decoration: none;">
                    <i class="fa-solid fa-cash-register"></i> POS Terminal
                </a>
                <a href="{{ route('admin.products.create') }}" style="display: flex; align-items: center; gap: 6px; padding: 8px; border-radius: 6px; background: #EFF6FF; border: 1px solid #DBEAFE; color: #2563EB; font-size: 11.5px; font-weight: 700; text-decoration: none;">
                    <i class="fa-solid fa-plus"></i> Add Product
                </a>
                <a href="{{ route('admin.reports.sales') }}" style="display: flex; align-items: center; gap: 6px; padding: 8px; border-radius: 6px; background: #F0FDF4; border: 1px solid #DCFCE7; color: #16A34A; font-size: 11.5px; font-weight: 700; text-decoration: none;">
                    <i class="fa-solid fa-chart-line"></i> Sales Report
                </a>
                <a href="{{ route('admin.staff.index') }}" style="display: flex; align-items: center; gap: 6px; padding: 8px; border-radius: 6px; background: #FAF5FF; border: 1px solid #F3E8FF; color: #7C3AED; font-size: 11.5px; font-weight: 700; text-decoration: none;">
                    <i class="fa-solid fa-user-shield"></i> Staff & Roles
                </a>
            </div>
        </div>

    </div>

</div>
@endsection
