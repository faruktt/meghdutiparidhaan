@extends('layouts.admin')

@section('title', 'Purchases')
@section('breadcrumb', 'Purchases')

@section('content')
<!-- Top Stat Cards (Matching Sohoj Theme) -->
<div class="stat-cards-grid">
    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-orange">
            <i class="fa-solid fa-receipt"></i>
        </div>
        <div>
            <div class="stat-card-sub">Today's Purchases</div>
            <div class="stat-card-val">৳{{ number_format(\App\Models\Purchase::whereDate('purchase_date', today())->sum('total_amount'), 2) }}</div>
        </div>
    </div>

    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-green">
            <i class="fa-solid fa-calendar-check"></i>
        </div>
        <div>
            <div class="stat-card-sub">This Month Purchases</div>
            <div class="stat-card-val">৳{{ number_format(\App\Models\Purchase::whereMonth('purchase_date', now()->month)->whereYear('purchase_date', now()->year)->sum('total_amount'), 2) }}</div>
        </div>
    </div>

    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-purple">
            <i class="fa-solid fa-file-invoice"></i>
        </div>
        <div>
            <div class="stat-card-sub">Total Invoices</div>
            <div class="stat-card-val">{{ \App\Models\Purchase::count() }}</div>
        </div>
    </div>
</div>

<!-- Search & Filter Bar (Matching Sohoj ERP Screenshot) -->
<div class="filter-card-bar">
    <form method="GET" action="{{ route('admin.purchases.index') }}" class="filter-left-group">
        <div class="search-input-wrap">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" name="search" value="{{ request('search') }}" class="filter-search-input" placeholder="ইনভয়েস # বা নোট দিয়ে খুঁজুন...">
        </div>

        <select name="supplier" class="filter-select">
            <option value="">সব সাপ্লায়ার</option>
            @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}" {{ request('supplier') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
            @endforeach
        </select>

        <select name="status" class="filter-select">
            <option value="">সব পেমেন্ট স্ট্যাটাস</option>
            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid (পরিশোধিত)</option>
            <option value="partial" {{ request('status') === 'partial' ? 'selected' : '' }}>Partial (আংশিক)</option>
            <option value="unpaid" {{ request('status') === 'unpaid' ? 'selected' : '' }}>Unpaid (বাকি)</option>
        </select>

        <button type="submit" class="btn-orange-search">
            <i class="fa-solid fa-magnifying-glass"></i> খুঁজুন
        </button>

        @if(request()->anyFilled(['search', 'supplier', 'status']))
            <a href="{{ route('admin.purchases.index') }}" class="btn-admin-secondary" style="padding: 6px 12px; font-size: 11.5px; border-radius: 6px;">রিসেট</a>
        @endif
    </form>

    <a href="{{ route('admin.purchases.create') }}" class="btn-orange-add">
        <i class="fa-solid fa-plus"></i> নতুন পারচেজ
    </a>
</div>

<!-- Compact Purchases Table (Exact Sohoj Excel-like Layout) -->
<div class="table-card-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th style="width: 45px; text-align: center;">#</th>
                <th>তারিখ</th>
                <th>ইনভয়েস #</th>
                <th>সাপ্লায়ার</th>
                <th style="text-align: center;">আইটেম</th>
                <th>মোট টাকা</th>
                <th>পেমেন্ট স্ট্যাটাস</th>
                <th style="text-align: right; width: 80px;">অ্যাকশন</th>
            </tr>
        </thead>
        <tbody>
            @forelse($purchases as $index => $purchase)
                <tr>
                    <td style="text-align: center; color: #9CA3AF; font-weight: 600;">
                        {{ $purchases->firstItem() + $index }}
                    </td>
                    <td style="white-space: nowrap; color: #64748B;">
                        {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M, Y') }}
                    </td>
                    <td>
                        <a href="{{ route('admin.purchases.show', $purchase->id) }}" class="invoice-orange">
                            {{ $purchase->purchase_number }}
                        </a>
                    </td>
                    <td style="font-weight: 600; color: #111827;">
                        {{ $purchase->supplier?->name ?? 'Direct Purchase' }}
                    </td>
                    <td style="text-align: center;">
                        <span class="badge-status badge-neutral">
                            {{ $purchase->items->count() }} item{{ $purchase->items->count() > 1 ? 's' : '' }}
                        </span>
                    </td>
                    <td>
                        <span class="tbl-price">৳{{ number_format($purchase->total_amount, 2) }}</span>
                    </td>
                    <td>
                        <span class="badge-status {{ $purchase->payment_status === 'paid' ? 'badge-success' : ($purchase->payment_status === 'partial' ? 'badge-warning' : 'badge-danger') }}">
                            {{ $purchase->payment_status === 'paid' ? 'পরিশোধিত' : ($purchase->payment_status === 'partial' ? 'আংশিক' : 'বাকি') }}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <div class="action-btns-wrap">
                            <a href="{{ route('admin.purchases.show', $purchase->id) }}" class="btn-tbl-action btn-tbl-view" title="View Purchase Details">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: #9CA3AF; padding: 36px;">
                        কোন পারচেজ রেকর্ড পাওয়া যায়নি।
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($purchases->hasPages())
        <div style="padding: 12px 18px; border-top: 1px solid var(--admin-border); background: #FFFFFF;">
            {{ $purchases->links() }}
        </div>
    @endif
</div>
@endsection
