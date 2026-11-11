@extends('layouts.admin')

@section('title', 'Purchases & Supplier Cost Report')
@section('breadcrumb', 'Purchases Report')

@section('content')
<!-- Filter Card Bar -->
<div class="filter-card-bar">
    <form method="GET" action="{{ route('admin.reports.purchase') }}" class="filter-left-group">
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
        <div class="stat-icon-wrap stat-icon-orange">
            <i class="fa-solid fa-truck-ramp-box"></i>
        </div>
        <div>
            <div class="stat-card-sub">Total Purchase Cost</div>
            <div class="stat-card-val">৳{{ number_format($totalPurchaseAmount, 2) }}</div>
        </div>
    </div>

    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-green">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div>
            <div class="stat-card-sub">Paid to Suppliers</div>
            <div class="stat-card-val">৳{{ number_format($totalPaid, 2) }}</div>
        </div>
    </div>

    <div class="stat-card-compact">
        <div class="stat-icon-wrap" style="background: #FEE2E2; color: #DC2626;">
            <i class="fa-solid fa-receipt"></i>
        </div>
        <div>
            <div class="stat-card-sub">Total Supplier Due</div>
            <div class="stat-card-val" style="color: #DC2626;">৳{{ number_format($totalDue, 2) }}</div>
        </div>
    </div>
</div>

<!-- Purchases Table (Exact Sohoj Excel-like Layout) -->
<div class="table-card-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th style="width: 45px; text-align: center;">#</th>
                <th>ইনভয়েস #</th>
                <th>সাপ্লায়ার</th>
                <th>তারিখ</th>
                <th>মোট খরচ</th>
                <th>পরিশোধিত</th>
                <th>বাকি (Due)</th>
                <th>পেমেন্ট স্ট্যাটাস</th>
            </tr>
        </thead>
        <tbody>
            @forelse($purchases as $idx => $p)
                <tr>
                    <td style="text-align: center; color: #9CA3AF; font-weight: 600;">
                        {{ $idx + 1 }}
                    </td>
                    <td>
                        <a href="{{ route('admin.purchases.show', $p->id) }}" class="invoice-orange">
                            {{ $p->purchase_number }}
                        </a>
                    </td>
                    <td style="font-weight: 600; color: #111827;">
                        {{ $p->supplier?->name ?? 'Cash' }}
                    </td>
                    <td style="color: #64748B;">
                        {{ \Carbon\Carbon::parse($p->purchase_date)->format('d M, Y') }}
                    </td>
                    <td>
                        <span class="tbl-price">৳{{ number_format($p->total_amount, 2) }}</span>
                    </td>
                    <td>
                        <span style="color: #16A34A; font-weight: 700;">৳{{ number_format($p->paid_amount, 2) }}</span>
                    </td>
                    <td>
                        @php $due = $p->total_amount - $p->paid_amount; @endphp
                        <span style="color: {{ $due > 0 ? '#DC2626' : '#6B7280' }}; font-weight: 700;">
                            ৳{{ number_format($due, 2) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge-status {{ $p->payment_status === 'paid' ? 'badge-success' : ($p->payment_status === 'partial' ? 'badge-warning' : 'badge-danger') }}">
                            {{ $p->payment_status === 'paid' ? 'পরিশোধিত' : ($p->payment_status === 'partial' ? 'আংশিক' : 'বাকি') }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: #9CA3AF; padding: 40px;">
                        এই সময়সীমার মধ্যে কোন পারচেজ নেই।
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
