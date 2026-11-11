@extends('layouts.app')

@section('title', 'Shop All Saree & Boutique Collections | Meghduti Paridhaan')

@push('styles')
<style>
    .shop-header {
        background: #FFFFFF;
        border-bottom: 1px solid #E5E7EB;
        padding: 24px 0;
        margin-bottom: 24px;
    }
    .shop-title {
        font-family: 'Playfair Display', serif;
        font-size: 24px;
        font-weight: 700;
        color: #111827;
    }
    .shop-layout {
        display: grid;
        grid-template-columns: 240px 1fr;
        gap: 26px;
        margin-bottom: 60px;
    }
    @media (max-width: 992px) {
        .shop-layout { grid-template-columns: 1fr; }
        .filter-sidebar { display: none; }
    }
    .filter-card {
        background: #FFFFFF;
        border-radius: 6px;
        border: 1px solid #E5E7EB;
        padding: 18px;
        margin-bottom: 16px;
    }
    .filter-title {
        font-size: 13px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 14px;
        color: #111827;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .filter-list {
        list-style: none;
    }
    .filter-item {
        margin-bottom: 6px;
    }
    .filter-link {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13px;
        color: #4B5563;
        padding: 4px 0;
        transition: color 0.2s;
    }
    .filter-link:hover, .filter-link.active {
        color: var(--brand-green);
        font-weight: 700;
    }
    .shop-topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        background: #FFFFFF;
        padding: 10px 16px;
        border-radius: 6px;
        border: 1px solid #E5E7EB;
    }
    .sort-select {
        padding: 6px 12px;
        border-radius: 4px;
        border: 1px solid #D1D5DB;
        font-size: 12.5px;
        background: #FFFFFF;
        outline: none;
        font-family: inherit;
    }

    /* Product Grid */
    .shop-product-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }
    @media (max-width: 1200px) { .shop-product-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 600px) { .shop-product-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; } }

    .product-card-meghduti {
        background: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 6px;
        padding: 8px;
        display: flex;
        flex-direction: column;
        transition: all 0.25s ease;
    }
    .product-card-meghduti:hover {
        border-color: #D1D5DB;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.07);
        transform: translateY(-2px);
    }
    .p-img-wrap {
        position: relative;
        width: 100%;
        aspect-ratio: 1 / 1;
        background: #F9FAFB;
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 8px;
    }
    .p-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    /* Small Watermark Badge in Top-Right Corner */
    .p-watermark-overlay {
        position: absolute;
        top: 8px;
        right: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: none;
        z-index: 2;
    }
    .p-watermark-badge {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: rgba(90, 16, 28, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.9);
        color: #FFFFFF;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.25);
    }
    .p-watermark-icon { font-size: 11px; }
    .p-watermark-text { display: none; }
    .p-title-link {
        font-size: 12.5px;
        font-weight: 600;
        color: #1F2937;
        line-height: 1.35;
        margin-bottom: 6px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 34px;
        text-decoration: none;
        text-align: center;
    }
    .p-title-link:hover { color: var(--brand-green); }
    .p-price-val {
        font-size: 14px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 10px;
        text-align: center;
    }
    .p-actions-row-top {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 6px;
    }
    .btn-add-cart-outline {
        flex: 1;
        height: 30px;
        background: #FFFFFF;
        border: 1px solid #D1D5DB;
        border-radius: 4px;
        color: #374151;
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        cursor: pointer;
    }
    .btn-add-cart-outline:hover {
        border-color: var(--brand-green);
        color: var(--brand-green);
        background: #F0FDF4;
    }
    .btn-add-cart-outline i { color: var(--brand-green); }
    .btn-wishlist-heart {
        width: 30px;
        height: 30px;
        border-radius: 4px;
        background: #FFFFFF;
        border: 1px solid #D1D5DB;
        color: #9CA3AF;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
    .btn-wishlist-heart:hover { color: #EF4444; border-color: #EF4444; }
    .btn-buy-now-full {
        width: 100%;
        height: 32px;
        background: var(--brand-green);
        color: #FFFFFF;
        border: none;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        cursor: pointer;
        text-decoration: none;
    }
    .btn-buy-now-full:hover { background: var(--brand-green-hover); color: #FFFFFF; }
</style>
@endpush

@section('content')
<div class="shop-header">
    <div class="container">
        <h1 class="shop-title">
            @if(request('category'))
                {{ ucfirst(str_replace('-', ' ', request('category'))) }} Collection
            @elseif(request('q'))
                Search Results for: "{{ request('q') }}"
            @else
                All Collections - Meghduti Paridhaan
            @endif
        </h1>
        <div style="font-size: 12.5px; color: #6B7280; margin-top: 4px;">
            Showing {{ $products->total() }} items available in stock
        </div>
    </div>
</div>

<div class="container">
    <div class="shop-layout">
        <!-- Sidebar Filters -->
        <aside class="filter-sidebar">
            <div class="filter-card">
                <div class="filter-title">Categories</div>
                <ul class="filter-list">
                    <li class="filter-item">
                        <a href="{{ route('shop.index') }}" class="filter-link {{ !request('category') ? 'active' : '' }}">
                            <span>All Products</span>
                            <i class="fa-solid fa-angle-right" style="font-size: 10px;"></i>
                        </a>
                    </li>
                    @foreach($categories as $cat)
                        <li class="filter-item">
                            <a href="{{ route('shop.index', ['category' => $cat->slug]) }}" class="filter-link {{ request('category') == $cat->slug ? 'active' : '' }}">
                                <span>{{ $cat->name }}</span>
                                <i class="fa-solid fa-angle-right" style="font-size: 10px;"></i>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            @if(request()->anyFilled(['category', 'q', 'sort']))
                <a href="{{ route('shop.index') }}" style="display: block; text-align: center; font-size: 12px; color: var(--brand-green); font-weight: 700; padding: 10px; border: 1px dashed var(--brand-green); border-radius: 4px; text-decoration: none;">
                    <i class="fa-solid fa-rotate-right"></i> Reset Filters
                </a>
            @endif
        </aside>

        <!-- Product Listings -->
        <main>
            <div class="shop-topbar">
                <div style="font-size: 12.5px; color: #6B7280;">
                    Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }}
                </div>
                <form method="GET" id="sortForm">
                    @foreach(request()->except('sort') as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach
                    <select name="sort" class="sort-select" onchange="document.getElementById('sortForm').submit()">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>New Arrivals</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>Featured Collection</option>
                    </select>
                </form>
            </div>

            @if($products->count() > 0)
                <div class="shop-product-grid">
                    @foreach($products as $product)
                        <div class="product-card-meghduti">
                            <div class="p-img-wrap">
                                <a href="{{ route('product.show', $product->slug) }}">
                                    <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" loading="lazy">
                                </a>
                                <div class="p-watermark-overlay">
                                    <div class="p-watermark-badge">
                                        <i class="fa-solid fa-gem p-watermark-icon"></i>
                                        <span class="p-watermark-text">Meghduti Paridhaan</span>
                                    </div>
                                </div>
                            </div>

                            <div class="p-details-box">
                                <a href="{{ route('product.show', $product->slug) }}" class="p-title-link" title="{{ $product->name }}">
                                    {{ $product->name }}
                                </a>

                                <div class="p-price-val">
                                    ৳{{ number_format($product->current_price) }}
                                </div>

                                <div class="p-actions-row-top">
                                    <button type="button" class="btn-add-cart-outline" onclick="addToCartAjax({{ $product->id }})">
                                        <i class="fa-solid fa-cart-shopping"></i> Add to Cart
                                    </button>
                                    <button type="button" class="btn-wishlist-heart {{ in_array($product->id, $wishlistIds ?? []) ? 'active in-wishlist' : '' }}" data-wishlist-id="{{ $product->id }}" onclick="toggleWishlist({{ $product->id }}, this)" title="Wishlist">
                                        <i class="{{ in_array($product->id, $wishlistIds ?? []) ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>
                                    </button>
                                </div>

                                @if($product->total_stock > 0)
                                    <button type="button" class="btn-buy-now-full" onclick="buyNowWithTracking({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->current_price }}, '{{ addslashes($product->category->name ?? 'Boutique') }}')">
                                        <i class="fa-solid fa-cart-shopping" style="font-size: 11px;"></i> Buy Now
                                    </button>
                                @else
                                    <button type="button" style="width: 100%; height: 32px; background: #DC2626; color: #FFFFFF; border: none; border-radius: 4px; font-size: 11.5px; font-weight: 700;" disabled>
                                        Out of Stock
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div style="margin-top: 36px;">
                    {{ $products->links() }}
                </div>
            @else
                <div style="text-align: center; padding: 70px 20px; background: #FFFFFF; border-radius: 6px; border: 1px solid #E5E7EB;">
                    <i class="fa-solid fa-magnifying-glass" style="font-size: 40px; color: #D1D5DB; margin-bottom: 14px;"></i>
                    <h3 style="font-size: 18px; font-weight: 700; color: #111827;">কোনো পণ্য পাওয়া যায়নি</h3>
                    <p style="color: #6B7280; margin: 8px 0 20px; font-size: 13px;">অন্য কোনো কি-ওয়ার্ড দিয়ে আবার চেষ্টা করুন।</p>
                    <a href="{{ route('shop.index') }}" style="background: var(--brand-green); color: white; padding: 10px 24px; border-radius: 4px; font-weight: 700; font-size: 13px; text-decoration: none;">সকল কালেকশন দেখুন</a>
                </div>
            @endif
        </main>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // GA4 & Meta Pixel: view_item_list & search Tracking
    document.addEventListener('DOMContentLoaded', function() {
        @if(request()->filled('q'))
            if (window.trackEcommerceEvent) {
                window.trackEcommerceEvent('search', {
                    search_term: '{{ addslashes(request("q")) }}'
                }, 'Search', {
                    search_string: '{{ addslashes(request("q")) }}'
                });
            }
        @endif

        @if($products->count() > 0)
            if (window.trackEcommerceEvent) {
                window.trackEcommerceEvent('view_item_list', {
                    ecommerce: {
                        item_list_name: '{{ addslashes(request("category") ? ucfirst(str_replace("-", " ", request("category"))) : "Boutique Collection") }}',
                        items: [
                            @foreach($products->take(20) as $idx => $p)
                            {
                                item_id: '{{ $p->id }}',
                                item_name: '{{ addslashes($p->name) }}',
                                price: {{ (float) $p->current_price }},
                                index: {{ $idx + 1 }},
                                item_category: '{{ addslashes($p->category->name ?? "Boutique") }}',
                                item_brand: 'Meghduti Paridhaan'
                            }{{ !$loop->last ? ',' : '' }}
                            @endforeach
                        ]
                    }
                });
            }
        @endif
    });
</script>
@endpush
