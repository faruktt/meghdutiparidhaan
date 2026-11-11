@extends('layouts.admin')

@section('title', 'Colors')
@section('breadcrumb', 'Colors')

@section('content')
<div style="display: grid; grid-template-columns: 340px 1fr; gap: 20px; align-items: start;">
    <!-- Add Color Form -->
    <div class="card" style="padding: 16px;">
        <h3 class="card-title" style="margin-bottom: 14px; font-size: 14px; display: flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-plus-circle" style="color: var(--admin-primary);"></i> নতুন কালার যোগ করুন
        </h3>
        <form action="{{ route('admin.colors.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 11.5px; font-weight: 700; color: #374151; margin-bottom: 4px;">কালার নাম <span style="color: red;">*</span></label>
                <input type="text" name="name" class="form-control" placeholder="যেমন: Navy Blue, Charcoal, Olive" required>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 11.5px; font-weight: 700; color: #374151; margin-bottom: 4px;">কালার কোড (Hex)</label>
                <input type="color" name="hex_code" value="#1E3A8A" style="width: 100%; height: 38px; border: 1px solid var(--admin-border); border-radius: 6px; cursor: pointer; background: white; padding: 2px;">
            </div>

            <button type="submit" class="btn-orange-add" style="width: 100%; justify-content: center; height: 36px;">
                <i class="fa-solid fa-plus"></i> কালার সংরক্ষণ করুন
            </button>
        </form>
    </div>

    <!-- Colors Table (Exact Sohoj Excel-like Layout) -->
    <div class="table-card-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 45px; text-align: center;">#</th>
                    <th style="width: 55px; text-align: center;">সোয়াচ</th>
                    <th>কালার নাম</th>
                    <th>হেক্স কোড</th>
                    <th style="text-align: right; width: 70px;">অ্যাকশন</th>
                </tr>
            </thead>
            <tbody>
                @forelse($colors as $index => $color)
                    <tr>
                        <td style="text-align: center; color: #9CA3AF; font-weight: 600;">
                            {{ $index + 1 }}
                        </td>
                        <td style="text-align: center;">
                            <div style="width: 22px; height: 22px; border-radius: 50%; background: {{ $color->hex_code ?? '#333' }}; border: 1px solid #CBD5E1; margin: 0 auto; box-shadow: 0 1px 2px rgba(0,0,0,0.1);"></div>
                        </td>
                        <td>
                            <strong style="color: #111827; font-size: 12.5px;">{{ $color->name }}</strong>
                        </td>
                        <td>
                            <code style="background: #F1F5F9; color: #475569; padding: 2px 6px; border-radius: 4px; font-size: 11px;">{{ $color->hex_code }}</code>
                        </td>
                        <td style="text-align: right;">
                            <div class="action-btns-wrap">
                                <form action="{{ route('admin.colors.destroy', $color->id) }}" method="POST" onsubmit="return confirm('Delete this color?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-tbl-action btn-tbl-delete" title="Delete Color">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #9CA3AF; padding: 36px;">
                            কোন কালার তৈরি করা হয়নি।
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
