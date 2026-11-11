<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $page->meta_title ?: $page->title . ' | ' . \App\Models\Setting::get('site_name', config('app.name', 'Meghduti Paridhaan')) }}</title>
    @if($page->meta_description)
        <meta name="description" content="{{ $page->meta_description }}">
    @endif

    <!-- Google Fonts: Hind Siliguri (Bangla) & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --primary: #EA580C;
            --primary-hover: #C2410C;
            --primary-light: #FFF7ED;
            --primary-border: #FED7AA;
            --dark: #0F172A;
            --text-dark: #1E293B;
            --text-muted: #64748B;
            --border-color: #E2E8F0;
            --bg-outer: #EEF2F6;
            --bg-card: #FFFFFF;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Hind Siliguri', 'Plus Jakarta Sans', -apple-system, sans-serif;
            background-color: var(--bg-outer);
            color: var(--text-dark);
            line-height: 1.5;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 0;
        }

        /* Desktop Container: Mobile Framed on Web View */
        .landing-viewport {
            width: 100%;
            max-width: 500px;
            min-height: 100vh;
            background: #FFFFFF;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.08), 0 1px 3px rgba(0, 0, 0, 0.04);
            border-left: 1px solid #E2E8F0;
            border-right: 1px solid #E2E8F0;
            position: relative;
            padding-bottom: 95px;
            overflow-x: hidden;
        }

        @media (max-width: 520px) {
            body {
                background-color: #FFFFFF;
            }
            .landing-viewport {
                border-left: none;
                border-right: none;
                box-shadow: none;
                max-width: 100%;
            }
        }

        /* 1. Dynamic Top Urgency Banner */
        .urgency-bar {
            background: #FFFBEB;
            border-bottom: 1.5px solid #FDE68A;
            padding: 10px 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 13.5px;
            font-weight: 700;
            color: #92400E;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        }
        .urgency-timer-pill {
            background: #FFFFFF;
            color: #B45309;
            border: 1.5px solid #F59E0B;
            padding: 2px 10px;
            border-radius: 6px;
            font-family: 'Plus Jakarta Sans', monospace;
            font-size: 14px;
            font-weight: 800;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        /* 2. Dynamic Feature Badges */
        .trust-two-box {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            padding: 16px 16px 12px 16px;
        }
        .trust-box-card {
            background: #FAFAFA;
            border: 1px solid #E5E7EB;
            border-radius: 10px;
            padding: 12px 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            gap: 6px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }
        .trust-box-icon {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #FFF7ED;
            color: #EA580C;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }
        .trust-box-card:nth-child(2) .trust-box-icon {
            background: #EFF6FF;
            color: #2563EB;
        }
        .trust-box-card span {
            font-size: 13.5px;
            font-weight: 700;
            color: #334155;
        }

        /* 3. Hero Campaign Card */
        .product-hero-wrap {
            padding: 0 16px 16px 16px;
        }
        .product-hero-card {
            background: #FFFFFF;
            border-radius: 12px;
            border: 1px solid #E5E7EB;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .hero-banner-img {
            width: 100%;
            height: auto;
            max-height: 400px;
            object-fit: cover;
            display: block;
        }
        .hero-info-box {
            padding: 16px;
            text-align: center;
        }
        .hero-main-title {
            font-size: 21px;
            font-weight: 800;
            color: #0F172A;
            line-height: 1.35;
            margin-bottom: 8px;
        }
        .hero-price-tag {
            display: inline-flex;
            align-items: baseline;
            gap: 8px;
            margin-bottom: 10px;
        }
        .current-hero-price {
            font-size: 24px;
            font-weight: 800;
            color: #EA580C;
        }
        .compare-hero-price {
            font-size: 15px;
            color: #94A3B8;
            text-decoration: line-through;
        }
        .hero-desc-text {
            font-size: 14px;
            color: #475569;
            line-height: 1.6;
            margin-bottom: 14px;
            text-align: left;
        }
        .btn-scroll-order {
            width: 100%;
            background: linear-gradient(135deg, #EA580C 0%, #C2410C 100%);
            color: #FFFFFF;
            font-size: 16px;
            font-weight: 800;
            padding: 13px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(234, 88, 12, 0.35);
            transition: transform 0.15s;
        }
        .btn-scroll-order:hover {
            transform: scale(0.99);
        }

        /* 4. Full Dynamic Details Section */
        .dynamic-details-section {
            padding: 0 16px 20px 16px;
        }
        .details-card {
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            padding: 18px 16px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.02);
        }
        .details-card-header {
            font-size: 17px;
            font-weight: 800;
            color: #0F172A;
            border-bottom: 1.5px solid #F1F5F9;
            padding-bottom: 10px;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .details-body-text {
            font-size: 14px;
            color: #334155;
            line-height: 1.7;
            white-space: pre-line;
            word-break: break-word;
        }

        /* 5. Product Select Section (RIGHT ABOVE CART / ORDER FORM) */
        .product-select-wrapper {
            margin-bottom: 18px;
        }
        .select-prod-header {
            font-size: 15.5px;
            font-weight: 800;
            color: #0F172A;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .multi-products-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .product-choice-row {
            background: #FFFFFF;
            border: 1.5px solid #CBD5E1;
            border-radius: 10px;
            padding: 10px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .product-choice-row.is-active {
            border-color: #EA580C;
            background: #FFF7ED;
        }
        .product-choice-thumb {
            width: 52px;
            height: 52px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid #E2E8F0;
            flex-shrink: 0;
        }
        .product-choice-meta {
            flex: 1;
            min-width: 0;
        }
        .product-choice-name {
            font-size: 13.5px;
            font-weight: 700;
            color: #1E293B;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .product-choice-price {
            font-size: 14.5px;
            font-weight: 800;
            color: #EA580C;
        }
        .radio-dot-check {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid #CBD5E1;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            flex-shrink: 0;
        }
        .product-choice-row.is-active .radio-dot-check {
            border-color: #EA580C;
            background: #EA580C;
        }
        .product-choice-row.is-active .radio-dot-check::after {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #FFFFFF;
        }

        /* 6. High-Converting Cash On Delivery Order Form */
        .order-form-container {
            padding: 0 16px;
        }
        .order-card {
            background: #FFFFFF;
            border: 1.5px solid #EA580C;
            border-radius: 14px;
            padding: 20px 16px;
            box-shadow: 0 6px 20px rgba(234, 88, 12, 0.08);
            position: relative;
        }
        .order-card-header {
            text-align: center;
            margin-bottom: 18px;
            border-bottom: 1px dashed #E2E8F0;
            padding-bottom: 14px;
        }
        .order-card-title {
            font-size: 21px;
            font-weight: 800;
            color: #0F172A;
            margin-bottom: 4px;
        }
        .order-card-subtitle {
            font-size: 13px;
            color: #EA580C;
            font-weight: 700;
        }

        /* Form Inputs */
        .form-group {
            margin-bottom: 14px;
        }
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: #1E293B;
            margin-bottom: 6px;
        }
        .form-label span.req {
            color: #EA580C;
        }
        .form-control-custom {
            width: 100%;
            padding: 11px 14px;
            font-size: 14px;
            border: 1.5px solid #CBD5E1;
            border-radius: 8px;
            color: #0F172A;
            background: #FFFFFF;
            outline: none;
            transition: all 0.2s;
            font-family: inherit;
        }
        .form-control-custom:focus {
            border-color: #EA580C;
            box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.12);
        }

        /* Delivery Area Selector (Radio Cards) */
        .zone-cards-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .zone-card-item {
            border: 1.5px solid #CBD5E1;
            border-radius: 8px;
            padding: 10px 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.2s;
            background: #FFFFFF;
        }
        .zone-card-item.is-selected {
            border-color: #16A34A;
            background: #F0FDF4;
        }
        .zone-card-item input[type="radio"] {
            margin-right: 8px;
            accent-color: #16A34A;
            width: 17px;
            height: 17px;
            cursor: pointer;
        }
        .zone-label-wrap {
            display: flex;
            align-items: center;
            flex: 1;
            cursor: pointer;
        }
        .zone-name-text {
            font-size: 13.5px;
            font-weight: 700;
            color: #1E293B;
        }
        .zone-charge-text {
            font-size: 14px;
            font-weight: 800;
            color: #16A34A;
        }

        /* Quantity & Selected Item Row */
        .order-item-row {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            padding: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 14px;
        }
        .order-item-left {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
            flex: 1;
        }
        .order-item-thumb {
            width: 44px;
            height: 44px;
            border-radius: 6px;
            object-fit: cover;
            border: 1px solid #CBD5E1;
            flex-shrink: 0;
        }
        .order-item-title {
            font-size: 13px;
            font-weight: 700;
            color: #0F172A;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .order-item-variant {
            font-size: 11.5px;
            color: #EA580C;
            font-weight: 600;
        }

        /* Quantity Counter */
        .quantity-stepper {
            display: inline-flex;
            align-items: center;
            border: 1.5px solid #CBD5E1;
            border-radius: 6px;
            background: #FFFFFF;
            overflow: hidden;
            flex-shrink: 0;
        }
        .qty-btn {
            background: #F1F5F9;
            border: none;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 800;
            color: #334155;
            cursor: pointer;
            transition: background 0.15s;
        }
        .qty-btn:hover {
            background: #E2E8F0;
        }
        .qty-input {
            width: 36px;
            height: 30px;
            border: none;
            text-align: center;
            font-size: 14px;
            font-weight: 800;
            color: #0F172A;
            font-family: inherit;
            outline: none;
        }

        /* Order Totals Table */
        .totals-table-box {
            background: #FFFFFF;
            border-top: 1px solid #E2E8F0;
            padding-top: 12px;
            margin-bottom: 16px;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            color: #64748B;
            margin-bottom: 6px;
        }
        .totals-row.grand-total {
            border-top: 1.5px dashed #CBD5E1;
            padding-top: 10px;
            margin-top: 8px;
            font-size: 16px;
            font-weight: 800;
            color: #0F172A;
        }
        .totals-row.grand-total span:last-child {
            color: #EA580C;
            font-size: 18px;
        }

        /* Big Order Confirm Button */
        .btn-confirm-order {
            width: 100%;
            background: #EA580C;
            color: #FFFFFF;
            font-size: 17px;
            font-weight: 800;
            padding: 14px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 14px rgba(234, 88, 12, 0.4);
            transition: all 0.2s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .btn-confirm-order:hover {
            background: #C2410C;
            transform: translateY(-1px);
        }

        .form-trust-footer {
            text-align: center;
            margin-top: 14px;
            font-size: 12px;
            color: #64748B;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .form-trust-footer i {
            color: #16A34A;
        }

        /* Sticky Bottom Bar */
        .sticky-footer-bar {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 500px;
            background: #FFFFFF;
            border-top: 1px solid #E2E8F0;
            padding: 10px 16px;
            box-shadow: 0 -4px 15px rgba(0, 0, 0, 0.08);
            z-index: 99;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .sticky-price-info {
            display: flex;
            flex-direction: column;
        }
        .sticky-label {
            font-size: 11px;
            color: #64748B;
            font-weight: 600;
        }
        .sticky-amount {
            font-size: 17px;
            font-weight: 800;
            color: #EA580C;
        }
        .btn-sticky-cta {
            background: #EA580C;
            color: #FFFFFF;
            padding: 10px 20px;
            border-radius: 99px;
            font-size: 14px;
            font-weight: 800;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 8px rgba(234, 88, 12, 0.3);
        }

        .alert-box {
            margin: 12px 16px;
            padding: 12px 14px;
            border-radius: 8px;
            font-size: 13.5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .alert-box.alert-error {
            background: #FEF2F2;
            color: #991B1B;
            border: 1px solid #FCA5A5;
        }
        .alert-box.alert-success {
            background: #F0FDF4;
            color: #166534;
            border: 1px solid #86EFAC;
        }
    </style>
</head>
<body>

    @php
        $firstProduct = $page->products->first();
        $firstVariant = $firstProduct ? $firstProduct->variants->first() : null;
        $defaultPrice = $firstProduct ? $firstProduct->current_price : 0;
    @endphp

    <!-- Main Viewport (Mobile Framed on Desktop) -->
    <div class="landing-viewport">

        <!-- 1. Top Urgency Banner (Dynamic) -->
        @if($page->urgency_text)
            <div class="urgency-bar">
                <span>{{ $page->urgency_text }}</span>
                <div class="urgency-timer-pill">
                    <i class="fa-regular fa-clock" style="font-size: 12px;"></i>
                    <span id="countdownDisplay">09:45</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert-box alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(session('success'))
            <div class="alert-box alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- 2. Dynamic Feature Badges (if entered) -->
        @if($page->feature_badge_1 || $page->feature_badge_2)
            <div class="trust-two-box">
                @if($page->feature_badge_1)
                    <div class="trust-box-card">
                        <div class="trust-box-icon">
                            <i class="fa-solid fa-truck-fast"></i>
                        </div>
                        <span>{{ $page->feature_badge_1 }}</span>
                    </div>
                @endif
                @if($page->feature_badge_2)
                    <div class="trust-box-card">
                        <div class="trust-box-icon">
                            <i class="fa-solid fa-headset"></i>
                        </div>
                        <span>{{ $page->feature_badge_2 }}</span>
                    </div>
                @endif
            </div>
        @endif

        <!-- 3. Dynamic Hero Showcase Banner -->
        <div class="product-hero-wrap">
            <div class="product-hero-card">
                @if($page->banner_image_url)
                    <img src="{{ $page->banner_image_url }}" alt="{{ $page->title }}" class="hero-banner-img">
                @elseif($firstProduct && $firstProduct->thumbnail_url)
                    <img src="{{ $firstProduct->thumbnail_url }}" alt="{{ $firstProduct->name }}" class="hero-banner-img">
                @endif

                <div class="hero-info-box">
                    <h1 class="hero-main-title">{{ $page->title }}</h1>
                    
                    @if($firstProduct)
                        <div class="hero-price-tag">
                            <span class="current-hero-price">৳{{ number_format($firstProduct->current_price) }}</span>
                            @if($firstProduct->compare_price && $firstProduct->compare_price > $firstProduct->current_price)
                                <span class="compare-hero-price">৳{{ number_format($firstProduct->compare_price) }}</span>
                            @endif
                        </div>
                    @endif

                    @if($page->description)
                        <div class="hero-desc-text">
                            {!! nl2br(e($page->description)) !!}
                        </div>
                    @endif

                    <a href="#orderSection" class="btn-scroll-order">
                        <i class="fa-solid fa-bag-shopping"></i> এখনই অর্ডার করুন
                    </a>
                </div>
            </div>
        </div>

        <!-- 4. Dynamic Details Section (If Admin enters full text details) -->
        @if($page->details)
            <div class="dynamic-details-section">
                <div class="details-card">
                    <div class="details-card-header">
                        <i class="fa-solid fa-circle-info" style="color: #EA580C;"></i> বিস্তারিত বিবরণ
                    </div>
                    <div class="details-body-text">
                        {!! nl2br(e($page->details)) !!}
                    </div>
                </div>
            </div>
        @endif

        <!-- 5 & 6. Order Section (PRODUCT SELECTOR IS PLACED RIGHT ABOVE CART / ORDER FORM) -->
        <div class="order-form-container" id="orderSection">
            <div class="order-card">

                <!-- Product Selection (Right above form fields) -->
                @if($page->products->count() > 1)
                    <div class="product-select-wrapper">
                        <div class="select-prod-header">
                            <i class="fa-solid fa-hand-pointer" style="color: #EA580C;"></i> আপনার পছন্দের প্রোডাক্ট সিলেক্ট করুন
                        </div>
                        <div class="multi-products-list">
                            @foreach($page->products as $idx => $prod)
                                <div class="product-choice-row {{ $idx === 0 ? 'is-active' : '' }}" 
                                     id="prod-row-{{ $prod->id }}"
                                     onclick="selectCampaignProduct({{ $prod->id }}, '{{ addslashes($prod->name) }}', {{ $prod->current_price }}, '{{ $prod->thumbnail_url }}')">
                                    <img src="{{ $prod->thumbnail_url }}" alt="{{ $prod->name }}" class="product-choice-thumb">
                                    <div class="product-choice-meta">
                                        <div class="product-choice-name">{{ $prod->name }}</div>
                                        <div class="product-choice-price">৳{{ number_format($prod->current_price) }}</div>
                                    </div>
                                    <div class="radio-dot-check"></div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="order-card-header">
                    <h2 class="order-card-title">এখনই অর্ডার করুন</h2>
                    <p class="order-card-subtitle">👉 ৩০ সেকেন্ডে অর্ডার সম্পন্ন করুন</p>
                </div>

                <form action="{{ route('landing.order', $page->slug) }}" method="POST" id="landingOrderForm">
                    @csrf
                    <input type="hidden" name="product_id" id="formProductId" value="{{ $firstProduct?->id }}">
                    <input type="hidden" name="variant_id" id="formVariantId" value="{{ $firstVariant?->id }}">
                    <input type="hidden" name="payment_method" value="cod">

                    <!-- Customer Name -->
                    <div class="form-group">
                        <label class="form-label">আপনার নাম <span class="req">*</span></label>
                        <input type="text" 
                               name="customer_name" 
                               class="form-control-custom" 
                               placeholder="আপনার পুরো নাম লিখুন" 
                               value="{{ old('customer_name') }}"
                               required>
                    </div>

                    <!-- Customer Phone -->
                    <div class="form-group">
                        <label class="form-label">মোবাইল নম্বর <span class="req">*</span></label>
                        <input type="tel" 
                               name="customer_phone" 
                               class="form-control-custom" 
                               placeholder="সঠিক ১১ ডিজিটের মোবাইল নম্বর" 
                               pattern="[0-9]{11}" 
                               title="সঠিক ১১ ডিজিটের নম্বর লিখুন (যেমন: 017XXXXXXXX)"
                               value="{{ old('customer_phone') }}"
                               required>
                    </div>

                    <!-- Customer Address -->
                    <div class="form-group">
                        <label class="form-label">ডেলিভারী ঠিকানা <span class="req">*</span></label>
                        <textarea name="customer_address" 
                                  rows="3" 
                                  class="form-control-custom" 
                                  placeholder="বাড়ি/হোল্ডিং, রোড নং, থানা, জেলা..." 
                                  required>{{ old('customer_address') }}</textarea>
                    </div>

                    <!-- Delivery Area Selector -->
                    <div class="form-group">
                        <label class="form-label">ডেলিভারি এলাকা <span class="req">*</span></label>
                        <div class="zone-cards-list">
                            @foreach($shippingZones as $zIdx => $zone)
                                <label class="zone-card-item {{ $zIdx === 0 ? 'is-selected' : '' }}" 
                                       onclick="updateDeliveryZone({{ $zone->charge }}, this)">
                                    <div class="zone-label-wrap">
                                        <input type="radio" 
                                               name="shipping_zone_id" 
                                               value="{{ $zone->id }}" 
                                               {{ $zIdx === 0 ? 'checked' : '' }}>
                                        <span class="zone-name-text">{{ $zone->name }}</span>
                                    </div>
                                    <span class="zone-charge-text">৳{{ number_format($zone->charge) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Quantity & Selected Item Preview -->
                    <div class="form-group">
                        <label class="form-label">পরিমাণ</label>
                        <div class="order-item-row">
                            <div class="order-item-left">
                                <img src="{{ $firstProduct?->thumbnail_url }}" 
                                     id="summaryImg" 
                                     alt="" 
                                     class="order-item-thumb">
                                <div>
                                    <div class="order-item-title" id="summaryTitle">{{ $firstProduct?->name }}</div>
                                    <div class="order-item-variant" id="summaryPriceEach">৳{{ number_format($defaultPrice) }}</div>
                                </div>
                            </div>
                            
                            <!-- Stepper -->
                            <div class="quantity-stepper">
                                <button type="button" class="qty-btn" onclick="changeQty(-1)">-</button>
                                <input type="number" 
                                       name="quantity" 
                                       id="orderQty" 
                                       class="qty-input" 
                                       value="1" 
                                       min="1" 
                                       max="10" 
                                       readonly>
                                <button type="button" class="qty-btn" onclick="changeQty(1)">+</button>
                            </div>
                        </div>
                    </div>

                    <!-- Order Price Breakdown -->
                    <div class="totals-table-box">
                        <div class="totals-row">
                            <span>সাবটোটাল:</span>
                            <strong id="displaySubtotal">৳{{ number_format($defaultPrice) }}</strong>
                        </div>
                        <div class="totals-row">
                            <span>ডেলিভারি:</span>
                            <strong id="displayShipping">৳{{ number_format($shippingCharge) }}</strong>
                        </div>
                        <div class="totals-row grand-total">
                            <span>সর্বমোট:</span>
                            <span id="displayGrandTotal">৳{{ number_format($defaultPrice + $shippingCharge) }}</span>
                        </div>
                    </div>

                    <!-- Large Confirm Order Button -->
                    <button type="submit" class="btn-confirm-order">
                        <i class="fa-solid fa-circle-check"></i> অর্ডার নিশ্চিত করুন
                    </button>

                    <div class="form-trust-footer">
                        <i class="fa-solid fa-lock"></i>
                        <span>১০০% ক্যাশ অন ডেলিভারি | পণ্য দেখে মূল্য পরিশোধ করুন</span>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- Mobile / Web Sticky Bottom Action Bar -->
    <div class="sticky-footer-bar">
        <div class="sticky-price-info">
            <span class="sticky-label">সর্বমোট টাকা</span>
            <span class="sticky-amount" id="stickyTotal">৳{{ number_format($defaultPrice + $shippingCharge) }}</span>
        </div>
        <a href="#orderSection" class="btn-sticky-cta" onclick="scrollAndFocus()">
            <i class="fa-solid fa-bag-shopping"></i> এখনই অর্ডার করুন
        </a>
    </div>

    <script>
        let currentItemPrice = {{ $defaultPrice }};
        let currentShipping = {{ $shippingCharge }};

        // 1. Live Countdown Timer (10 Minutes)
        function initCountdown() {
            let totalSeconds = 9 * 60 + 45; // 09:45
            const display = document.getElementById('countdownDisplay');
            if (!display) return;

            const timer = setInterval(() => {
                if (totalSeconds <= 0) {
                    clearInterval(timer);
                    display.innerText = "00:00";
                    return;
                }
                totalSeconds--;
                const mins = Math.floor(totalSeconds / 60);
                const secs = totalSeconds % 60;
                display.innerText = 
                    (mins < 10 ? '0' : '') + mins + ':' + 
                    (secs < 10 ? '0' : '') + secs;
            }, 1000);
        }
        initCountdown();

        // 2. Select Campaign Product (Product Selector right above cart)
        function selectCampaignProduct(id, name, price, imgUrl) {
            document.querySelectorAll('.product-choice-row').forEach(row => row.classList.remove('is-active'));
            const selected = document.getElementById('prod-row-' + id);
            if (selected) {
                selected.classList.add('is-active');
            }

            document.getElementById('formProductId').value = id;
            currentItemPrice = price;

            document.getElementById('summaryTitle').innerText = name;
            document.getElementById('summaryImg').src = imgUrl;
            document.getElementById('summaryPriceEach').innerText = '৳' + price.toLocaleString();

            calculateTotal();
        }

        // 3. Update Delivery Zone
        function updateDeliveryZone(charge, cardElem) {
            document.querySelectorAll('.zone-card-item').forEach(el => el.classList.remove('is-selected'));
            cardElem.classList.add('is-selected');
            const radio = cardElem.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = true;
            }
            currentShipping = charge;
            calculateTotal();
        }

        // 4. Quantity Stepper
        function changeQty(delta) {
            const qtyInput = document.getElementById('orderQty');
            let val = parseInt(qtyInput.value) || 1;
            val += delta;
            if (val < 1) val = 1;
            if (val > 10) val = 10;
            qtyInput.value = val;
            calculateTotal();
        }

        // 5. Calculate Order Totals
        function calculateTotal() {
            const qtyInput = document.getElementById('orderQty');
            const qty = parseInt(qtyInput.value) || 1;

            const subtotal = currentItemPrice * qty;
            const grandTotal = subtotal + currentShipping;

            document.getElementById('displaySubtotal').innerText = '৳' + subtotal.toLocaleString();
            document.getElementById('displayShipping').innerText = '৳' + currentShipping.toLocaleString();
            document.getElementById('displayGrandTotal').innerText = '৳' + grandTotal.toLocaleString();
            document.getElementById('stickyTotal').innerText = '৳' + grandTotal.toLocaleString();
        }

        // 6. Smooth Scroll and Focus
        function scrollAndFocus() {
            const orderSec = document.getElementById('orderSection');
            if (orderSec) {
                orderSec.scrollIntoView({ behavior: 'smooth' });
                setTimeout(() => {
                    const nameInput = document.querySelector('input[name="customer_name"]');
                    if (nameInput) nameInput.focus();
                }, 400);
            }
        }
    </script>
</body>
</html>
