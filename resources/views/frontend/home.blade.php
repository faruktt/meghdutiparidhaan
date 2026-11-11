@extends('layouts.app')

@section('title', 'Meghduti Paridhaan - Exclusive Saree & Women Boutique Collection')

@push('styles')
<style>
    /* =========================================================
       DYNAMIC HERO BANNER & SLIDER
       ========================================================= */
    .hero-slider-section {
        position: relative;
        width: 100%;
        overflow: hidden;
        background: #111827;
    }
    .hero-slider-track {
        display: flex;
        transition: transform 0.5s ease-in-out;
        width: 100%;
    }
    .hero-slide-item {
        min-width: 100%;
        width: 100%;
        flex-shrink: 0;
        position: relative;
        display: block;
        line-height: 0;
    }
    .hero-slide-img {
        width: 100%;
        height: auto;
        max-height: 520px;
        object-fit: cover;
        display: block;
    }
    @media (max-width: 768px) {
        .hero-slide-img {
            min-height: 160px;
            max-height: 320px;
            object-fit: cover;
        }
    }
    .hero-slide-overlay {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        padding: 40px 8%;
        background: linear-gradient(90deg, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0.2) 50%, rgba(0,0,0,0) 100%);
        line-height: 1.4;
    }
    .hero-slide-content {
        max-width: 600px;
        color: #FFFFFF;
    }
    .hero-slide-title {
        font-family: 'Playfair Display', 'Cinzel', serif;
        font-size: 38px;
        font-weight: 700;
        color: #FFFFFF;
        margin-bottom: 8px;
        text-shadow: 0 2px 10px rgba(0,0,0,0.5);
    }
    .hero-slide-subtitle {
        font-size: 16px;
        color: rgba(255,255,255,0.92);
        margin-bottom: 16px;
        text-shadow: 0 1px 6px rgba(0,0,0,0.4);
    }
    .hero-slider-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.85);
        color: #1F2937;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        transition: all 0.2s;
        box-shadow: 0 2px 10px rgba(0,0,0,0.25);
    }
    .hero-slider-nav:hover {
        background: #FFFFFF;
        color: var(--brand-maroon);
        transform: translateY(-50%) scale(1.08);
    }
    .hero-slider-prev { left: 16px; }
    .hero-slider-next { right: 16px; }
    .hero-slider-dots {
        position: absolute;
        bottom: 14px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 8px;
        z-index: 10;
    }
    .hero-slider-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.45);
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    .hero-slider-dot.active {
        background: #FFFFFF;
        width: 26px;
        border-radius: 10px;
    }

    /* =========================================================
       HERO BANNER SECTION (Exact Match to Screenshot)
       ========================================================= */
    .hero-boutique-banner {
        background-color: var(--brand-maroon);
        width: 100%;
        min-height: 380px;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
    }
    .hero-banner-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        width: 100%;
        min-height: 380px;
    }

    /* Decorative Fireworks/Burst Line-art in Background */
    .hero-burst-decor-left {
        position: absolute;
        left: -40px;
        top: 20px;
        width: 220px;
        height: 220px;
        opacity: 0.35;
        pointer-events: none;
    }
    .hero-burst-decor-right {
        position: absolute;
        right: 42%;
        top: 25px;
        width: 140px;
        height: 140px;
        opacity: 0.25;
        pointer-events: none;
    }

    /* Left Typography */
    .hero-title-area {
        flex: 1;
        padding: 40px 20px 40px 60px;
        z-index: 2;
    }
    .hero-main-title {
        font-family: 'Cinzel', 'Playfair Display', serif;
        color: #FFFFFF;
        font-size: 44px;
        font-weight: 700;
        line-height: 1.15;
        letter-spacing: 5px;
        text-transform: uppercase;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    }
    .hero-main-title span {
        display: block;
    }

    /* Right Curved Photo Frame */
    .hero-image-arch-wrap {
        width: 48%;
        height: 380px;
        position: relative;
        overflow: hidden;
        border-top-left-radius: 200px;
        border-bottom-left-radius: 200px;
        box-shadow: -10px 0 30px rgba(0, 0, 0, 0.25);
        border-left: 3px solid rgba(255, 255, 255, 0.2);
    }
    .hero-arch-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        display: block;
        transition: transform 0.5s ease;
    }
    .hero-image-arch-wrap:hover .hero-arch-img {
        transform: scale(1.03);
    }

    @media (max-width: 900px) {
        .hero-banner-inner {
            flex-direction: column;
            text-align: center;
            padding: 30px 16px;
        }
        .hero-title-area {
            padding: 20px 0;
        }
        .hero-main-title {
            font-size: 32px;
            letter-spacing: 3px;
        }
        .hero-image-arch-wrap {
            width: 100%;
            height: 240px;
            border-radius: 12px;
            border-left: none;
        }
    }

    /* =========================================================
       SECTION HEADINGS
       ========================================================= */
    .section-header-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin: 32px 0 16px;
    }
    .section-title-bold {
        font-size: 18px;
        font-weight: 700;
        color: #1F2937;
        margin: 0;
    }
    .btn-all-categories {
        padding: 6px 16px;
        border: 1px solid #D1D5DB;
        border-radius: 4px;
        background: #FFFFFF;
        color: #4B5563;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-all-categories:hover {
        border-color: var(--brand-green);
        color: var(--brand-green);
    }

    .btn-view-more-green {
        padding: 6px 18px;
        background: var(--brand-green);
        color: #FFFFFF;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        transition: background 0.2s;
    }
    .btn-view-more-green:hover {
        background: var(--brand-green-hover);
        color: #FFFFFF;
    }

    /* =========================================================
       CATEGORIES CAROUSEL
       ========================================================= */
    .categories-carousel-wrap {
        position: relative;
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
    }
    .cat-nav-arrow {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #FFFFFF;
        border: 1px solid #D1D5DB;
        color: #6B7280;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        transition: all 0.2s;
        flex-shrink: 0;
        z-index: 2;
    }
    .cat-nav-arrow:hover {
        background: #F3F4F6;
        color: #111827;
        border-color: #9CA3AF;
    }
    .categories-track {
        display: flex;
        align-items: center;
        gap: 14px;
        overflow-x: auto;
        scroll-behavior: smooth;
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none; /* IE */
        padding: 4px 2px;
        flex: 1;
    }
    .categories-track::-webkit-scrollbar {
        display: none;
    }

    .category-box-card {
        min-width: 130px;
        padding: 16px 12px;
        background: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        transition: all 0.2s ease;
        text-align: center;
        flex-shrink: 0;
    }
    .category-box-card:hover {
        border-color: var(--brand-green);
        box-shadow: 0 4px 12px rgba(30, 126, 52, 0.08);
        transform: translateY(-2px);
    }
    .category-box-icon {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--brand-green);
        font-size: 26px;
    }
    .category-box-name {
        font-size: 12.5px;
        font-weight: 700;
        color: #374151;
        line-height: 1.2;
    }

    /* =========================================================
       5-COLUMN PRODUCT GRID SYSTEM (Exact Match to Screenshot)
       ========================================================= */
    .products-5-col-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 1200px) {
        .products-5-col-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; }
    }
    @media (max-width: 900px) {
        .products-5-col-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 12px; }
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
        position: relative;
        transition: all 0.25s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }
    .product-card-meghduti:hover {
        border-color: #D1D5DB;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.07);
        transform: translateY(-2px);
    }

    /* Product Image Wrap with Center Watermark */
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
        transition: transform 0.3s ease;
    }
    .product-card-meghduti:hover .p-img-wrap img {
        transform: scale(1.04);
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
    .p-watermark-icon {
        font-size: 11px;
    }
    .p-watermark-text {
        display: none;
    }

    /* Product Info */
    .p-details-box {
        display: flex;
        flex-direction: column;
        flex: 1;
        text-align: center;
    }
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
    }
    .p-title-link:hover {
        color: var(--brand-green);
    }

    .p-price-val {
        font-size: 14px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 10px;
    }

    /* Action Buttons (Exact Match to Screenshot) */
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
        transition: all 0.2s;
    }
    .btn-add-cart-outline:hover {
        border-color: var(--brand-green);
        color: var(--brand-green);
        background: #F0FDF4;
    }
    .btn-add-cart-outline i {
        color: var(--brand-green);
        font-size: 12px;
    }

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
        font-size: 12px;
        transition: all 0.2s;
    }
    .btn-wishlist-heart:hover {
        color: #EF4444;
        border-color: #EF4444;
    }

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
        transition: background 0.2s;
    }
    .btn-buy-now-full:hover {
        background: var(--brand-green-hover);
        color: #FFFFFF;
    }

    /* Out of Stock Button (Red badge as seen on image row 2, col 5) */
    .btn-out-of-stock {
        width: 100%;
        height: 32px;
        background: #DC2626;
        color: #FFFFFF;
        border: none;
        border-radius: 4px;
        font-size: 11.5px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: not-allowed;
    }

    /* Load More Button (Bottom of All Products) */
    .load-more-center {
        text-align: center;
        margin: 20px 0 40px;
    }
    .btn-load-more-green {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 9px 36px;
        background: var(--brand-green);
        color: #FFFFFF;
        border: none;
        border-radius: 4px;
        font-size: 12.5px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s;
        text-decoration: none;
    }
    .btn-load-more-green:hover {
        background: var(--brand-green-hover);
        color: #FFFFFF;
    }
</style>
@endpush

@section('content')

<!-- 1. Hero Banner (Dynamic Admin Banners with Fallback Boutique Design) -->
@if(isset($heroBanners) && $heroBanners->count() > 0)
    <section class="hero-slider-section" id="heroSlider">
        <div class="hero-slider-track" id="heroTrack">
            @foreach($heroBanners as $index => $banner)
                <div class="hero-slide-item">
                    @if($banner->link)
                        <a href="{{ $banner->link }}" style="display: block; line-height: 0;">
                    @endif
                        <img src="{{ $banner->image_url }}" alt="{{ $banner->title ?? 'Meghduti Paridhaan' }}" class="hero-slide-img" loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                        @if($banner->title || $banner->subtitle)
                            <div class="hero-slide-overlay">
                                <div class="hero-slide-content">
                                    @if($banner->title)
                                        <h2 class="hero-slide-title">{{ $banner->title }}</h2>
                                    @endif
                                    @if($banner->subtitle)
                                        <p class="hero-slide-subtitle">{{ $banner->subtitle }}</p>
                                    @endif
                                    @if($banner->link)
                                        <span class="btn-buy-now-full" style="display: inline-flex; width: auto; padding: 8px 22px; font-size: 13px;">Shop Now <i class="fa-solid fa-arrow-right" style="margin-left: 6px;"></i></span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @if($banner->link)
                        </a>
                    @endif
                </div>
            @endforeach
        </div>

        @if($heroBanners->count() > 1)
            <button type="button" class="hero-slider-nav hero-slider-prev" onclick="prevHeroSlide()" aria-label="Previous Slide">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <button type="button" class="hero-slider-nav hero-slider-next" onclick="nextHeroSlide()" aria-label="Next Slide">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
            <div class="hero-slider-dots" id="heroDots">
                @foreach($heroBanners as $idx => $b)
                    <button type="button" class="hero-slider-dot {{ $idx === 0 ? 'active' : '' }}" onclick="goToHeroSlide({{ $idx }})" aria-label="Slide {{ $idx + 1 }}"></button>
                @endforeach
            </div>
        @endif
    </section>
@else
    <!-- Default Boutique Maroon Banner Fallback -->
    <section class="hero-boutique-banner">
        <!-- Decorative Fireworks Background SVG -->
        <svg class="hero-burst-decor-left" viewBox="0 0 100 100" fill="none" stroke="#FFFFFF" stroke-width="1.2">
            <line x1="50" y1="50" x2="10" y2="10" />
            <line x1="50" y1="50" x2="20" y2="3" />
            <line x1="50" y1="50" x2="35" y2="0" />
            <line x1="50" y1="50" x2="5" y2="25" />
            <line x1="50" y1="50" x2="0" y2="45" />
            <line x1="50" y1="50" x2="5" y2="65" />
            <line x1="50" y1="50" x2="15" y2="85" />
            <line x1="50" y1="50" x2="35" y2="95" />
            <circle cx="10" cy="10" r="1.5" fill="#FFFFFF" />
            <circle cx="20" cy="3" r="1.5" fill="#FFFFFF" />
            <circle cx="0" cy="45" r="1.5" fill="#FFFFFF" />
            <circle cx="15" cy="85" r="1.5" fill="#FFFFFF" />
        </svg>

        <svg class="hero-burst-decor-right" viewBox="0 0 100 100" fill="none" stroke="#FFFFFF" stroke-width="1">
            <line x1="50" y1="50" x2="90" y2="10" />
            <line x1="50" y1="50" x2="80" y2="0" />
            <line x1="50" y1="50" x2="98" y2="30" />
            <line x1="50" y1="50" x2="100" y2="50" />
            <circle cx="90" cy="10" r="1.5" fill="#FFFFFF" />
            <circle cx="98" cy="30" r="1.5" fill="#FFFFFF" />
        </svg>

        <div class="container" style="padding: 0;">
            <div class="hero-banner-inner">
                <!-- Left Typography: MEGHDUTI PARIDHAAN -->
                <div class="hero-title-area">
                    <h1 class="hero-main-title">
                        <span>MEGHDUTI</span>
                        <span>PARIDHAAN</span>
                    </h1>
                </div>

                <!-- Right: Curved Arch Frame with Sarees & Boutique Photo -->
                <div class="hero-image-arch-wrap">
                    <img src="https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&w=1200&q=85" 
                         alt="Meghduti Paridhaan Sarees Collection" 
                         class="hero-arch-img">
                </div>
            </div>
        </div>
    </section>
@endif

<div class="container">

    <!-- 2. Categories Section (Exact Match) -->
    <div class="section-header-row">
        <h3 class="section-title-bold">Categories</h3>
        <a href="{{ route('shop.index') }}" class="btn-all-categories">All Categories</a>
    </div>

    <div class="categories-carousel-wrap">
        <button type="button" class="cat-nav-arrow" onclick="scrollCat(-200)" aria-label="Previous Categories">
            <i class="fa-solid fa-chevron-left"></i>
        </button>

        <div class="categories-track" id="catTrack">
            <!-- Woman Clothing (Category shown in screenshot) -->
            <a href="{{ route('shop.index', ['category' => 'woman-clothing']) }}" class="category-box-card">
                <div class="category-box-icon">
                    <!-- Green Woman Dress SVG -->
                    <svg viewBox="0 0 24 24" width="34" height="34" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 2l4 7-3 1-1 12H8L7 10 4 9l4-7h8z"/>
                        <path d="M9 2a3 3 0 0 0 6 0"/>
                    </svg>
                </div>
                <span class="category-box-name">Woman Clothing</span>
            </a>

            @foreach($categories as $cat)
                @if($cat->slug !== 'woman-clothing')
                    <a href="{{ route('shop.index', ['category' => $cat->slug]) }}" class="category-box-card">
                        <div class="category-box-icon">
                            @if(str_contains(strtolower($cat->name), 'jamdani'))
                                <i class="fa-solid fa-wand-magic-sparkles"></i>
                            @elseif(str_contains(strtolower($cat->name), 'manipuri'))
                                <i class="fa-solid fa-feather-pointed"></i>
                            @elseif(str_contains(strtolower($cat->name), 'silk'))
                                <i class="fa-solid fa-gem"></i>
                            @else
                                <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 2l4 7-3 1-1 12H8L7 10 4 9l4-7h8z"/>
                                    <path d="M9 2a3 3 0 0 0 6 0"/>
                                </svg>
                            @endif
                        </div>
                        <span class="category-box-name">{{ $cat->name }}</span>
                    </a>
                @endif
            @endforeach
        </div>

        <button type="button" class="cat-nav-arrow" onclick="scrollCat(200)" aria-label="Next Categories">
            <i class="fa-solid fa-chevron-right"></i>
        </button>
    </div>

    <!-- 3. New Arrival Section -->
    @if(isset($newArrivalProducts) && $newArrivalProducts->count() > 0)
        <div class="section-header-row">
            <h3 class="section-title-bold" style="display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-sparkles" style="color: #059669; font-size: 16px;"></i>
                <span>New Arrival</span>
            </h3>
            <a href="{{ route('shop.index', ['section' => 'new-arrival']) }}" class="btn-view-more-green">View More</a>
        </div>

        <div class="products-5-col-grid">
            @foreach($newArrivalProducts as $product)
                <div class="product-card-meghduti">
                    
                    <!-- Image with Watermark -->
                    <div class="p-img-wrap">
                        <a href="{{ route('product.show', $product->slug) }}">
                            <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" loading="lazy">
                        </a>
                        
                        <!-- Center Watermark Emblem -->
                        <div class="p-watermark-overlay">
                            <div class="p-watermark-badge">
                                <i class="fa-solid fa-gem p-watermark-icon"></i>
                                <span class="p-watermark-text">Meghduti Paridhaan</span>
                            </div>
                        </div>
                    </div>

                    <!-- Product Details -->
                    <div class="p-details-box">
                        <a href="{{ route('product.show', $product->slug) }}" class="p-title-link" title="{{ $product->name }}">
                            {{ $product->name }}
                        </a>

                        <div class="p-price-val">
                            ৳{{ number_format($product->current_price) }}
                        </div>

                        <!-- Action Buttons: Add to Cart + Wishlist -->
                        <div class="p-actions-row-top">
                            <button type="button" class="btn-add-cart-outline" onclick="addToCartAjax({{ $product->id }})">
                                <i class="fa-solid fa-cart-shopping"></i> Add to Cart
                            </button>
                            <button type="button" class="btn-wishlist-heart {{ in_array($product->id, $wishlistIds ?? []) ? 'active in-wishlist' : '' }}" data-wishlist-id="{{ $product->id }}" onclick="toggleWishlist({{ $product->id }}, this)" title="Wishlist">
                                <i class="{{ in_array($product->id, $wishlistIds ?? []) ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>
                            </button>
                        </div>

                        <!-- Buy Now Button (Direct checkout with tracking) -->
                        @if($product->total_stock > 0)
                            <button type="button" class="btn-buy-now-full" onclick="buyNowWithTracking({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->current_price }}, '{{ addslashes($product->category->name ?? 'Boutique') }}')">
                                <i class="fa-solid fa-cart-shopping" style="font-size: 11px;"></i> Buy Now
                            </button>
                        @else
                            <button type="button" class="btn-out-of-stock" disabled>
                                Out of Stock
                            </button>
                        @endif
                    </div>

                </div>
            @endforeach
        </div>
    @endif

    <!-- 4. Top Selling Section -->
    @if(isset($topSellingProducts) && $topSellingProducts->count() > 0)
        <div class="section-header-row" style="margin-top: 36px;">
            <h3 class="section-title-bold" style="display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-fire" style="color: #D97706; font-size: 16px;"></i>
                <span>Top Selling</span>
            </h3>
            <a href="{{ route('shop.index', ['section' => 'top-selling']) }}" class="btn-view-more-green">View More</a>
        </div>

        <div class="products-5-col-grid">
            @foreach($topSellingProducts as $product)
                <div class="product-card-meghduti">
                    
                    <!-- Image with Watermark -->
                    <div class="p-img-wrap">
                        <a href="{{ route('product.show', $product->slug) }}">
                            <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" loading="lazy">
                        </a>
                        
                        <!-- Center Watermark Emblem -->
                        <div class="p-watermark-overlay">
                            <div class="p-watermark-badge">
                                <i class="fa-solid fa-gem p-watermark-icon"></i>
                                <span class="p-watermark-text">Meghduti Paridhaan</span>
                            </div>
                        </div>
                    </div>

                    <!-- Product Details -->
                    <div class="p-details-box">
                        <a href="{{ route('product.show', $product->slug) }}" class="p-title-link" title="{{ $product->name }}">
                            {{ $product->name }}
                        </a>

                        <div class="p-price-val">
                            ৳{{ number_format($product->current_price) }}
                        </div>

                        <!-- Action Buttons: Add to Cart + Wishlist -->
                        <div class="p-actions-row-top">
                            <button type="button" class="btn-add-cart-outline" onclick="addToCartAjax({{ $product->id }})">
                                <i class="fa-solid fa-cart-shopping"></i> Add to Cart
                            </button>
                            <button type="button" class="btn-wishlist-heart {{ in_array($product->id, $wishlistIds ?? []) ? 'active in-wishlist' : '' }}" data-wishlist-id="{{ $product->id }}" onclick="toggleWishlist({{ $product->id }}, this)" title="Wishlist">
                                <i class="{{ in_array($product->id, $wishlistIds ?? []) ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>
                            </button>
                        </div>

                        <!-- Buy Now Button (Direct checkout with tracking) -->
                        @if($product->total_stock > 0)
                            <button type="button" class="btn-buy-now-full" onclick="buyNowWithTracking({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->current_price }}, '{{ addslashes($product->category->name ?? 'Boutique') }}')">
                                <i class="fa-solid fa-cart-shopping" style="font-size: 11px;"></i> Buy Now
                            </button>
                        @else
                            <button type="button" class="btn-out-of-stock" disabled>
                                Out of Stock
                            </button>
                        @endif
                    </div>

                </div>
            @endforeach
        </div>
    @endif

    <!-- 5. All Products Section (Exact Match - 5 Column Grid) -->
    <div class="section-header-row" style="margin-top: 40px;">
        <h3 class="section-title-bold">All Products</h3>
    </div>

    <div class="products-5-col-grid" id="allProductsContainer">
        @foreach($allProducts as $product)
            <div class="product-card-meghduti">
                
                <!-- Image with Watermark -->
                <div class="p-img-wrap">
                    <a href="{{ route('product.show', $product->slug) }}">
                        <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" loading="lazy">
                    </a>

                    <!-- Center Watermark Emblem -->
                    <div class="p-watermark-overlay">
                        <div class="p-watermark-badge">
                            <i class="fa-solid fa-gem p-watermark-icon"></i>
                            <span class="p-watermark-text">Meghduti Paridhaan</span>
                        </div>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="p-details-box">
                    <a href="{{ route('product.show', $product->slug) }}" class="p-title-link" title="{{ $product->name }}">
                        {{ $product->name }}
                    </a>

                    <div class="p-price-val">
                        ৳{{ number_format($product->current_price) }}
                    </div>

                    <!-- Action Buttons: Add to Cart + Wishlist -->
                    <div class="p-actions-row-top">
                        <button type="button" class="btn-add-cart-outline" onclick="addToCartAjax({{ $product->id }})">
                            <i class="fa-solid fa-cart-shopping"></i> Add to Cart
                        </button>
                        <button type="button" class="btn-wishlist-heart {{ in_array($product->id, $wishlistIds ?? []) ? 'active in-wishlist' : '' }}" data-wishlist-id="{{ $product->id }}" onclick="toggleWishlist({{ $product->id }}, this)" title="Wishlist">
                            <i class="{{ in_array($product->id, $wishlistIds ?? []) ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>
                        </button>
                    </div>

                    <!-- Full Width Button (Buy Now or Out of Stock) -->
                    @if($product->total_stock > 0)
                        <button type="button" class="btn-buy-now-full" onclick="buyNowWithTracking({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->current_price }}, '{{ addslashes($product->category->name ?? 'Boutique') }}')">
                            <i class="fa-solid fa-cart-shopping" style="font-size: 11px;"></i> Buy Now
                        </button>
                    @else
                        <!-- Red Out of Stock button as in screenshot row 2 col 5 -->
                        <button type="button" class="btn-out-of-stock" disabled>
                            Out of Stock
                        </button>
                    @endif
                </div>

            </div>
        @endforeach
    </div>

    <!-- Centered Load More Button -->
    <div class="load-more-center">
        <a href="{{ route('shop.index') }}" class="btn-load-more-green">
            Load More
        </a>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // GA4: view_item_list Tracking
    document.addEventListener('DOMContentLoaded', function() {
        if (window.trackEcommerceEvent && {{ $trendingProducts->count() > 0 ? 'true' : 'false' }}) {
            window.trackEcommerceEvent('view_item_list', {
                ecommerce: {
                    item_list_name: 'Featured & Trending Saree Boutique',
                    items: [
                        @foreach($trendingProducts as $idx => $tp)
                        {
                            item_id: '{{ $tp->id }}',
                            item_name: '{{ addslashes($tp->name) }}',
                            price: {{ (float) $tp->current_price }},
                            index: {{ $idx + 1 }},
                            item_category: '{{ addslashes($tp->category->name ?? "Boutique") }}',
                            item_brand: 'Meghduti Paridhaan'
                        }{{ !$loop->last ? ',' : '' }}
                        @endforeach
                    ]
                }
            });
        }
    });

    function scrollCat(distance) {
        const track = document.getElementById('catTrack');
        if (track) {
            track.scrollBy({ left: distance, behavior: 'smooth' });
        }
    }

    // Hero Banner Slider
    let currentHeroSlide = 0;
    const heroTrack = document.getElementById('heroTrack');
    const heroDots = document.getElementById('heroDots');
    const heroSlides = heroTrack ? heroTrack.querySelectorAll('.hero-slide-item') : [];
    let heroAutoInterval = null;

    function updateHeroSlide() {
        if (!heroTrack || heroSlides.length <= 1) return;
        heroTrack.style.transform = `translateX(-${currentHeroSlide * 100}%)`;
        if (heroDots) {
            const dots = heroDots.querySelectorAll('.hero-slider-dot');
            dots.forEach((dot, idx) => {
                dot.classList.toggle('active', idx === currentHeroSlide);
            });
        }
    }

    function nextHeroSlide() {
        if (heroSlides.length <= 1) return;
        currentHeroSlide = (currentHeroSlide + 1) % heroSlides.length;
        updateHeroSlide();
    }

    function prevHeroSlide() {
        if (heroSlides.length <= 1) return;
        currentHeroSlide = (currentHeroSlide - 1 + heroSlides.length) % heroSlides.length;
        updateHeroSlide();
    }

    function goToHeroSlide(index) {
        currentHeroSlide = index;
        updateHeroSlide();
        resetHeroAutoplay();
    }

    function startHeroAutoplay() {
        if (heroSlides.length > 1 && !heroAutoInterval) {
            heroAutoInterval = setInterval(nextHeroSlide, 5000);
        }
    }

    function stopHeroAutoplay() {
        if (heroAutoInterval) {
            clearInterval(heroAutoInterval);
            heroAutoInterval = null;
        }
    }

    function resetHeroAutoplay() {
        stopHeroAutoplay();
        startHeroAutoplay();
    }

    if (heroSlides.length > 1) {
        const sliderEl = document.getElementById('heroSlider');
        if (sliderEl) {
            sliderEl.addEventListener('mouseenter', stopHeroAutoplay);
            sliderEl.addEventListener('mouseleave', startHeroAutoplay);
        }
        startHeroAutoplay();
    }
</script>
@endpush
