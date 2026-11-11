@extends('layouts.admin')

@section('title', 'Suppliers')
@section('breadcrumb', 'Suppliers')

@section('content')
<!-- Top Stat Cards (Sohoj Theme) -->
<div class="stat-cards-grid">
    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-purple">
            <i class="fa-solid fa-building-user"></i>
        </div>
        <div>
            <div class="stat-card-sub">Total Suppliers</div>
            <div class="stat-card-val">{{ \App\Models\Supplier::count() }}</div>
        </div>
    </div>

    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-orange">
            <i class="fa-solid fa-truck-ramp-box"></i>
        </div>
        <div>
            <div class="stat-card-sub">Total Purchase Orders</div>
            <div class="stat-card-val">{{ \App\Models\Purchase::count() }}</div>
        </div>
    </div>

    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-green">
            <i class="fa-solid fa-bangladeshi-taka-sign"></i>
        </div>
        <div>
            <div class="stat-card-sub">Total Paid Wholesale</div>
            <div class="stat-card-val">৳{{ number_format(\App\Models\Purchase::sum('paid_amount'), 2) }}</div>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 340px 1fr; gap: 20px; align-items: start;">
    <!-- Add Supplier Form -->
    <div class="card" style="padding: 16px;">
        <h3 class="card-title" style="margin-bottom: 14px; font-size: 14px; display: flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-plus-circle" style="color: var(--admin-primary);"></i> নতুন সাপ্লায়ার যোগ করুন
        </h3>
        <form action="{{ route('admin.suppliers.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 11.5px; font-weight: 700; color: #374151; margin-bottom: 4px;">সাপ্লায়ার নাম <span style="color: red;">*</span></label>
                <input type="text" name="name" class="form-control" placeholder="যেমন: Al-Amin Textile" required>
            </div>

            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 11.5px; font-weight: 700; color: #374151; margin-bottom: 4px;">কোম্পানির নাম</label>
                <input type="text" name="company_name" class="form-control" placeholder="যেমন: Dhaka Garments Ltd.">
            </div>

            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 11.5px; font-weight: 700; color: #374151; margin-bottom: 4px;">ফোন নম্বর</label>
                <input type="text" name="phone" class="form-control" placeholder="017xxxxxxxx">
            </div>

            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 11.5px; font-weight: 700; color: #374151; margin-bottom: 4px;">ইমেইল</label>
                <input type="email" name="email" class="form-control" placeholder="supplier@email.com">
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 11.5px; font-weight: 700; color: #374151; margin-bottom: 4px;">ঠিকানা</label>
                <textarea name="address" rows="2" class="form-control" placeholder="সাপ্লায়ার ঠিকানা..."></textarea>
            </div>

            <button type="submit" class="btn-orange-add" style="width: 100%; justify-content: center; height: 36px;">
                <i class="fa-solid fa-plus"></i> সাপ্লায়ার সংরক্ষণ করুন
            </button>
        </form>
    </div>

    <!-- Compact Suppliers Table (Exact Sohoj Excel-like Layout) -->
    <div class="table-card-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 45px; text-align: center;">#</th>
                    <th>সাপ্লায়ার ও কোম্পানি</th>
                    <th>যোগাযোগ</th>
                    <th style="text-align: center;">অর্ডার সংখ্যা</th>
                    <th style="text-align: right; width: 70px;">অ্যাকশন</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $index => $supplier)
                    <tr>
                        <td style="text-align: center; color: #9CA3AF; font-weight: 600;">
                            {{ $index + 1 }}
                        </td>
                        <td>
                            <strong style="color: #111827; font-size: 12.5px;">{{ $supplier->name }}</strong>
                            @if($supplier->company_name)
                                <div style="font-size: 11px; color: #64748B;">{{ $supplier->company_name }}</div>
                            @endif
                        </td>
                        <td>
                            <div style="color: #374151;">
                                <i class="fa-solid fa-phone" style="font-size: 10px; color: #9CA3AF; margin-right: 3px;"></i> {{ $supplier->phone ?? '—' }}
                            </div>
                            @if($supplier->email)
                                <div style="font-size: 11px; color: #64748B;">{{ $supplier->email }}</div>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <span class="badge-status badge-info">
                                {{ $supplier->purchases_count }} orders
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <div class="action-btns-wrap">
                                <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}" method="POST" onsubmit="return confirm('Delete this supplier?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-tbl-action btn-tbl-delete" title="Delete Supplier">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #9CA3AF; padding: 36px;">
                            কোন সাপ্লায়ার পাওয়া যায়নি।
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($suppliers->hasPages())
            <div style="padding: 12px 18px; border-top: 1px solid var(--admin-border); background: #FFFFFF;">
                {{ $suppliers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
