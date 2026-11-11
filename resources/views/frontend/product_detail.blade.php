@extends('layouts.app')

@section('title', $product->name . ' | Meghduti Paridhaan - Dhaka')

@push('styles')
<style>
    /* Breadcrumbs */
    .mp-breadcrumb {
        padding: 16px 0 20px;
        font-size: 12.5px;
        color: #6B7280;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .mp-breadcrumb a {
        color: #4B5563;
        transition: color 0.2s;
    }
    .mp-breadcrumb a:hover {
        color: var(--brand-green);
    }
    .mp-breadcrumb .sep {
        color: #D1D5DB;
        font-size: 11px;
    }
    .mp-breadcrumb .current {
        color: #111827;
        font-weight: 700;
    }

    /* Product Showcase Main Grid */
    .mp-product-layout {
        display: grid;
        grid-template-columns: 1.1fr 1fr;
        gap: 40px;
        margin-bottom: 50px;
        align-items: start;
        background: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        padding: 28px;
    }
    @media (max-width: 991px) {
        .mp-product-layout {
            grid-template-columns: 1fr;
            gap: 24px;
            padding: 16px;
        }
    }

    /* Gallery */
    .gallery-flex-box {
        display: flex;
        gap: 14px;
        align-items: flex-start;
    }
    @media (max-width: 640px) {
        .gallery-flex-box {
            flex-direction: column-reverse;
        }
    }

    .gallery-vertical-thumbs {
        display: flex;
        flex-direction: column;
        gap: 10px;
        width: 76px;
        flex-shrink: 0;
    }
    @media (max-width: 640px) {
        .gallery-vertical-thumbs {
            flex-direction: row;
            width: 100%;
            overflow-x: auto;
        }
    }

    .gallery-thumb-box {
        width: 76px;
        height: 86px;
        border: 1px solid #E5E7EB;
        border-radius: 4px;
        cursor: pointer;
        overflow: hidden;
        background: #F9FAFB;
        transition: all 0.2s;
    }
    .gallery-thumb-box.active, .gallery-thumb-box:hover {
        border: 2px solid var(--brand-green);
    }
    .gallery-thumb-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .gallery-main-view {
        flex: 1;
        position: relative;
        border-radius: 6px;
        background: #F9FAFB;
        overflow: hidden;
        border: 1px solid #F3F4F6;
        aspect-ratio: 1 / 1;
    }
    .gallery-main-view img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.3s ease;
    }
    .gallery-main-view:hover img {
        transform: scale(1.03);
    }

    /* Watermark Emblem on main view */
    .gallery-watermark {
        position: absolute;
        top: 14px;
        right: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: none;
        z-index: 2;
    }
    .gallery-watermark-badge {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(90, 16, 28, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.9);
        color: #FFFFFF;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.25);
    }
    .gallery-watermark-badge i {
        font-size: 14px;
    }
    .gallery-watermark-badge span {
        display: none;
    }

    /* Details Wrap */
    .mp-details-wrap {
        display: flex;
        flex-direction: column;
    }
    .mp-product-title {
        font-family: 'Playfair Display', serif;
        font-size: 24px;
        font-weight: 700;
        color: #111827;
        line-height: 1.3;
        margin-bottom: 8px;
    }
    .mp-rating-row {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12.5px;
        color: #6B7280;
        margin-bottom: 14px;
    }
    .mp-rating-stars {
        color: #F59E0B;
        font-size: 13px;
        letter-spacing: 1px;
    }

    .mp-price-row {
        font-size: 26px;
        font-weight: 800;
        color: var(--brand-green);
        margin-bottom: 16px;
        display: flex;
        align-items: baseline;
        gap: 12px;
    }
    .mp-old-price {
        font-size: 16px;
        color: #9CA3AF;
        text-decoration: line-through;
        font-weight: 400;
    }

    .mp-meta-info-box {
        background: #F9FAFB;
        border: 1px solid #E5E7EB;
        border-radius: 6px;
        padding: 12px 16px;
        margin-bottom: 20px;
        font-size: 13px;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .mp-meta-row {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .mp-meta-label {
        font-weight: 700;
        color: #4B5563;
        width: 100px;
    }
    .mp-meta-val {
        color: #111827;
    }

    /* Product Variant Selectors (Color & Size) */
    .mp-variants-wrapper {
        display: flex;
        flex-direction: column;
        gap: 16px;
        margin-bottom: 22px;
        padding: 16px 18px;
        background: #FAFAFA;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
    }
    .variant-section-box {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .variant-title-row {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13.5px;
    }
    .variant-title-text {
        font-weight: 700;
        color: #1F2937;
    }
    .variant-selected-name {
        font-weight: 700;
        color: var(--brand-green);
        background: #FFF1F3;
        border: 1px solid rgba(90, 16, 28, 0.15);
        padding: 2px 10px;
        border-radius: 999px;
        font-size: 12px;
    }
    .variant-options-flex {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
        padding: 4px;
        border-radius: 6px;
        transition: background 0.3s ease;
    }
    .variant-options-flex.selection-needed {
        background: #FEE2E2;
        outline: 2px dashed #EF4444;
        animation: shakeSelect 0.4s ease;
    }
    @keyframes shakeSelect {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-4px); }
        75% { transform: translateX(4px); }
    }

    /* Color Swatch Button */
    .color-swatch-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 14px;
        border: 1.5px solid #D1D5DB;
        border-radius: 999px;
        background: #FFFFFF;
        color: #374151;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        user-select: none;
    }
    .color-swatch-btn:hover {
        border-color: var(--brand-green);
        color: var(--brand-green);
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }
    .color-swatch-btn.active {
        border-color: var(--brand-green);
        background: #FFF1F3;
        color: var(--brand-green);
        box-shadow: 0 0 0 1.5px var(--brand-green);
        font-weight: 700;
    }
    .color-swatch-circle {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        display: inline-block;
        border: 1px solid rgba(0, 0, 0, 0.2);
        flex-shrink: 0;
    }
    .color-swatch-thumb-mini {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        object-fit: cover;
        border: 1px solid #D1D5DB;
        flex-shrink: 0;
    }

    /* Size Pill Button */
    .size-pill-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 46px;
        padding: 7px 16px;
        border: 1.5px solid #D1D5DB;
        border-radius: 6px;
        background: #FFFFFF;
        color: #374151;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        user-select: none;
    }
    .size-pill-btn:hover {
        border-color: var(--brand-green);
        color: var(--brand-green);
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }
    .size-pill-btn.active {
        border-color: var(--brand-green);
        background: var(--brand-green);
        color: #FFFFFF;
        box-shadow: 0 2px 8px rgba(90, 16, 28, 0.3);
        font-weight: 700;
    }

    /* Actions: Stepper + Add Cart + Buy Now */
    .mp-actions-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }
    .mp-qty-box {
        display: flex;
        align-items: center;
        border: 1px solid #D1D5DB;
        border-radius: 4px;
        height: 42px;
        background: #FFFFFF;
    }
    .mp-qty-btn {
        background: none;
        border: none;
        width: 36px;
        height: 100%;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        color: #374151;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .mp-qty-btn:hover { background: #F3F4F6; }
    .mp-qty-input {
        width: 44px;
        text-align: center;
        font-size: 14px;
        font-weight: 800;
        color: #111827;
        border: none;
        outline: none;
    }

    .btn-detail-add-cart {
        flex: 1;
        height: 42px;
        background: #FFFFFF;
        border: 1.5px solid var(--brand-green);
        color: var(--brand-green);
        border-radius: 4px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s;
    }
    .btn-detail-add-cart:hover {
        background: #F0FDF4;
    }

    .btn-detail-buy-now {
        flex: 1;
        height: 42px;
        background: var(--brand-green);
        color: #FFFFFF;
        border: none;
        border-radius: 4px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        transition: background 0.2s;
    }
    .btn-detail-buy-now:hover {
        background: var(--brand-green-hover);
        color: #FFFFFF;
    }

    .btn-detail-wishlist {
        width: 42px;
        height: 42px;
        border-radius: 4px;
        border: 1px solid #D1D5DB;
        background: #FFFFFF;
        color: #4B5563;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        cursor: pointer;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }
    .btn-detail-wishlist:hover,
    .btn-detail-wishlist.active,
    .btn-detail-wishlist.in-wishlist {
        border-color: #EF4444;
        color: #EF4444;
        background: #FEF2F2;
    }

    /* Trust Highlights */
    .mp-trust-badges {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        padding-top: 18px;
        border-top: 1px solid #E5E7EB;
        font-size: 12px;
        color: #4B5563;
    }
    .mp-trust-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .mp-trust-item i {
        color: var(--brand-green);
        font-size: 15px;
    }

    /* Description & Info Box */
    .mp-tabs-card {
        background: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        padding: 24px;
        margin-bottom: 40px;
    }
    .mp-tabs-title {
        font-size: 16px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 14px;
        padding-bottom: 8px;
        border-bottom: 2px solid var(--brand-green);
        display: inline-block;
    }
    .mp-description-content {
        font-size: 13.5px;
        line-height: 1.8;
        color: #4B5563;
    }

    /* Related Products 5 Col Grid System */
    .products-5-col-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 40px;
    }
    @media (max-width: 1200px) {
        .products-5-col-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); }
    }
    @media (max-width: 900px) {
        .products-5-col-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    }
    @media (max-width: 640px) {
        .products-5-col-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; }
    }

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
@php
    $thumbList = [];
    if ($product->thumbnail_url) {
        $thumbList[] = $product->thumbnail_url;
    }
    foreach ($product->images as $img) {
        if (!in_array($img->image_url, $thumbList)) {
            $thumbList[] = $img->image_url;
        }
    }
    // Also include all variant images so all variation images show in the gallery
    foreach ($product->variants as $v) {
        if ($v->image_url && !in_array($v->image_url, $thumbList)) {
            $thumbList[] = $v->image_url;
        }
    }
    if (empty($thumbList)) {
        $thumbList[] = 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&w=700&q=80';
    }

    // Collect distinct colors & sizes
    $productColors = collect();
    $productSizes  = collect();
    foreach ($product->variants as $v) {
        if ($v->color && !$productColors->contains('id', $v->color->id)) {
            $productColors->push($v->color);
        }
        if ($v->size && !$productSizes->contains('id', $v->size->id)) {
            $productSizes->push($v->size);
        }
    }

    $variantsJson = $product->variants->map(function($v) {
        return [
            'id' => $v->id,
            'color_id' => $v->color_id,
            'color_name' => $v->color?->name,
            'color_hex' => $v->color?->hex_code,
            'size_id' => $v->size_id,
            'size_name' => $v->size?->name,
            'stock' => (int) $v->stock,
            'price_adjustment' => (float) $v->price_adjustment,
            'image_url' => $v->image_url,
        ];
    });
@endphp

<div class="container">
    
    <!-- Breadcrumb -->
    <div class="mp-breadcrumb">
        <a href="{{ route('home') }}"><i class="fa-solid fa-house" style="font-size: 11px;"></i> Home</a>
        <span class="sep"><i class="fa-solid fa-chevron-right"></i></span>
        <a href="{{ route('shop.index', ['category' => $product->category?->slug ?? 'woman-clothing']) }}">
            {{ $product->category?->name ?? 'Woman Clothing' }}
        </a>
        <span class="sep"><i class="fa-solid fa-chevron-right"></i></span>
        <span class="current">{{ $product->name }}</span>
    </div>

    <!-- Main Product Layout -->
    <div class="mp-product-layout">
        
        <!-- Left: Image Gallery -->
        <div class="gallery-flex-box">
            <div class="gallery-vertical-thumbs">
                @foreach($thumbList as $idx => $tUrl)
                    <div class="gallery-thumb-box {{ $idx === 0 ? 'active' : '' }}" data-img-url="{{ $tUrl }}" onclick="changeMainImage('{{ $tUrl }}', this)">
                        <img src="{{ $tUrl }}" alt="Thumb {{ $idx + 1 }}">
                    </div>
                @endforeach
            </div>

            <div class="gallery-main-view">
                <img id="mainProductPhoto" src="{{ $thumbList[0] }}" alt="{{ $product->name }}">
                
                <div class="gallery-watermark">
                    <div class="gallery-watermark-badge">
                        <i class="fa-solid fa-gem" style="font-size: 16px; margin-bottom: 2px;"></i>
                        <span style="font-size: 7.5px; font-weight: 700; text-transform: uppercase;">Meghduti Paridhaan</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Product Information & Purchase Actions -->
        <div class="mp-details-wrap">
            <h1 class="mp-product-title">{{ $product->name }}</h1>

            <div class="mp-rating-row">
                <span class="mp-rating-stars">
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star-half-stroke"></i>
                </span>
                <span>(4.8 / 5.0) | 100% Genuine Handloom Collection</span>
            </div>

            <div class="mp-price-row">
                <span id="productPriceDisplay">৳ {{ number_format($product->current_price) }}</span>
                @if($product->sale_price)
                    <span class="mp-old-price">৳ {{ number_format($product->regular_price) }}</span>
                @endif
            </div>

            <div class="mp-meta-info-box">
                <div class="mp-meta-row">
                    <span class="mp-meta-label">Availability:</span>
                    <span class="mp-meta-val" id="productStockDisplay" style="color: #16A34A; font-weight: 700;">
                        <i class="fa-solid fa-circle-check"></i> {{ $product->total_stock > 0 ? 'In Stock (' . $product->total_stock . ' available)' : 'Out of Stock' }}
                    </span>
                </div>
                <div class="mp-meta-row">
                    <span class="mp-meta-label">Category:</span>
                    <span class="mp-meta-val">{{ $product->category?->name ?? 'Woman Clothing' }}</span>
                </div>
                <div class="mp-meta-row">
                    <span class="mp-meta-label">Product SKU:</span>
                    <span class="mp-meta-val">{{ $product->sku ?? 'MP-2026' }}</span>
                </div>
                <div class="mp-meta-row">
                    <span class="mp-meta-label">Location:</span>
                    <span class="mp-meta-val">Dhaka, Bangladesh</span>
                </div>
            </div>

            @if($productColors->count() > 0 || $productSizes->count() > 0)
                <!-- Product Variants Selector (Colors & Sizes) -->
                <div class="mp-variants-wrapper">
                    @if($productColors->count() > 0)
                        <div class="variant-section-box">
                            <div class="variant-title-row">
                                <span class="variant-title-text"><i class="fa-solid fa-palette" style="color: var(--brand-green); font-size: 12px;"></i> Color:</span>
                                <span class="variant-selected-name" id="selectedColorName">Choose Color</span>
                            </div>
                            <div class="variant-options-flex color-options-flex">
                                @foreach($productColors as $color)
                                    @php
                                        $colorVar = $product->variants->first(function($item) use ($color) {
                                            return $item->color_id == $color->id && !empty($item->image_url);
                                        });
                                        $colorImg = $colorVar?->image_url ?? '';
                                    @endphp
                                    <button type="button" 
                                            class="color-swatch-btn" 
                                            data-color-id="{{ $color->id }}" 
                                            data-color-name="{{ $color->name }}" 
                                            data-image-url="{{ $colorImg }}" 
                                            onclick="selectColor({{ $color->id }}, '{{ addslashes($color->name) }}', '{{ $colorImg }}', this)" 
                                            title="{{ $color->name }}">
                                        @if(!empty($colorImg))
                                            <img src="{{ $colorImg }}" class="color-swatch-thumb-mini" alt="{{ $color->name }}">
                                        @elseif(!empty($color->hex_code))
                                            <span class="color-swatch-circle" style="background-color: {{ $color->hex_code }};"></span>
                                        @else
                                            <span class="color-swatch-circle" style="background-color: #E2E8F0;"></span>
                                        @endif
                                        <span>{{ $color->name }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($productSizes->count() > 0)
                        <div class="variant-section-box">
                            <div class="variant-title-row">
                                <span class="variant-title-text"><i class="fa-solid fa-ruler" style="color: var(--brand-green); font-size: 12px;"></i> Size:</span>
                                <span class="variant-selected-name" id="selectedSizeName">Choose Size</span>
                            </div>
                            <div class="variant-options-flex size-options-flex">
                                @foreach($productSizes as $size)
                                    <button type="button" 
                                            class="size-pill-btn" 
                                            data-size-id="{{ $size->id }}" 
                                            data-size-name="{{ $size->name }}" 
                                            onclick="selectSize({{ $size->id }}, '{{ addslashes($size->name) }}', this)">
                                        {{ $size->name }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Quantity Stepper & Buttons -->
            <div class="mp-actions-wrap">
                <div class="mp-qty-box">
                    <button type="button" class="mp-qty-btn" onclick="stepQty(-1)">-</button>
                    <input type="number" id="detailQtyInput" class="mp-qty-input" value="1" min="1" max="50">
                    <button type="button" class="mp-qty-btn" onclick="stepQty(1)">+</button>
                </div>

                <button type="button" class="btn-detail-add-cart" onclick="addCurrentToCart()">
                    <i class="fa-solid fa-cart-shopping"></i> Add to Cart
                </button>

                <button type="button" class="btn-detail-buy-now" onclick="buyCurrentNow()">
                    <i class="fa-solid fa-bolt"></i> Buy Now
                </button>

                <button type="button" class="btn-detail-wishlist {{ in_array($product->id, $wishlistIds ?? []) ? 'active in-wishlist' : '' }}" data-wishlist-id="{{ $product->id }}" onclick="toggleWishlist({{ $product->id }}, this)" title="Wishlist">
                    <i class="{{ in_array($product->id, $wishlistIds ?? []) ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>
                </button>
            </div>

            <!-- Trust Highlights -->
            <div class="mp-trust-badges">
                <div class="mp-trust-item">
                    <i class="fa-solid fa-truck-fast"></i>
                    <div>
                        <strong>Fast Delivery</strong>
                        <div>Dhaka 24-48 hrs, Nationwide 3 days</div>
                    </div>
                </div>
                <div class="mp-trust-item">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                    <div>
                        <strong>Cash on Delivery</strong>
                        <div>Pay comfortably at your doorstep</div>
                    </div>
                </div>
                <div class="mp-trust-item">
                    <i class="fa-solid fa-shield-halved"></i>
                    <div>
                        <strong>100% Authentic</strong>
                        <div>Original artisanal craftsmanship</div>
                    </div>
                </div>
                <div class="mp-trust-item">
                    <i class="fa-solid fa-rotate-left"></i>
                    <div>
                        <strong>Easy Return</strong>
                        <div>Inspect before delivery payment</div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- Product Description & Material Care -->
    <div class="mp-tabs-card">
        <div class="mp-tabs-title">
            Product Details & Description
        </div>
        <div class="mp-description-content">
            <p style="margin-bottom: 14px;">
                {{ $product->description ?: 'মেঘদূতী পরিধান এর ঐতিহ্যবাহী জামদানি ও মণিপুরি শাড়ি কালেকশন। ১০০% খাঁটি সুতি ও তাতের কাজ, নিখুঁত আঁচল ও পাড়ের নকশা। বিশেষ উৎসব, অনুষ্ঠান এবং নিয়মিত ব্যবহারের জন্য অত্যন্ত মানানসই। ডেলিভারি সারা বাংলাদেশে।' }}
            </p>
            @if($product->more_information)
                <div style="background: #F9FAFB; padding: 14px 18px; border-radius: 6px; border: 1px solid #E5E7EB; margin-top: 14px;">
                    <strong style="color: #111827; display: block; margin-bottom: 4px;">Fabric & Specifications:</strong>
                    {{ $product->more_information }}
                </div>
            @endif
        </div>
    </div>

    <!-- Related Products (5-Column Grid Matching Screenshot) -->
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
        <div class="section-header-row">
            <h3 class="section-title-bold">Related Products</h3>
            <a href="{{ route('shop.index', ['category' => $product->category?->slug]) }}" class="btn-view-more-green">View All</a>
        </div>

        <div class="products-5-col-grid">
            @foreach($relatedProducts->take(5) as $relProduct)
                <div class="product-card-meghduti">
                    <div class="p-img-wrap">
                        <a href="{{ route('product.show', $relProduct->slug) }}">
                            <img src="{{ $relProduct->thumbnail_url }}" alt="{{ $relProduct->name }}" loading="lazy">
                        </a>
                        <div class="p-watermark-overlay">
                            <div class="p-watermark-badge">
                                <i class="fa-solid fa-gem p-watermark-icon"></i>
                                <span class="p-watermark-text">Meghduti Paridhaan</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-details-box">
                        <a href="{{ route('product.show', $relProduct->slug) }}" class="p-title-link" title="{{ $relProduct->name }}">
                            {{ $relProduct->name }}
                        </a>

                        <div class="p-price-val">
                            ৳{{ number_format($relProduct->current_price) }}
                        </div>

                        <div class="p-actions-row-top">
                            <button type="button" class="btn-add-cart-outline" onclick="addToCartAjax({{ $relProduct->id }})">
                                <i class="fa-solid fa-cart-shopping"></i> Add to Cart
                            </button>
                            <button type="button" class="btn-wishlist-heart {{ in_array($relProduct->id, $wishlistIds ?? []) ? 'active in-wishlist' : '' }}" data-wishlist-id="{{ $relProduct->id }}" onclick="toggleWishlist({{ $relProduct->id }}, this)" title="Wishlist">
                                <i class="{{ in_array($relProduct->id, $wishlistIds ?? []) ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>
                            </button>
                        </div>

                        <a href="{{ route('checkout.index', ['direct_product_id' => $relProduct->id]) }}" class="btn-buy-now-full">
                            <i class="fa-solid fa-cart-shopping" style="font-size: 11px;"></i> Buy Now
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    // GA4 & Meta Pixel: view_item / ViewContent Tracking
    document.addEventListener('DOMContentLoaded', function() {
        if (window.trackEcommerceEvent) {
            window.trackEcommerceEvent('view_item', {
                ecommerce: {
                    currency: 'BDT',
                    value: {{ (float) $product->current_price }},
                    items: [{
                        item_id: '{{ $product->id }}',
                        item_name: '{{ addslashes($product->name) }}',
                        price: {{ (float) $product->current_price }},
                        item_category: '{{ addslashes($product->category->name ?? "Boutique") }}',
                        item_brand: 'Meghduti Paridhaan',
                        quantity: 1
                    }]
                }
            }, 'ViewContent', {
                content_name: '{{ addslashes($product->name) }}',
                content_ids: ['{{ $product->id }}'],
                content_type: 'product',
                value: {{ (float) $product->current_price }},
                currency: 'BDT'
            });
        }
    });

    const productVariants = {!! json_encode($variantsJson) !!};
    const baseProductPrice = {{ (float) $product->current_price }};
    const hasColors = {{ $productColors->count() > 0 ? 'true' : 'false' }};
    const hasSizes  = {{ $productSizes->count() > 0 ? 'true' : 'false' }};

    let selectedColorId = null;
    let selectedSizeId  = null;

    function changeMainImage(url, elem) {
        if (!url) return;
        const mainImg = document.getElementById('mainProductPhoto');
        if (mainImg) mainImg.src = url;

        document.querySelectorAll('.gallery-thumb-box').forEach(el => {
            if (el === elem || el.getAttribute('data-img-url') === url) {
                el.classList.add('active');
            } else {
                el.classList.remove('active');
            }
        });

        // If clicked directly on a thumb and it matches a color's image, select that color!
        if (elem) {
            const matchBtn = document.querySelector(`.color-swatch-btn[data-image-url="${url}"]`);
            if (matchBtn && matchBtn.getAttribute('data-color-id') != selectedColorId) {
                selectColor(
                    matchBtn.getAttribute('data-color-id'),
                    matchBtn.getAttribute('data-color-name'),
                    url,
                    matchBtn
                );
            }
        }
    }

    function selectColor(colorId, colorName, imageUrl, elem) {
        selectedColorId = colorId;

        document.querySelectorAll('.color-swatch-btn').forEach(btn => btn.classList.remove('active'));
        if (elem) elem.classList.add('active');

        const nameLabel = document.getElementById('selectedColorName');
        if (nameLabel) nameLabel.textContent = colorName;

        // Switch main image to this color's image
        if (imageUrl) {
            changeMainImage(imageUrl, null);
        }

        updateVariantInfo();
    }

    function selectSize(sizeId, sizeName, elem) {
        selectedSizeId = sizeId;

        document.querySelectorAll('.size-pill-btn').forEach(btn => btn.classList.remove('active'));
        if (elem) elem.classList.add('active');

        const nameLabel = document.getElementById('selectedSizeName');
        if (nameLabel) nameLabel.textContent = sizeName;

        updateVariantInfo();
    }

    function getMatchingVariant() {
        if (!productVariants || productVariants.length === 0) return null;

        if (hasColors && hasSizes) {
            if (!selectedColorId || !selectedSizeId) return null;
            return productVariants.find(v => v.color_id == selectedColorId && v.size_id == selectedSizeId) || null;
        } else if (hasColors) {
            if (!selectedColorId) return null;
            return productVariants.find(v => v.color_id == selectedColorId) || null;
        } else if (hasSizes) {
            if (!selectedSizeId) return null;
            return productVariants.find(v => v.size_id == selectedSizeId) || null;
        }
        return productVariants[0] || null;
    }

    function updateVariantInfo() {
        const variant = getMatchingVariant();

        // Update price
        const priceDisplay = document.getElementById('productPriceDisplay');
        if (variant && variant.price_adjustment) {
            const finalPrice = baseProductPrice + parseFloat(variant.price_adjustment);
            if (priceDisplay) priceDisplay.textContent = '৳ ' + Math.round(finalPrice).toLocaleString();
        } else if (priceDisplay) {
            priceDisplay.textContent = '৳ ' + Math.round(baseProductPrice).toLocaleString();
        }

        // Update stock
        const stockDisplay = document.getElementById('productStockDisplay');
        if (stockDisplay && variant) {
            if (variant.stock > 0) {
                stockDisplay.innerHTML = '<i class="fa-solid fa-circle-check"></i> In Stock (' + variant.stock + ' available)';
                stockDisplay.style.color = '#16A34A';
            } else {
                stockDisplay.innerHTML = '<i class="fa-solid fa-circle-xmark"></i> Out of Stock';
                stockDisplay.style.color = '#DC2626';
            }
        }

        // If variant has an image and it's not active, switch
        if (variant && variant.image_url) {
            changeMainImage(variant.image_url, null);
        }
    }

    function stepQty(delta) {
        const input = document.getElementById('detailQtyInput');
        let val = parseInt(input.value) || 1;
        val = Math.max(1, Math.min(50, val + delta));
        input.value = val;
    }

    function validateSelection() {
        if (hasColors && !selectedColorId) {
            if (typeof showToast === 'function') {
                showToast('অনুগ্রহ করে একটি কালার (Color) সিলেক্ট করুন!');
            } else {
                alert('অনুগ্রহ করে একটি কালার (Color) সিলেক্ট করুন!');
            }
            const box = document.querySelector('.color-options-flex');
            if (box) {
                box.classList.add('selection-needed');
                setTimeout(() => box.classList.remove('selection-needed'), 800);
            }
            return false;
        }

        if (hasSizes && !selectedSizeId) {
            if (typeof showToast === 'function') {
                showToast('অনুগ্রহ করে একটি সাইজ (Size) সিলেক্ট করুন!');
            } else {
                alert('অনুগ্রহ করে একটি সাইজ (Size) সিলেক্ট করুন!');
            }
            const box = document.querySelector('.size-options-flex');
            if (box) {
                box.classList.add('selection-needed');
                setTimeout(() => box.classList.remove('selection-needed'), 800);
            }
            return false;
        }

        const variant = getMatchingVariant();
        if ((hasColors || hasSizes) && !variant) {
            if (typeof showToast === 'function') {
                showToast('দুঃখিত, এই কালার ও সাইজ কম্বিনেশনটি পাওয়া যায়নি।');
            } else {
                alert('দুঃখিত, এই কালার ও সাইজ কম্বিনেশনটি পাওয়া যায়নি।');
            }
            return false;
        }

        if (variant && variant.stock <= 0) {
            if (typeof showToast === 'function') {
                showToast('দুঃখিত, নির্বাচিত ভ্যারিয়েন্টটি আউট অফ স্টক!');
            } else {
                alert('দুঃখিত, নির্বাচিত ভ্যারিয়েন্টটি আউট অফ স্টক!');
            }
            return false;
        }

        return variant ? variant.id : null;
    }

    function addCurrentToCart() {
        let variantId = null;
        if (hasColors || hasSizes) {
            variantId = validateSelection();
            if (variantId === false) return;
        }
        const qty = parseInt(document.getElementById('detailQtyInput').value) || 1;
        addToCartAjax({{ $product->id }}, variantId, qty);
    }

    function buyCurrentNow() {
        let variantId = null;
        if (hasColors || hasSizes) {
            variantId = validateSelection();
            if (variantId === false) return;
        }
        const qty = parseInt(document.getElementById('detailQtyInput').value) || 1;
        
        let checkoutUrl = `{{ route('checkout.index') }}?direct_product_id={{ $product->id }}&direct_qty=${qty}`;
        if (variantId) {
            checkoutUrl += `&direct_variant_id=${variantId}`;
        }
        window.location.href = checkoutUrl;
    }
</script>
@endpush
