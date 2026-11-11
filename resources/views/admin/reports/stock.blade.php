@extends('layouts.admin')

@section('title', 'Warehouse Stock & Inventory Report')
@section('breadcrumb', 'Stock Report')

@push('styles')
<style>
    @media print {
        .admin-sidebar, .admin-topbar, .filter-card-bar, .action-btns-wrap, .btn-tbl-action, .pagination {
            display: none !important;
        }
        .admin-main-wrap, .admin-content-body {
            margin: 0 !important;
            padding: 0 !important;
        }
        .table-card-container {
            box-shadow: none !important;
            border: none !important;
        }
    }
    .stat-cards-stock-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 14px;
        margin-bottom: 18px;
    }
</style>
@endpush

@section('content')
<!-- Stat Cards Row (Highlighting Variant Stock & Inventory Value) -->
<div class="stat-cards-stock-grid">
    <!-- Card 1: Total Products -->
    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-blue">
            <i class="fa-solid fa-boxes-stacked"></i>
        </div>
        <div>
            <div class="stat-card-sub">Total Items (প্রোডাক্ট)</div>
            <div class="stat-card-val">{{ number_format($totalProducts) }} <span style="font-size: 13px; font-weight: 500; color: #6B7280;">টি</span></div>
        </div>
    </div>

    <!-- Card 2: Total Stock Quantity (Variants Combined) -->
    <div class="stat-card-compact" style="border-left: 4px solid #5A101C;">
        <div class="stat-icon-wrap" style="background: #FFF1F3; color: #5A101C;">
            <i class="fa-solid fa-layer-group"></i>
        </div>
        <div>
            <div class="stat-card-sub">মোট স্টক (ভ্যারিয়েন্টসহ)</div>
            <div class="stat-card-val" style="color: #5A101C; font-weight: 900;">
                {{ number_format($totalStockQuantity) }} <span style="font-size: 13px; font-weight: 600;">পিস</span>
            </div>
        </div>
    </div>

    <!-- Card 3: Total Stock Value -->
    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-green">
            <i class="fa-solid fa-bangladeshi-taka-sign"></i>
        </div>
        <div>
            <div class="stat-card-sub">মোট স্টক ভ্যালু</div>
            <div class="stat-card-val" style="color: #15803D;">৳{{ number_format($totalStockValue, 2) }}</div>
        </div>
    </div>

    <!-- Card 4: Low Stock Warning -->
    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-orange">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div>
            <div class="stat-card-sub">লো স্টক সতর্কতা (≤ ৫ পিস)</div>
            <div class="stat-card-val" style="color: #D97706;">{{ number_format($lowStock) }} <span style="font-size: 13px; font-weight: 500;">আইটেম</span></div>
        </div>
    </div>

    <!-- Card 5: Out of Stock -->
    <div class="stat-card-compact">
        <div class="stat-icon-wrap" style="background: #FEE2E2; color: #DC2626;">
            <i class="fa-solid fa-ban"></i>
        </div>
        <div>
            <div class="stat-card-sub">স্টক শেষ (০ পিস)</div>
            <div class="stat-card-val" style="color: #DC2626;">{{ number_format($outOfStock) }} <span style="font-size: 13px; font-weight: 500;">আইটেম</span></div>
        </div>
    </div>
</div>

<!-- Quick Status Filter Pills -->
<div style="display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 12px; align-items: center;">
    <span style="font-size: 12px; font-weight: 700; color: #4B5563; margin-right: 4px;">স্ট্যাটাস:</span>
    <a href="{{ route('admin.reports.stock', array_merge(request()->except(['stock_status', 'filter', 'page']))) }}" 
       class="btn-admin-secondary" 
       style="padding: 5px 12px; font-size: 11.5px; font-weight: 700; border-radius: 999px; text-decoration: none; {{ (!request('stock_status') && !request('filter')) ? 'background: #5A101C; color: white; border-color: #5A101C;' : '' }}">
        সব প্রোডাক্ট ({{ $totalProducts }})
    </a>
    <a href="{{ route('admin.reports.stock', array_merge(request()->except(['page']), ['stock_status' => 'in_stock'])) }}" 
       class="btn-admin-secondary" 
       style="padding: 5px 12px; font-size: 11.5px; font-weight: 700; border-radius: 999px; text-decoration: none; {{ (request('stock_status') == 'in_stock' || request('filter') == 'in_stock') ? 'background: #16A34A; color: white; border-color: #16A34A;' : 'color: #16A34A;' }}">
        <i class="fa-solid fa-circle-check"></i> ইন স্টক (> ৫)
    </a>
    <a href="{{ route('admin.reports.stock', array_merge(request()->except(['page']), ['stock_status' => 'low'])) }}" 
       class="btn-admin-secondary" 
       style="padding: 5px 12px; font-size: 11.5px; font-weight: 700; border-radius: 999px; text-decoration: none; {{ (request('stock_status') == 'low' || request('filter') == 'low') ? 'background: #EA580C; color: white; border-color: #EA580C;' : 'color: #EA580C;' }}">
        <i class="fa-solid fa-triangle-exclamation"></i> লো স্টক (≤ ৫) ({{ $lowStock }})
    </a>
    <a href="{{ route('admin.reports.stock', array_merge(request()->except(['page']), ['stock_status' => 'out'])) }}" 
       class="btn-admin-secondary" 
       style="padding: 5px 12px; font-size: 11.5px; font-weight: 700; border-radius: 999px; text-decoration: none; {{ (request('stock_status') == 'out' || request('filter') == 'out') ? 'background: #DC2626; color: white; border-color: #DC2626;' : 'color: #DC2626;' }}">
        <i class="fa-solid fa-ban"></i> স্টক শেষ (০) ({{ $outOfStock }})
    </a>
</div>

<!-- Search & Filter Bar (Matching Sohoj ERP Design) -->
<div class="filter-card-bar" style="margin-bottom: 16px; background: #FFFFFF; border: 1px solid var(--admin-border); border-radius: 8px; padding: 12px 16px;">
    <form method="GET" action="{{ route('admin.reports.stock') }}" class="filter-left-group" style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center; width: 100%;">
        
        <!-- Search Input -->
        <div class="search-input-wrap" style="position: relative; min-width: 240px; flex: 1;">
            <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #9CA3AF; font-size: 12px;"></i>
            <input type="text" name="search" value="{{ request('search') }}" class="filter-search-input" placeholder="প্রোডাক্টের নাম বা SKU দিয়ে খুঁজুন..." style="padding-left: 32px; height: 36px; font-size: 12.5px; border: 1px solid #D1D5DB; border-radius: 6px; width: 100%;">
        </div>

        <!-- Category Dropdown -->
        <select name="category" class="filter-select" style="height: 36px; font-size: 12.5px; border: 1px solid #D1D5DB; border-radius: 6px; padding: 0 10px; background: #FFFFFF; min-width: 170px;">
            <option value="">সব ক্যাটাগরি</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
                @if($cat->children && $cat->children->count() > 0)
                    @foreach($cat->children as $child)
                        <option value="{{ $child->id }}" {{ request('category') == $child->id ? 'selected' : '' }}>
                            &nbsp;&nbsp;&nbsp;↳ {{ $child->name }}
                        </option>
                    @endforeach
                @endif
            @endforeach
        </select>

        <!-- Stock Status Dropdown -->
        <select name="stock_status" class="filter-select" style="height: 36px; font-size: 12.5px; border: 1px solid #D1D5DB; border-radius: 6px; padding: 0 10px; background: #FFFFFF; min-width: 160px;">
            <option value="">সব স্টক লেভেল</option>
            <option value="in_stock" {{ (request('stock_status') === 'in_stock' || request('filter') === 'in_stock') ? 'selected' : '' }}>ইন স্টক (> ৫ পিস)</option>
            <option value="low" {{ (request('stock_status') === 'low' || request('filter') === 'low') ? 'selected' : '' }}>লো স্টক (≤ ৫ পিস)</option>
            <option value="out" {{ (request('stock_status') === 'out' || request('filter') === 'out') ? 'selected' : '' }}>স্টক শেষ (০ পিস)</option>
        </select>

        <!-- Sorting Dropdown -->
        <select name="sort" class="filter-select" style="height: 36px; font-size: 12.5px; border: 1px solid #D1D5DB; border-radius: 6px; padding: 0 10px; background: #FFFFFF; min-width: 180px;">
            <option value="stock_desc" {{ (request('sort') === 'stock_desc' || !request('sort')) ? 'selected' : '' }}>স্টক: বেশি থেকে কম</option>
            <option value="stock_asc" {{ request('sort') === 'stock_asc' ? 'selected' : '' }}>স্টক: কম থেকে বেশি</option>
            <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>নাম: A to Z</option>
            <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>নাম: Z to A</option>
            <option value="value_desc" {{ request('sort') === 'value_desc' ? 'selected' : '' }}>স্টক ভ্যালু: বেশি থেকে কম</option>
            <option value="value_asc" {{ request('sort') === 'value_asc' ? 'selected' : '' }}>স্টক ভ্যালু: কম থেকে বেশি</option>
        </select>

        <!-- Filter Submit Button -->
        <button type="submit" class="btn-orange-search" style="height: 36px; padding: 0 16px; font-size: 12.5px; display: inline-flex; align-items: center; gap: 6px; border-radius: 6px;">
            <i class="fa-solid fa-filter"></i> ফিল্টার
        </button>

        @if(request()->anyFilled(['search', 'category', 'stock_status', 'filter', 'sort']))
            <a href="{{ route('admin.reports.stock') }}" class="btn-admin-secondary" style="height: 36px; padding: 0 12px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px; border-radius: 6px; text-decoration: none;">
                <i class="fa-solid fa-rotate-left"></i> রিসেট
            </a>
        @endif

        <div style="margin-left: auto; display: flex; gap: 8px;">
            <button type="button" onclick="window.print()" class="btn-admin-secondary" style="height: 36px; padding: 0 14px; font-size: 12.5px; display: inline-flex; align-items: center; gap: 6px; border-radius: 6px;">
                <i class="fa-solid fa-print"></i> প্রিন্ট
            </button>
            <a href="{{ route('admin.purchases.create') }}" class="btn-orange-add" style="height: 36px; padding: 0 16px; font-size: 12.5px; display: inline-flex; align-items: center; gap: 6px; border-radius: 6px; text-decoration: none;">
                <i class="fa-solid fa-plus"></i> স্টক রিস্টক করুন
            </a>
        </div>
    </form>
</div>

<!-- Stock Table (Comprehensive Variant Breakdown & Total Stock) -->
<div class="table-card-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th style="width: 40px; text-align: center;">#</th>
                <th style="width: 50px; text-align: center;">ছবি</th>
                <th>প্রোডাক্টের নাম ও SKU</th>
                <th>ক্যাটাগরি</th>
                <th>একক মূল্য</th>
                <th style="min-width: 240px;">ভেরিয়েন্ট স্টক বিভাজন (কালার ও সাইজ)</th>
                <th style="text-align: center; min-width: 140px;">ভ্যারিয়েন্টসহ মোট স্টক</th>
                <th style="text-align: right; min-width: 110px;">মোট স্টক মূল্য</th>
                <th style="text-align: right; width: 80px;">অ্যাকশন</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $idx => $product)
                @php
                    $prodTotalStock = $product->variants->sum('stock');
                    $prodTotalValue = $prodTotalStock * ($product->regular_price ?? $product->current_price);
                @endphp
                <tr>
                    <td style="text-align: center; color: #9CA3AF; font-weight: 600;">
                        {{ $products->firstItem() + $idx }}
                    </td>
                    <td style="text-align: center;">
                        <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" style="width: 38px; height: 38px; object-fit: cover; border-radius: 4px; border: 1px solid #E5E7EB;">
                    </td>
                    <td>
                        <a href="{{ route('admin.products.edit', $product->id) }}" style="font-weight: 700; color: #111827; font-size: 12.5px; text-decoration: none;">
                            {{ $product->name }}
                        </a>
                        <div class="invoice-orange" style="font-size: 11px; margin-top: 2px;">SKU: {{ $product->sku ?? 'N/A' }}</div>
                    </td>
                    <td>
                        <span style="font-size: 12px; font-weight: 600; color: #4B5563; background: #F3F4F6; padding: 2px 8px; border-radius: 4px;">
                            {{ $product->category?->name ?? 'Unassigned' }}
                        </span>
                    </td>
                    <td>
                        <span class="tbl-price" style="font-weight: 700;">৳{{ number_format($product->current_price, 2) }}</span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                            @forelse($product->variants as $v)
                                @php
                                    $isOut = $v->stock <= 0;
                                    $isLow = $v->stock > 0 && $v->stock <= 5;
                                    $vColor = $isOut ? '#EF4444' : ($isLow ? '#D97706' : '#16A34A');
                                    $vBg = $isOut ? '#FEF2F2' : ($isLow ? '#FFFBEB' : '#F0FDF4');
                                    $vBorder = $isOut ? '#FECACA' : ($isLow ? '#FDE68A' : '#BBF7D0');
                                @endphp
                                <span style="font-size: 11px; background: {{ $vBg }}; border: 1px solid {{ $vBorder }}; padding: 3px 7px; border-radius: 5px; display: inline-flex; align-items: center; gap: 4px;" title="Color: {{ $v->color?->name ?? 'None' }}, Size: {{ $v->size?->name ?? 'None' }}">
                                    @if($v->color && !empty($v->color->hex_code))
                                        <span style="width: 8px; height: 8px; border-radius: 50%; background-color: {{ $v->color->hex_code }}; border: 1px solid rgba(0,0,0,0.2);"></span>
                                    @endif
                                    <span style="color: #374151; font-weight: 600;">
                                        {{ $v->color?->name ?? '' }}{{ $v->color && $v->size ? ' - ' : '' }}{{ $v->size?->name ?? '' }}:
                                    </span>
                                    <strong style="color: {{ $vColor }}; font-weight: 800;">{{ $v->stock }}</strong>
                                </span>
                            @empty
                                <span style="font-size: 11px; color: #9CA3AF; font-style: italic;">কোনো ভ্যারিয়েন্ট নেই</span>
                            @endforelse
                        </div>
                    </td>
                    <td style="text-align: center;">
                        <span class="badge-status {{ $prodTotalStock > 5 ? 'badge-success' : ($prodTotalStock > 0 ? 'badge-warning' : 'badge-danger') }}" style="font-size: 12px; font-weight: 800; padding: 4px 10px;">
                            {{ number_format($prodTotalStock) }} pcs
                        </span>
                        <div style="font-size: 10px; color: {{ $prodTotalStock > 5 ? '#16A34A' : ($prodTotalStock > 0 ? '#D97706' : '#DC2626') }}; font-weight: 700; margin-top: 2px;">
                            {{ $prodTotalStock > 5 ? 'ইন স্টক' : ($prodTotalStock > 0 ? 'লো স্টক' : 'স্টক শেষ') }}
                        </div>
                    </td>
                    <td style="text-align: right;">
                        <span style="font-weight: 700; font-size: 12.5px; color: #111827;">৳{{ number_format($prodTotalValue, 2) }}</span>
                    </td>
                    <td style="text-align: right;">
                        <div class="action-btns-wrap">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn-tbl-action btn-tbl-edit" title="Edit Stock / Variants">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <a href="{{ route('product.show', $product->slug) }}" target="_blank" class="btn-tbl-action btn-tbl-view" title="View on Website">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 36px; color: #6B7280;">
                        <i class="fa-solid fa-box-open" style="font-size: 32px; color: #D1D5DB; margin-bottom: 8px; display: block;"></i>
                        কোনো প্রোডাক্ট পাওয়া যায়নি।
                    </td>
                </tr>
            @endforelse
        </tbody>

        @if($products->count() > 0)
            @php
                $pageTotalQty = $products->sum(fn($p) => $p->variants->sum('stock'));
                $pageTotalVal = $products->sum(fn($p) => $p->variants->sum('stock') * $p->regular_price);
            @endphp
            <tfoot style="background: #F9FAFB; border-top: 2px solid #E5E7EB; font-weight: 800;">
                <tr>
                    <td colspan="5" style="text-align: right; padding: 12px 16px; font-size: 12.5px; color: #374151;">
                        এই পেজের সাব-টোটাল ({{ $products->count() }} টি প্রোডাক্ট):
                    </td>
                    <td></td>
                    <td style="text-align: center; padding: 12px; font-size: 13px; color: #5A101C;">
                        {{ number_format($pageTotalQty) }} pcs
                    </td>
                    <td style="text-align: right; padding: 12px 16px; font-size: 13px; color: #111827;">
                        ৳{{ number_format($pageTotalVal, 2) }}
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        @endif
    </table>

    @if($products->hasPages())
        <div style="padding: 12px 18px; border-top: 1px solid var(--admin-border); background: #FFFFFF;">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
