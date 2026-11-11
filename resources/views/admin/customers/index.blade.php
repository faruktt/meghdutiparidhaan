@extends('layouts.admin')

@section('title', 'Customers')
@section('breadcrumb', 'Customers')

@section('content')
<!-- Top Stat Cards (Sohoj Theme) -->
<div class="stat-cards-grid">
    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-orange">
            <i class="fa-solid fa-users"></i>
        </div>
        <div>
            <div class="stat-card-sub">Total Customers</div>
            <div class="stat-card-val">{{ \App\Models\User::count() }}</div>
        </div>
    </div>

    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-green">
            <i class="fa-solid fa-user-check"></i>
        </div>
        <div>
            <div class="stat-card-sub">Active Buyers</div>
            <div class="stat-card-val">{{ \App\Models\User::has('orders')->count() }}</div>
        </div>
    </div>

    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-blue">
            <i class="fa-solid fa-cart-shopping"></i>
        </div>
        <div>
            <div class="stat-card-sub">Total Customer Orders</div>
            <div class="stat-card-val">{{ \App\Models\Order::count() }}</div>
        </div>
    </div>
</div>

<!-- Search & Filter Bar (Matching Sohoj ERP Screenshot) -->
<div class="filter-card-bar">
    <form method="GET" action="{{ route('admin.customers.index') }}" class="filter-left-group">
        <div class="search-input-wrap">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" name="search" value="{{ request('search') }}" class="filter-search-input" placeholder="কাস্টমার নাম, ফোন বা ইমেইল দিয়ে খুঁজুন...">
        </div>

        <button type="submit" class="btn-orange-search">
            <i class="fa-solid fa-magnifying-glass"></i> খুঁজুন
        </button>

        @if(request()->filled('search'))
            <a href="{{ route('admin.customers.index') }}" class="btn-admin-secondary" style="padding: 6px 12px; font-size: 11.5px; border-radius: 6px;">রিসেট</a>
        @endif
    </form>
</div>

<!-- Compact Customers Table (Exact Sohoj Excel-like Layout) -->
<div class="table-card-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th style="width: 45px; text-align: center;">#</th>
                <th>তারিখ</th>
                <th>কাস্টমার নাম</th>
                <th>ফোন নম্বর</th>
                <th>ইমেইল</th>
                <th style="text-align: center;">মোট অর্ডার</th>
                <th style="text-align: right; width: 80px;">অ্যাকশন</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customers as $index => $cust)
                <tr>
                    <td style="text-align: center; color: #9CA3AF; font-weight: 600;">
                        {{ $customers->firstItem() + $index }}
                    </td>
                    <td style="white-space: nowrap; color: #64748B;">
                        {{ $cust->created_at->format('d M, Y') }}
                    </td>
                    <td>
                        <strong style="color: #111827; font-size: 12.5px;">{{ $cust->name }}</strong>
                    </td>
                    <td>
                        <span style="color: #374151; font-weight: 500;">
                            <i class="fa-solid fa-phone" style="font-size: 10px; color: #9CA3AF; margin-right: 3px;"></i> {{ $cust->phone ?? '—' }}
                        </span>
                    </td>
                    <td style="color: #64748B;">
                        {{ $cust->email ?? '—' }}
                    </td>
                    <td style="text-align: center;">
                        <span class="badge-status badge-info">
                            {{ $cust->orders_count }} {{ $cust->orders_count == 1 ? 'order' : 'orders' }}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <div class="action-btns-wrap">
                            <a href="{{ route('admin.customers.show', $cust->id) }}" class="btn-tbl-action btn-tbl-view" title="View Profile & Orders">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #9CA3AF; padding: 36px;">
                        কোন কাস্টমার পাওয়া যায়নি।
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($customers->hasPages())
        <div style="padding: 12px 18px; border-top: 1px solid var(--admin-border); background: #FFFFFF;">
            {{ $customers->links() }}
        </div>
    @endif
</div>
@endsection
