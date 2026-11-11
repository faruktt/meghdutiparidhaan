@extends('layouts.admin')

@section('title', 'Categories')
@section('breadcrumb', 'Categories')

@section('content')
<!-- Top Stat Cards (Sohoj Theme) -->
<div class="stat-cards-grid">
    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-purple">
            <i class="fa-solid fa-tags"></i>
        </div>
        <div>
            <div class="stat-card-sub">Total Categories</div>
            <div class="stat-card-val">{{ \App\Models\Category::count() }}</div>
        </div>
    </div>

    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-green">
            <i class="fa-solid fa-bars"></i>
        </div>
        <div>
            <div class="stat-card-sub">Menu Visible</div>
            <div class="stat-card-val">{{ \App\Models\Category::where('show_in_menu', true)->count() }}</div>
        </div>
    </div>

    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-orange">
            <i class="fa-solid fa-shirt"></i>
        </div>
        <div>
            <div class="stat-card-sub">Total Catalog Products</div>
            <div class="stat-card-val">{{ \App\Models\Product::count() }}</div>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 340px 1fr; gap: 20px; align-items: start;">
    <!-- Add New Category Form (Compact Card) -->
    <div class="card" style="padding: 16px;">
        <h3 class="card-title" style="margin-bottom: 14px; font-size: 14px; display: flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-plus-circle" style="color: var(--admin-primary);"></i> নতুন ক্যাটাগরি যোগ করুন
        </h3>
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 11.5px; font-weight: 700; color: #374151; margin-bottom: 4px;">ক্যাটাগরি নাম <span style="color: red;">*</span></label>
                <input type="text" name="name" class="form-control" placeholder="যেমন: Cuban Shirts, Casual Pants" required>
            </div>

            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 11.5px; font-weight: 700; color: #374151; margin-bottom: 4px;">প্যারেন্ট ক্যাটাগরি</label>
                <select name="parent_id" class="form-control">
                    <option value="">None (Top-Level Category)</option>
                    @foreach($parents as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 11.5px; font-weight: 700; color: #374151; margin-bottom: 4px;">ছবি / আইকন</label>
                <input type="file" name="image" accept="image/*" class="form-control">
            </div>

            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 11.5px; font-weight: 700; color: #374151; margin-bottom: 4px;">বিবরণ</label>
                <textarea name="description" rows="2" class="form-control" placeholder="সংক্ষিপ্ত বিবরণ..."></textarea>
            </div>

            <div style="margin-bottom: 16px; padding: 8px 10px; background: #F8FAFC; border-radius: 6px; border: 1px solid #E2E8F0;">
                <label style="display: flex; align-items: center; gap: 8px; font-size: 12px; font-weight: 600; cursor: pointer; margin: 0;">
                    <input type="checkbox" name="show_in_menu" value="1" checked style="accent-color: var(--admin-primary); width: 15px; height: 15px;">
                    <span>হেডার মেনুতে প্রদর্শন করুন</span>
                </label>
            </div>

            <button type="submit" class="btn-orange-add" style="width: 100%; justify-content: center; height: 36px;">
                <i class="fa-solid fa-plus"></i> ক্যাটাগরি সংরক্ষণ করুন
            </button>
        </form>
    </div>

    <!-- Category List Table (Exact Sohoj Excel-like Layout) -->
    <div class="table-card-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 45px; text-align: center;">#</th>
                    <th style="width: 50px; text-align: center;">ছবি</th>
                    <th>ক্যাটাগরি নাম</th>
                    <th>প্যারেন্ট ক্যাটাগরি</th>
                    <th>মেনু স্ট্যাটাস</th>
                    <th style="text-align: right; width: 70px;">অ্যাকশন</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $index => $cat)
                    <tr>
                        <td style="text-align: center; color: #9CA3AF; font-weight: 600;">
                            {{ $categories->firstItem() + $index }}
                        </td>
                        <td style="text-align: center;">
                            @if($cat->image)
                                <img src="{{ $cat->image_url }}" style="width: 32px; height: 32px; object-fit: cover; border-radius: 4px; border: 1px solid #E5E7EB;" alt="{{ $cat->name }}">
                            @else
                                <div style="width: 32px; height: 32px; border-radius: 4px; background: #F1F5F9; display: flex; align-items: center; justify-content: center; color: #9CA3AF; margin: 0 auto; font-size: 11px;">
                                    <i class="fa-solid fa-folder"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong style="color: #111827; font-size: 12.5px;">{{ $cat->name }}</strong>
                            <div style="font-size: 10.5px; color: #9CA3AF;">slug: {{ $cat->slug }}</div>
                        </td>
                        <td>
                            @if($cat->parent)
                                <span style="background: #F1F5F9; color: #4B5563; padding: 2px 7px; border-radius: 4px; font-size: 11px; font-weight: 600;">
                                    {{ $cat->parent->name }}
                                </span>
                            @else
                                <span style="color: #9CA3AF; font-size: 11px;">Top-Level</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge-status {{ $cat->show_in_menu ? 'badge-success' : 'badge-neutral' }}">
                                {{ $cat->show_in_menu ? 'Visible' : 'Hidden' }}
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <div class="action-btns-wrap">
                                <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Delete this category?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-tbl-action btn-tbl-delete" title="Delete Category">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #9CA3AF; padding: 36px;">
                            কোন ক্যাটাগরি তৈরি করা হয়নি।
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($categories->hasPages())
            <div style="padding: 12px 18px; border-top: 1px solid var(--admin-border); background: #FFFFFF;">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
