@extends('layouts.admin')

@section('title', 'Products')
@section('breadcrumb', 'Products')

@section('content')
<!-- Top Stat Cards (Sohoj Theme) -->
<div class="stat-cards-grid">
    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-orange">
            <i class="fa-solid fa-shirt"></i>
        </div>
        <div>
            <div class="stat-card-sub">Total Products</div>
            <div class="stat-card-val">{{ \App\Models\Product::count() }}</div>
        </div>
    </div>

    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-green">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div>
            <div class="stat-card-sub">Active Products</div>
            <div class="stat-card-val">{{ \App\Models\Product::where('is_active', true)->count() }}</div>
        </div>
    </div>

    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-purple">
            <i class="fa-solid fa-boxes-stacked"></i>
        </div>
        <div>
            <div class="stat-card-sub">Total Categories</div>
            <div class="stat-card-val">{{ \App\Models\Category::count() }}</div>
        </div>
    </div>
</div>

<!-- Search & Filter Bar (Matching Sohoj ERP Screenshot) -->
<div class="filter-card-bar">
    <form method="GET" action="{{ route('admin.products.index') }}" class="filter-left-group">
        <div class="search-input-wrap">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" name="search" value="{{ request('search') }}" class="filter-search-input" placeholder="প্রোডাক্টের নাম বা SKU দিয়ে খুঁজুন...">
        </div>

        <select name="category" class="filter-select">
            <option value="">সব ক্যাটাগরি</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>

        <select name="status" class="filter-select">
            <option value="">সব স্ট্যাটাস</option>
            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Draft / Inactive</option>
        </select>

        <select name="section" class="filter-select">
            <option value="">সব সেকশন</option>
            <option value="new_arrival" {{ request('section') === 'new_arrival' ? 'selected' : '' }}>New Arrival</option>
            <option value="top_selling" {{ request('section') === 'top_selling' ? 'selected' : '' }}>Top Selling</option>
        </select>

        <button type="submit" class="btn-orange-search">
            <i class="fa-solid fa-magnifying-glass"></i> খুঁজুন
        </button>

        @if(request()->anyFilled(['search', 'category', 'status', 'section']))
            <a href="{{ route('admin.products.index') }}" class="btn-admin-secondary" style="padding: 6px 12px; font-size: 11.5px; border-radius: 6px;">রিসেট</a>
        @endif
    </form>

    <a href="{{ route('admin.products.create') }}" class="btn-orange-add">
        <i class="fa-solid fa-plus"></i> নতুন প্রোডাক্ট
    </a>
</div>

<!-- Compact Products Table (Exact Sohoj Excel-like Layout) -->
<div class="table-card-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th style="width: 45px; text-align: center;">#</th>
                <th style="width: 55px; text-align: center;">ছবি</th>
                <th>প্রোডাক্টের নাম ও SKU</th>
                <th>ক্যাটাগরি</th>
                <th>মূল্য</th>
                <th style="text-align: center;">স্টক</th>
                <th>স্ট্যাটাস</th>
                <th style="text-align: right; width: 100px;">অ্যাকশন</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $index => $product)
                <tr>
                    <td style="text-align: center; color: #9CA3AF; font-weight: 600;">
                        {{ $products->firstItem() + $index }}
                    </td>
                    <td style="text-align: center;">
                        <img src="{{ $product->thumbnail_url }}" style="width: 38px; height: 42px; object-fit: cover; border-radius: 4px; border: 1px solid #E5E7EB; background: #F8FAFC;" alt="{{ $product->name }}">
                    </td>
                    <td>
                        <div style="font-weight: 700; color: #111827; margin-bottom: 2px;">
                            <a href="{{ route('product.show', $product->slug) }}" target="_blank" style="color: #111827; text-decoration: none;">
                                {{ $product->name }}
                            </a>
                        </div>
                        <div class="invoice-orange">SKU: {{ $product->sku }}</div>
                        <div style="margin-top: 4px; display: flex; gap: 4px; flex-wrap: wrap;">
                            @if($product->is_new_arrival)
                                <span style="background: #ECFDF5; color: #047857; font-size: 10px; font-weight: 700; padding: 1px 6px; border-radius: 4px; border: 1px solid #A7F3D0;">
                                    <i class="fa-solid fa-sparkles"></i> New Arrival
                                </span>
                            @endif
                            @if($product->is_top_selling)
                                <span style="background: #FFFBEB; color: #B45309; font-size: 10px; font-weight: 700; padding: 1px 6px; border-radius: 4px; border: 1px solid #FDE68A;">
                                    <i class="fa-solid fa-fire"></i> Top Selling
                                </span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <span style="font-size: 12px; font-weight: 600; color: #4B5563;">{{ $product->category?->name ?? 'Unassigned' }}</span>
                    </td>
                    <td>
                        <span class="tbl-price">৳{{ number_format($product->current_price, 2) }}</span>
                        @if($product->sale_price)
                            <div style="font-size: 10.5px; text-decoration: line-through; color: #9CA3AF;">৳{{ number_format($product->regular_price, 2) }}</div>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        @php $stk = $product->total_stock; @endphp
                        <span class="badge-status {{ $stk > 5 ? 'badge-success' : ($stk > 0 ? 'badge-warning' : 'badge-danger') }}">
                            {{ $stk }} in stock
                        </span>
                    </td>
                    <td>
                        <span class="badge-status {{ $product->is_active ? 'badge-success' : 'badge-neutral' }}">
                            {{ $product->is_active ? 'Active' : 'Draft' }}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <div class="action-btns-wrap">
                            <a href="{{ route('product.show', $product->slug) }}" target="_blank" class="btn-tbl-action btn-tbl-view" title="Live Preview">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn-tbl-action btn-tbl-edit" title="Edit Product">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Delete this product?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-tbl-action btn-tbl-delete" title="Delete Product">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: #9CA3AF; padding: 36px;">
                        কোন প্রোডাক্ট পাওয়া যায়নি।
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($products->hasPages())
        <div style="padding: 12px 18px; border-top: 1px solid var(--admin-border); background: #FFFFFF;">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
