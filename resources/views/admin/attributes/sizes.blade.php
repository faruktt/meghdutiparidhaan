@extends('layouts.admin')

@section('title', 'Sizes')
@section('breadcrumb', 'Sizes')

@section('content')
<div style="display: grid; grid-template-columns: 340px 1fr; gap: 20px; align-items: start;">
    <!-- Add Size Form -->
    <div class="card" style="padding: 16px;">
        <h3 class="card-title" style="margin-bottom: 14px; font-size: 14px; display: flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-plus-circle" style="color: var(--admin-primary);"></i> নতুন সাইজ যোগ করুন
        </h3>
        <form action="{{ route('admin.sizes.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 11.5px; font-weight: 700; color: #374151; margin-bottom: 4px;">সাইজ লেবেল <span style="color: red;">*</span></label>
                <input type="text" name="name" class="form-control" placeholder="যেমন: M, L, XL, XXL, 38, 40" required>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 11.5px; font-weight: 700; color: #374151; margin-bottom: 4px;">ডিসপ্লে ক্রম</label>
                <input type="number" name="sort_order" class="form-control" value="0">
            </div>

            <button type="submit" class="btn-orange-add" style="width: 100%; justify-content: center; height: 36px;">
                <i class="fa-solid fa-plus"></i> সাইজ সংরক্ষণ করুন
            </button>
        </form>
    </div>

    <!-- Sizes Table (Exact Sohoj Excel-like Layout) -->
    <div class="table-card-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 45px; text-align: center;">#</th>
                    <th>সাইজ লেবেল</th>
                    <th style="text-align: center;">ডিসপ্লে ক্রম</th>
                    <th style="text-align: right; width: 70px;">অ্যাকশন</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sizes as $index => $size)
                    <tr>
                        <td style="text-align: center; color: #9CA3AF; font-weight: 600;">
                            {{ $index + 1 }}
                        </td>
                        <td>
                            <strong style="color: #111827; font-size: 13px;">{{ $size->name }}</strong>
                        </td>
                        <td style="text-align: center; color: #64748B;">
                            {{ $size->sort_order }}
                        </td>
                        <td style="text-align: right;">
                            <div class="action-btns-wrap">
                                <form action="{{ route('admin.sizes.destroy', $size->id) }}" method="POST" onsubmit="return confirm('Delete this size?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-tbl-action btn-tbl-delete" title="Delete Size">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: #9CA3AF; padding: 36px;">
                            কোন সাইজ তৈরি করা হয়নি।
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
