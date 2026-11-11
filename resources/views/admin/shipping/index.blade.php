@extends('layouts.admin')

@section('title', 'Shipping Zones')
@section('breadcrumb', 'Shipping Zones')

@section('content')
<!-- Top Stat Cards (Sohoj Theme) -->
<div class="stat-cards-grid">
    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-orange">
            <i class="fa-solid fa-truck-fast"></i>
        </div>
        <div>
            <div class="stat-card-sub">Total Delivery Zones</div>
            <div class="stat-card-val">{{ \App\Models\ShippingZone::count() }}</div>
        </div>
    </div>

    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-green">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div>
            <div class="stat-card-sub">Active Zones</div>
            <div class="stat-card-val">{{ \App\Models\ShippingZone::where('is_active', true)->count() }}</div>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 340px 1fr; gap: 20px; align-items: start;">
    <!-- Add Zone Form -->
    <div class="card" style="padding: 16px;">
        <h3 class="card-title" style="margin-bottom: 14px; font-size: 14px; display: flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-plus-circle" style="color: var(--admin-primary);"></i> নতুন ডেলিভারি জোন যোগ করুন
        </h3>
        <form action="{{ route('admin.shipping.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 11.5px; font-weight: 700; color: #374151; margin-bottom: 4px;">জোনের নাম <span style="color: red;">*</span></label>
                <input type="text" name="name" class="form-control" placeholder="যেমন: Inside Dhaka, Outside Dhaka" required>
            </div>

            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 11.5px; font-weight: 700; color: #374151; margin-bottom: 4px;">ডেলিভারি চার্জ (৳) <span style="color: red;">*</span></label>
                <input type="number" step="0.01" name="charge" class="form-control" placeholder="যেমন: 60 বা 120" required>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 11.5px; font-weight: 700; color: #374151; margin-bottom: 4px;">ডিসপ্লে ক্রম (Sort Order)</label>
                <input type="number" name="sort_order" class="form-control" value="0">
            </div>

            <button type="submit" class="btn-orange-add" style="width: 100%; justify-content: center; height: 36px;">
                <i class="fa-solid fa-plus"></i> জোন সংরক্ষণ করুন
            </button>
        </form>
    </div>

    <!-- Zones Table (Exact Sohoj Excel-like Layout) -->
    <div class="table-card-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 45px; text-align: center;">#</th>
                    <th>জোনের নাম</th>
                    <th>ডেলিভারি চার্জ</th>
                    <th>স্ট্যাটাস</th>
                    <th style="text-align: right; width: 70px;">অ্যাকশন</th>
                </tr>
            </thead>
            <tbody>
                @forelse($zones as $index => $zone)
                    <tr>
                        <td style="text-align: center; color: #9CA3AF; font-weight: 600;">
                            {{ $index + 1 }}
                        </td>
                        <td>
                            <strong style="color: #111827; font-size: 12.5px;">{{ $zone->name }}</strong>
                        </td>
                        <td>
                            <span class="tbl-price">৳{{ number_format($zone->charge, 2) }}</span>
                        </td>
                        <td>
                            <span class="badge-status {{ $zone->is_active ? 'badge-success' : 'badge-neutral' }}">
                                {{ $zone->is_active ? 'Active' : 'Disabled' }}
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <div class="action-btns-wrap">
                                <form action="{{ route('admin.shipping.destroy', $zone->id) }}" method="POST" onsubmit="return confirm('Delete this shipping zone?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-tbl-action btn-tbl-delete" title="Delete Zone">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #9CA3AF; padding: 36px;">
                            কোন ডেলিভারি জোন সেট করা হয়নি।
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
