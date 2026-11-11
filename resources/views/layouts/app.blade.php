<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Meghduti Paridhaan - Dhaka | Exclusive Saree & Boutique')</title>

    <!-- Google Tag Manager / dataLayer Initialization & Global Tracking (Google GA4 & Meta Standard) -->
    <script>
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({
            event: 'page_view',
            page_title: document.title,
            page_location: window.location.href,
            page_path: window.location.pathname
        });

        // Global Universal eCommerce Event Tracker
        window.trackEcommerceEvent = function(eventName, ecommerceData, fbEvent, fbData) {
            window.dataLayer = window.dataLayer || [];
            if (ecommerceData) {
                window.dataLayer.push({ ecommerce: null }); // Clear previous ecommerce object per GA4 standard
                var payload = Object.assign({ event: eventName }, ecommerceData);
                window.dataLayer.push(payload);
                console.log('📊 [GTM dataLayer Event]:', eventName, payload);
            } else {
                window.dataLayer.push({ event: eventName });
                console.log('📊 [GTM dataLayer Event]:', eventName);
            }

            if (fbEvent) {
                if (typeof window.fbq === 'function') {
                    window.fbq('track', fbEvent, fbData || {});
                    console.log('🎯 [Meta Pixel Event]:', fbEvent, fbData || {});
                } else {
                    console.log('ℹ️ [Meta Pixel Event Queued]:', fbEvent, '(fbq not loaded yet)');
                }
            }
        };
    </script>

    @php
        $siteFavicon = \App\Models\Setting::get('site_favicon') ?: \App\Models\Setting::get('favicon');
        $siteLogo = \App\Models\Setting::get('site_logo') ?: \App\Models\Setting::get('logo');
        $siteName = \App\Models\Setting::get('site_name', 'Meghduti Paridhaan');
        $siteAddress = \App\Models\Setting::get('site_address') ?: \App\Models\Setting::get('address', 'Dhaka');
        $sitePhone = \App\Models\Setting::get('site_phone') ?: \App\Models\Setting::get('phone', '88-01983380065');
        $siteEmail = \App\Models\Setting::get('site_email') ?: \App\Models\Setting::get('email', 'apon1001@gmail.com');
        
        $gtmId = \App\Models\Setting::get('gtm_id');
        $gtmHeadCode = \App\Models\Setting::get('gtm_head_code');
        $gtmBodyCode = \App\Models\Setting::get('gtm_body_code');
        $fbPixelId = \App\Models\Setting::get('fb_pixel_id');
        $fbPixelCode = \App\Models\Setting::get('fb_pixel_code');
        
        $logoUrl = null;
        if ($siteLogo) {
            if (\Illuminate\Support\Str::startsWith($siteLogo, ['http://', 'https://'])) {
                $logoUrl = $siteLogo;
            } elseif (\Illuminate\Support\Str::startsWith($siteLogo, 'images/')) {
                $logoUrl = asset($siteLogo);
            } else {
                $logoUrl = asset('images/' . $siteLogo);
            }
        }

        $faviconUrl = null;
        if ($siteFavicon) {
            if (\Illuminate\Support\Str::startsWith($siteFavicon, ['http://', 'https://'])) {
                $faviconUrl = $siteFavicon;
            } elseif (\Illuminate\Support\Str::startsWith($siteFavicon, 'images/')) {
                $faviconUrl = asset($siteFavicon);
            } else {
                $faviconUrl = asset('images/' . $siteFavicon);
            }
        }
    @endphp

    @if($faviconUrl)
        <link rel="icon" href="{{ $faviconUrl }}">
    @endif

    <!-- Google Tag Manager (GTM) Container / Custom Head Script -->
    @if(!empty($gtmHeadCode))
        {!! $gtmHeadCode !!}
    @elseif(!empty($gtmId))
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','{{ trim($gtmId) }}');</script>
        <!-- End Google Tag Manager -->
    @endif

    <!-- Meta / Facebook Pixel Code -->
    @if(!empty($fbPixelCode))
        {!! $fbPixelCode !!}
    @elseif(!empty($fbPixelId))
        <!-- Meta Pixel Code -->
        <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{{ trim($fbPixelId) }}');
        fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id={{ trim($fbPixelId) }}&ev=PageView&noscript=1"
        alt="Meta Pixel" /></noscript>
        <!-- End Meta Pixel Code -->
    @endif

    <!-- Google Fonts: Plus Jakarta Sans, Hind Siliguri, Playfair Display & Cinzel -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700;800;900&family=Hind+Siliguri:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,500&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --brand-maroon: #5A101C;
            --brand-maroon-dark: #430B14;
            --brand-maroon-light: #7A192A;
            --brand-maroon-subtle: #FFF1F3;
            --brand-gold: #D97706;
            --brand-gold-hover: #B45309;
            --brand-primary: #5A101C;
            --brand-primary-hover: #430B14;
            --brand-green: #5A101C;
            --brand-green-hover: #430B14;
            --brand-terracotta: #7A192A;
            --brand-terracotta-hover: #5A101C;
            --text-dark: #1F2937;
            --text-muted: #6B7280;
            --border-light: #E5E7EB;
            --bg-body: #FAFAFA;
            --bg-footer: #15181C;
            --radius-btn: 4px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Hind Siliguri', 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-body);
            color: var(--text-dark);
            line-height: 1.4;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
            color: inherit;
            transition: all 0.2s ease;
        }
        a:hover { opacity: 0.9; }

        .container {
            width: 100%;
            max-width: 1360px;
            margin: 0 auto;
            padding: 0 16px;
        }

        /* Header (Exact Match to Screenshot) */
        .site-header {
            background: #FFFFFF;
            border-bottom: none;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
            overflow: visible !important;
        }
        .header-main-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 0;
            gap: 20px;
        }

        /* Logo with emblem & Dhaka text */
        .brand-logo-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            min-width: 220px;
        }
        .site-header-logo-img {
            max-height: 48px;
            max-width: 240px;
            width: auto;
            height: auto;
            object-fit: contain;
            display: block;
        }
        @media (max-width: 768px) {
            .site-header-logo-img {
                max-height: 38px;
                max-width: 170px;
            }
        }
        .brand-logo-emblem {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            border: 2px solid #5A101C;
            background: #FFF5F6;
            color: #5A101C;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            box-shadow: 0 2px 6px rgba(90, 16, 28, 0.15);
            flex-shrink: 0;
        }
        .brand-logo-text {
            display: flex;
            flex-direction: column;
        }
        .brand-title {
            font-family: 'Playfair Display', 'Hind Siliguri', serif;
            font-size: 19px;
            font-weight: 700;
            color: #1F2937;
            line-height: 1.1;
            letter-spacing: -0.3px;
        }
        .brand-subtitle {
            font-size: 11px;
            font-weight: 500;
            color: #9CA3AF;
            letter-spacing: 0.5px;
            margin-top: 2px;
        }

        /* Search Bar */
        .header-search-form {
            flex: 1;
            max-width: 620px;
            display: flex;
            align-items: center;
            position: relative;
        }
        .header-search-input {
            width: 100%;
            height: 40px;
            padding: 8px 46px 8px 16px;
            border: 1px solid #D1D5DB;
            border-radius: 4px;
            font-size: 13.5px;
            outline: none;
            font-family: inherit;
            color: #374151;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: #FFFFFF;
        }
        .header-search-input:focus {
            border-color: var(--brand-green);
            box-shadow: 0 0 0 3px rgba(30, 126, 52, 0.1);
        }
        .header-search-btn {
            position: absolute;
            right: 0;
            top: 0;
            height: 40px;
            width: 44px;
            background: var(--brand-green);
            color: #FFFFFF;
            border: none;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            transition: background 0.2s;
        }
        .header-search-btn:hover {
            background: var(--brand-green-hover);
        }

        /* Header Right Actions */
        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .btn-cart-header {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 16px;
            border: 1px solid var(--brand-green);
            border-radius: 999px;
            background: #FFFFFF;
            color: var(--brand-green);
            font-size: 12.5px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-cart-header:hover {
            background: #FFF1F3;
        }
        .btn-cart-header i {
            font-size: 14px;
        }
        .cart-pill-count {
            background: var(--brand-green);
            color: #FFFFFF;
            font-size: 10.5px;
            font-weight: 800;
            padding: 1px 7px;
            border-radius: 99px;
            line-height: 1.2;
        }

        .btn-wishlist-header {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            position: relative;
            width: 38px;
            height: 38px;
            border: 1px solid #E5E7EB;
            border-radius: 50%;
            background: #FFFFFF;
            color: #4B5563;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .btn-wishlist-header:hover {
            border-color: #EF4444;
            color: #EF4444;
            background: #FEF2F2;
        }
        .wishlist-pill-count {
            position: absolute;
            top: -4px;
            right: -4px;
            background: #EF4444;
            color: #FFFFFF;
            font-size: 10px;
            font-weight: 800;
            min-width: 18px;
            height: 18px;
            border-radius: 99px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
            line-height: 1;
            box-shadow: 0 1px 3px rgba(239, 68, 68, 0.4);
        }

        .btn-wishlist-heart.active,
        .btn-wishlist-heart.in-wishlist {
            color: #EF4444 !important;
            border-color: #EF4444 !important;
            background: #FEF2F2 !important;
        }
        .heart-bouncing {
            animation: heartBounce 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        @keyframes heartBounce {
            0% { transform: scale(1); }
            50% { transform: scale(1.35); }
            100% { transform: scale(1); }
        }

        .btn-login-header {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 22px;
            border-radius: 4px;
            background: var(--brand-terracotta);
            color: #FFFFFF;
            font-size: 12.5px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-login-header:hover {
            background: var(--brand-terracotta-hover);
            color: #FFFFFF;
        }

        /* =========================================================
           CATEGORY HEADER NAVBAR (Sub-Header)
           ========================================================= */
        .category-header-navbar {
            background: #FFFFFF;
            border-top: 1px solid #F3F4F6;
            border-bottom: none !important;
            position: relative;
            z-index: 1001;
            box-shadow: none;
            overflow: visible !important;
        }
        .category-header-navbar .container {
            overflow: visible !important;
        }
        .cat-nav-list {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 6px;
            list-style: none;
            margin: 0;
            padding: 0;
            overflow: visible !important;
            position: relative;
        }
        .cat-nav-item {
            position: relative;
            flex-shrink: 0;
        }
        .cat-nav-item:hover,
        .cat-nav-item.menu-open {
            z-index: 1005;
        }
        .cat-nav-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 14px;
            font-size: 13.5px;
            font-weight: 600;
            color: #2D3748;
            text-decoration: none;
            white-space: nowrap;
            border-radius: 4px;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .cat-nav-link:hover,
        .cat-nav-item:hover > .cat-nav-link,
        .cat-nav-link.active,
        .cat-nav-item.active > .cat-nav-link {
            color: #5A101C;
            background: #FFF1F3;
        }
        .cat-nav-link .cat-icon {
            font-size: 12px;
            color: #5A101C;
        }
        .cat-chevron {
            font-size: 10px;
            color: #9CA3AF;
            transition: transform 0.25s ease, color 0.2s ease;
            margin-left: 2px;
        }
        .cat-nav-item:hover .cat-chevron,
        .cat-nav-item.menu-open .cat-chevron {
            transform: rotate(180deg);
            color: #5A101C;
        }

        /* Category Dropdown Menu (Opens on Hover - Floats over Banner) */
        .cat-dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            min-width: 230px;
            background: #FFFFFF;
            border-radius: 6px;
            border: 1px solid #E5E7EB;
            box-shadow: 0 16px 36px rgba(0, 0, 0, 0.15), 0 4px 12px rgba(0, 0, 0, 0.06);
            padding: 8px 0;
            z-index: 99999;
            opacity: 0;
            visibility: hidden;
            transform: translateY(6px);
            transition: opacity 0.2s cubic-bezier(0.16, 1, 0.3, 1), transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.2s;
            pointer-events: none;
        }
        /* Hover bridge to keep mouse interaction smooth without flickering */
        .cat-dropdown-menu::before {
            content: '';
            position: absolute;
            top: -10px;
            left: 0;
            right: 0;
            height: 10px;
            background: transparent;
        }
        .cat-nav-item:hover > .cat-dropdown-menu,
        .cat-nav-item.menu-open > .cat-dropdown-menu,
        .cat-dropdown-menu:hover {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            pointer-events: auto;
        }
        .cat-sub-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .cat-sub-item {
            display: block;
        }
        .cat-sub-link {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 500;
            color: #374151;
            text-decoration: none;
            transition: all 0.18s ease;
        }
        .cat-sub-link .sub-bullet {
            font-size: 9px;
            color: #9CA3AF;
            transition: transform 0.18s ease, color 0.18s ease;
        }
        .cat-sub-link:hover,
        .cat-sub-link.active {
            background: #FFF1F3;
            color: #5A101C;
            font-weight: 600;
            padding-left: 20px;
        }
        .cat-sub-link:hover .sub-bullet,
        .cat-sub-link.active .sub-bullet {
            color: #5A101C;
            transform: translateX(2px);
        }

        @media (max-width: 768px) {
            .category-header-navbar {
                border-bottom: none !important;
            }
            .cat-nav-list {
                overflow-x: auto !important;
                overflow-y: visible !important;
                scrollbar-width: none;
                -ms-overflow-style: none;
                -webkit-overflow-scrolling: touch;
                gap: 2px;
                padding: 4px 0;
            }
            .cat-nav-list::-webkit-scrollbar {
                display: none;
            }
            .cat-nav-link {
                font-size: 12.5px;
                padding: 7px 10px;
            }
            .cat-dropdown-menu {
                position: fixed;
                top: auto;
                left: 12px;
                right: 12px;
                width: auto;
                border-radius: 8px;
                z-index: 999999;
            }
        }

        /* Floating WhatsApp Button (Bottom Right) */
        .floating-whatsapp-chat {
            position: fixed;
            bottom: 24px;
            right: 24px;
            width: 54px;
            height: 54px;
            border-radius: 50%;
            background: #25D366;
            color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            box-shadow: 0 4px 18px rgba(37, 211, 102, 0.45);
            z-index: 995;
            transition: all 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-decoration: none;
        }
        .floating-whatsapp-chat:hover {
            transform: scale(1.12);
            background: #20BA5A;
            color: #FFFFFF;
            box-shadow: 0 8px 24px rgba(37, 211, 102, 0.6);
        }
        @media (max-width: 768px) {
            .floating-whatsapp-chat {
                bottom: 20px;
                right: 18px;
                width: 48px;
                height: 48px;
                font-size: 26px;
            }
        }

        /* Footer (Exact Match to Screenshot) */
        .site-footer {
            background: var(--bg-footer);
            color: #9CA3AF;
            font-size: 12.5px;
            padding: 48px 0 24px;
            margin-top: 60px;
            border-top: 1px solid #22262E;
        }
        .footer-top-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 30px;
            text-decoration: none;
        }
        .site-footer-logo-img {
            max-height: 42px;
            max-width: 220px;
            width: auto;
            height: auto;
            object-fit: contain;
            display: block;
            background: #FFFFFF;
            padding: 4px 10px;
            border-radius: 6px;
        }
        .footer-logo-badge {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #5A101C;
            color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 800;
        }
        .footer-logo-text {
            color: #FFFFFF;
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .footer-grid-5 {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 28px;
            padding-bottom: 36px;
            border-bottom: 1px solid #272C35;
        }
        @media (max-width: 1024px) {
            .footer-grid-5 { grid-template-columns: repeat(3, 1fr); }
        }
        @media (max-width: 640px) {
            .footer-grid-5 { grid-template-columns: 1fr; gap: 20px; }
        }

        .footer-col-title {
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
            text-transform: capitalize;
        }
        .footer-col-text {
            color: #E5E7EB;
            font-size: 12.5px;
            line-height: 1.5;
            word-break: break-word;
        }

        /* QR Code Block */
        .qr-image-box {
            width: 82px;
            height: 82px;
            background: #FFFFFF;
            padding: 4px;
            border-radius: 4px;
            display: inline-block;
        }
        .qr-image-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        /* Social Icons */
        .social-pill-group {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 6px;
        }
        .social-btn-circle {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFFFFF;
            font-size: 13px;
            transition: transform 0.2s;
            text-decoration: none;
        }
        .social-btn-circle:hover {
            transform: scale(1.1);
        }
        .social-fb { background: #1877F2; }
        .social-insta { background: linear-gradient(45deg, #F09433 0%, #E6683C 25%, #DC2743 50%, #CC2366 75%, #BC1888 100%); }
        .social-wa { background: #25D366; }
        .social-tg { background: #229ED9; }
        .social-tw { background: #1DA1F2; }

        /* Footer Bottom Bar */
        .footer-bottom-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 20px;
            font-size: 11.5px;
            color: #9CA3AF;
            flex-wrap: wrap;
            gap: 14px;
        }
        .footer-bottom-links {
            display: flex;
            align-items: center;
            gap: 18px;
            list-style: none;
            flex-wrap: wrap;
        }
        .footer-bottom-links a {
            color: #9CA3AF;
            transition: color 0.2s;
        }
        .footer-bottom-links a:hover {
            color: #FFFFFF;
        }

        /* Cart Offcanvas Drawer */
        .cart-drawer-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 2000;
        }
        .cart-drawer-overlay.active { display: block; }
        .cart-drawer {
            position: fixed;
            top: 0;
            right: -420px;
            width: 100%;
            max-width: 400px;
            height: 100%;
            background: #FFFFFF;
            z-index: 2001;
            box-shadow: -5px 0 25px rgba(0,0,0,0.15);
            transition: right 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        .cart-drawer.active { right: 0; }
        .cart-drawer-header {
            padding: 18px 20px;
            border-bottom: 1px solid #F3F4F6;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .cart-drawer-title {
            font-size: 14px;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: #111827;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .cart-drawer-body {
            flex: 1;
            overflow-y: auto;
            padding: 18px 20px;
        }
        .cart-item-row {
            display: flex;
            gap: 12px;
            padding-bottom: 14px;
            margin-bottom: 14px;
            border-bottom: 1px solid #F3F4F6;
        }
        .cart-item-img {
            width: 60px;
            height: 75px;
            object-fit: cover;
            border-radius: 4px;
            background: #F4F4F5;
        }
        .cart-drawer-footer {
            padding: 18px 20px;
            border-top: 1px solid #F3F4F6;
            background: #FAFAFA;
        }
        .btn-checkout-green {
            display: block;
            width: 100%;
            background: var(--brand-green);
            color: #FFFFFF;
            padding: 12px;
            text-align: center;
            font-size: 13px;
            font-weight: 700;
            border: none;
            border-radius: var(--radius-btn);
            cursor: pointer;
            margin-bottom: 8px;
            transition: background 0.2s;
        }
        .btn-checkout-green:hover { background: var(--brand-green-hover); color: #FFFFFF; }
        .btn-view-cart-white {
            display: block;
            width: 100%;
            background: #FFFFFF;
            color: #374151;
            padding: 10px;
            text-align: center;
            font-size: 12px;
            font-weight: 700;
            border: 1px solid #D1D5DB;
            border-radius: var(--radius-btn);
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-view-cart-white:hover { border-color: #111827; color: #111827; }

        /* Toast Popup */
        .toast-box {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #111827;
            color: #FFFFFF;
            padding: 12px 20px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            z-index: 9999;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            animation: slideInToast 0.3s ease;
            border-left: 4px solid var(--brand-green);
        }
        @keyframes slideInToast {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* Language Switcher Badge in Header */
        .site-lang-switch {
            display: inline-flex;
            align-items: center;
            background: #F3F4F6;
            border: 1px solid #E5E7EB;
            border-radius: 999px;
            padding: 2px 4px;
            gap: 2px;
            margin-right: 2px;
        }
        .site-lang-switch .lang-btn {
            border: none;
            background: transparent;
            color: #4B5563;
            padding: 4px 10px;
            border-radius: 999px;
            cursor: pointer;
            font-family: inherit;
            font-weight: 700;
            font-size: 12px;
            line-height: 1.2;
            transition: all 0.2s ease;
        }
        .site-lang-switch .lang-btn:hover {
            color: var(--brand-maroon);
        }
        .site-lang-switch .lang-btn.active {
            background: var(--brand-maroon);
            color: #FFFFFF;
            box-shadow: 0 1px 3px rgba(90, 16, 28, 0.25);
        }
        .site-lang-switch .lang-sep {
            color: #D1D5DB;
            font-size: 11px;
            user-select: none;
        }

        /* Google Translate UI Cleanup */
        .goog-te-banner-frame.skiptranslate,
        .goog-te-banner-frame,
        iframe.goog-te-banner-frame,
        .VIpgJd-ZVi9od-ORHb-OEVmfc {
            display: none !important;
            visibility: hidden !important;
        }
        body {
            top: 0px !important;
            position: static !important;
        }
        .skiptranslate iframe {
            display: none !important;
        }
        #goog-gt-tt, .goog-te-balloon-frame {
            display: none !important;
        }
        .goog-text-highlight {
            background: none !important;
            box-shadow: none !important;
        }
        #google_translate_element {
            display: none !important;
        }

        /* Site Popup Banner Modal */
        .site-popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.72);
            z-index: 999999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
            backdrop-filter: blur(3px);
        }
        .site-popup-overlay.show {
            opacity: 1;
            pointer-events: auto;
        }
        .site-popup-modal {
            position: relative;
            max-width: 540px;
            width: 100%;
            background: #FFFFFF;
            border-radius: 12px;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.4);
            overflow: hidden;
            transform: scale(0.92);
            transition: transform 0.3s ease;
        }
        .site-popup-overlay.show .site-popup-modal {
            transform: scale(1);
        }
        .site-popup-close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.65);
            color: #FFFFFF;
            border: 2px solid #FFFFFF;
            font-size: 20px;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            transition: background 0.2s, transform 0.2s;
        }
        .site-popup-close-btn:hover {
            background: #EF4444;
            transform: scale(1.1);
        }
        .site-popup-content {
            line-height: 0;
            width: 100%;
        }
        .site-popup-link {
            display: block;
            cursor: pointer;
        }
        .site-popup-img {
            width: 100%;
            height: auto;
            max-height: 75vh;
            object-fit: contain;
            display: block;
            border-radius: 12px;
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Google Tag Manager (noscript) -->
    @if(!empty($gtmBodyCode))
        {!! $gtmBodyCode !!}
    @elseif(!empty($gtmId))
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ trim($gtmId) }}"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @endif
    <!-- End Google Tag Manager (noscript) -->

    <!-- Header Section -->
    <header class="site-header">
        <div class="container">
            <div class="header-main-row">
                
                <!-- Left: Logo & Subtitle -->
                <a href="{{ route('home') }}" class="brand-logo-wrap" title="{{ $siteName }} - Dhaka">
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="site-header-logo-img">
                    @else
                        <div class="brand-logo-emblem">
                            <i class="fa-solid fa-gem"></i>
                        </div>
                        <div class="brand-logo-text">
                            <span class="brand-title">{{ $siteName }}</span>
                            <span class="brand-subtitle">Dhaka</span>
                        </div>
                    @endif
                </a>

                <!-- Center: Search Input with Green Button -->
                <form action="{{ route('shop.index') }}" method="GET" class="header-search-form">
                    <input type="text" name="q" class="header-search-input" placeholder="Search in the store here..." value="{{ request('q') }}">
                    <button type="submit" class="header-search-btn" title="Search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>

                <!-- Right: Wishlist, Cart & Login Buttons -->
                <div class="header-actions">
                    <!-- Website Language Switcher (Google Translate) -->
                    <div class="site-lang-switch" title="Language / ভাষা পরিবর্তন">
                        <button type="button" class="lang-btn active" data-lang="en" onclick="setSiteLanguage('en')">EN</button>
                        <span class="lang-sep">|</span>
                        <button type="button" class="lang-btn" data-lang="bn" onclick="setSiteLanguage('bn')">বাং</button>
                    </div>

                    <a href="{{ route('wishlist.index') }}" class="btn-wishlist-header" title="My Wishlist">
                        <i class="fa-regular fa-heart"></i>
                        <span class="wishlist-pill-count" id="headerWishlistCount">{{ count($wishlistIds ?? []) }}</span>
                    </a>

                    <button type="button" class="btn-cart-header" onclick="openCartDrawer()" title="View Shopping Cart">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span>Cart</span>
                        <span class="cart-pill-count" id="headerCartCount">{{ session('cart') ? array_sum(array_column(session('cart'), 'quantity')) : 0 }}</span>
                    </button>

                    @if(auth('web')->check())
                        <a href="{{ route('account.dashboard') }}" class="btn-login-header" title="My Account">
                            <i class="fa-regular fa-user" style="margin-right: 5px;"></i> Account
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-login-header" title="Customer Login">
                            Login
                        </a>
                    @endif
                </div>

            </div>
        </div>

        <!-- Category Header Navbar (Secondary Header Navigation with Subcategories Dropdown) -->
        <nav class="category-header-navbar" aria-label="Category Navigation">
            <div class="container">
                <ul class="cat-nav-list">
                    <li class="cat-nav-item">
                        <a href="{{ route('shop.index') }}" class="cat-nav-link {{ !request('category') && !request('subcategory') && request()->routeIs('shop.index') ? 'active' : '' }}">
                            <i class="fa-solid fa-layer-group cat-icon"></i>
                            <span>All Collection</span>
                        </a>
                    </li>
                    @if(isset($navCategories) && $navCategories->count() > 0)
                        @foreach($navCategories as $cat)
                            @php
                                $hasChildren = $cat->children && $cat->children->count() > 0;
                                $isActive = request('category') === $cat->slug;
                            @endphp
                            <li class="cat-nav-item {{ $hasChildren ? 'has-dropdown' : '' }} {{ $isActive ? 'active' : '' }}">
                                <a href="{{ route('shop.index', ['category' => $cat->slug]) }}" class="cat-nav-link {{ $isActive ? 'active' : '' }}">
                                    <span>{{ $cat->name }}</span>
                                    @if($hasChildren)
                                        <i class="fa-solid fa-chevron-down cat-chevron"></i>
                                    @endif
                                </a>

                                @if($hasChildren)
                                    <div class="cat-dropdown-menu">
                                        <ul class="cat-sub-list">
                                            @foreach($cat->children as $sub)
                                                @php
                                                    $isSubActive = request('subcategory') === $sub->slug;
                                                @endphp
                                                <li class="cat-sub-item">
                                                    <a href="{{ route('shop.index', ['category' => $cat->slug, 'subcategory' => $sub->slug]) }}" class="cat-sub-link {{ $isSubActive ? 'active' : '' }}">
                                                        <i class="fa-solid fa-angle-right sub-bullet"></i>
                                                        <span>{{ $sub->name }}</span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </nav>
    </header>

    @php
        $rawWhatsapp = \App\Models\Setting::get('whatsapp_number') 
            ?: (\App\Models\Setting::get('phone') ?: \App\Models\Setting::get('site_phone'));
        
        $whatsappUrl = null;
        if ($rawWhatsapp) {
            $trimmed = trim($rawWhatsapp);
            if (\Illuminate\Support\Str::startsWith($trimmed, ['http://', 'https://', 'wa.me/'])) {
                $whatsappUrl = \Illuminate\Support\Str::startsWith($trimmed, 'wa.me/') ? 'https://' . $trimmed : $trimmed;
            } else {
                $digits = preg_replace('/[^0-9]/', '', $trimmed);
                if (strlen($digits) === 11 && str_starts_with($digits, '01')) {
                    $digits = '88' . $digits;
                } elseif (strlen($digits) === 10 && str_starts_with($digits, '1')) {
                    $digits = '880' . $digits;
                }
                if (!empty($digits)) {
                    $whatsappUrl = 'https://wa.me/' . $digits;
                }
            }
        }
    @endphp
    <!-- Floating WhatsApp Widget (Bottom Right) -->
    @if($whatsappUrl)
        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="floating-whatsapp-chat" title="Chat with Us on WhatsApp">
            <i class="fa-brands fa-whatsapp"></i>
        </a>
    @endif

    <!-- Main Content Body -->
    <main>
        @yield('content')
    </main>

    <!-- Footer Section (Exact Match to Screenshot) -->
    <footer class="site-footer">
        <div class="container">
            
            <!-- Footer Brand Logo -->
            <a href="{{ route('home') }}" class="footer-top-logo">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="site-footer-logo-img">
                @else
                    <div class="footer-logo-badge">
                        <i class="fa-solid fa-gem"></i>
                    </div>
                    <span class="footer-logo-text">{{ $siteName }}</span>
                @endif
            </a>

            <!-- 5 Columns Grid -->
            <div class="footer-grid-5">
                
                <!-- Col 1: Address -->
                <div>
                    <div class="footer-col-title" style="color: #F59E0B;">
                        <i class="fa-solid fa-location-dot"></i> Address
                    </div>
                    <div class="footer-col-text">
                        {{ $siteAddress }}
                    </div>
                </div>

                <!-- Col 2: 24/7 Online Support -->
                <div>
                    <div class="footer-col-title" style="color: #06B6D4;">
                        <i class="fa-solid fa-headphones"></i> 24/7 Online Support
                    </div>
                    <div class="footer-col-text">
                        {{ $sitePhone }}
                    </div>
                </div>

                <!-- Col 3: Email Us -->
                <div>
                    <div class="footer-col-title" style="color: #FBBF24;">
                        <i class="fa-regular fa-envelope"></i> Email Us
                    </div>
                    <div class="footer-col-text">
                        {{ $siteEmail }}
                    </div>
                </div>

                <!-- Col 4: Scan QR -->
                <div>
                    <div class="footer-col-title" style="color: #FFFFFF;">
                        Scan QR
                    </div>
                    <div class="qr-image-box">
                        <!-- Clean standard QR code -->
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(url('/')) }}" alt="Scan QR">
                    </div>
                </div>

                <!-- Col 5: Find & Share Our Shop -->
                <div>
                    <div class="footer-col-title" style="color: #FFFFFF; margin-bottom: 4px;">
                        Find Our Shop
                    </div>
                    <div class="social-pill-group" style="margin-bottom: 12px;">
                        <a href="{{ \App\Models\Setting::get('facebook_url', 'https://facebook.com') }}" target="_blank" class="social-btn-circle social-fb" title="Facebook">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                        <a href="{{ \App\Models\Setting::get('instagram_url', 'https://instagram.com') }}" target="_blank" class="social-btn-circle social-insta" title="Instagram">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                    </div>

                    <div class="footer-col-title" style="color: #FFFFFF; margin-bottom: 4px;">
                        Share Our Shop
                    </div>
                    <div class="social-pill-group">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url('/')) }}" target="_blank" class="social-btn-circle social-fb" title="Share on Facebook">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                        <a href="https://wa.me/?text={{ urlencode(url('/')) }}" target="_blank" class="social-btn-circle social-wa" title="Share on WhatsApp">
                            <i class="fa-brands fa-whatsapp"></i>
                        </a>
                        <a href="https://t.me/share/url?url={{ urlencode(url('/')) }}" target="_blank" class="social-btn-circle social-tg" title="Share on Telegram">
                            <i class="fa-brands fa-telegram"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url('/')) }}" target="_blank" class="social-btn-circle social-tw" title="Share on Twitter">
                            <i class="fa-brands fa-twitter"></i>
                        </a>
                    </div>
                </div>

            </div>

            <!-- Footer Bottom Row -->
            <div class="footer-bottom-row">
                <div>
                    Developed by <a href="https://sarkarit.com" target="_blank" rel="noopener noreferrer" style="color: #E5E7EB; text-decoration: underline; font-weight: 600;">Sarkar IT</a>
                </div>

                <ul class="footer-bottom-links">
                    <li><a href="{{ route('track.order') }}">Track Order</a></li>
                    @php
                        $footerPages = \App\Models\Page::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();
                    @endphp
                    @foreach($footerPages as $fPage)
                        <li><a href="{{ route('page.show', $fPage->slug) }}">{{ $fPage->title }}</a></li>
                    @endforeach
                </ul>
            </div>

        </div>
    </footer>

    <!-- Cart Drawer Offcanvas -->
    <div class="cart-drawer-overlay" id="cartOverlay" onclick="closeCartDrawer()"></div>
    <div class="cart-drawer" id="cartDrawer">
        <div class="cart-drawer-header">
            <div class="cart-drawer-title">
                <i class="fa-solid fa-bag-shopping" style="color: var(--brand-green);"></i> YOUR SHOPPING BAG (<span id="drawerCartCount">{{ session('cart') ? array_sum(array_column(session('cart'), 'quantity')) : 0 }}</span>)
            </div>
            <button onclick="closeCartDrawer()" style="background: none; border: none; font-size: 18px; cursor: pointer; color: #6B7280;"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="cart-drawer-body" id="cartDrawerBody">
            @if(session('cart') && count(session('cart')) > 0)
                @foreach(session('cart') as $key => $item)
                    <div class="cart-item-row" id="cart-item-{{ $key }}">
                        <img src="{{ $item['image'] }}" class="cart-item-img" alt="{{ $item['name'] }}">
                        <div style="flex: 1;">
                            <div style="font-size: 13px; font-weight: 700; color: #111827; margin-bottom: 2px;">{{ $item['name'] }}</div>
                            @if(!empty($item['variant_info']))
                                <div style="font-size: 11px; color: var(--text-muted); margin-bottom: 4px;">{{ $item['variant_info'] }}</div>
                            @endif
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 6px;">
                                <div style="font-size: 13px; font-weight: 800; color: var(--brand-green);">৳ {{ number_format($item['price']) }} × {{ $item['quantity'] }}</div>
                                <button onclick="removeFromCart('{{ $key }}')" style="background: none; border: none; color: #EF4444; cursor: pointer; font-size: 12px;"><i class="fa-solid fa-trash-can"></i></button>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div style="text-align: center; padding: 50px 10px; color: var(--text-muted);">
                    <i class="fa-solid fa-bag-shopping" style="font-size: 40px; opacity: 0.25; margin-bottom: 12px; color: var(--brand-green);"></i>
                    <p style="font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Your shopping bag is empty.</p>
                </div>
            @endif
        </div>
        <div class="cart-drawer-footer">
            <div style="display: flex; justify-content: space-between; font-size: 14.5px; font-weight: 800; margin-bottom: 14px; color: #111827;">
                <span>SUBTOTAL:</span>
                <span id="drawerSubtotal" style="color: var(--brand-green);">৳ {{ number_format(session('cart') ? array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], session('cart'))) : 0) }}</span>
            </div>
            <a href="{{ route('checkout.index') }}" class="btn-checkout-green">PROCEED TO CHECKOUT</a>
            <a href="{{ route('cart.index') }}" class="btn-view-cart-white">VIEW SHOPPING BAG</a>
        </div>
    </div>

    <!-- Toast Notification Container -->
    <div id="toastBoxContainer"></div>

    <script>
        window.buyNowWithTracking = function(productId, productName, productPrice, categoryName) {
            window.trackEcommerceEvent('add_to_cart', {
                ecommerce: {
                    currency: 'BDT',
                    value: Number(productPrice),
                    items: [{
                        item_id: String(productId),
                        item_name: productName,
                        price: Number(productPrice),
                        quantity: 1,
                        item_category: categoryName || 'Boutique',
                        item_brand: 'Meghduti Paridhaan'
                    }]
                }
            }, 'AddToCart', {
                content_name: productName,
                content_ids: [String(productId)],
                content_type: 'product',
                value: Number(productPrice),
                currency: 'BDT'
            });

            setTimeout(function() {
                window.location.href = `{{ route('checkout.index') }}?direct_product_id=${productId}&direct_qty=1`;
            }, 120);
        };

        function openCartDrawer() {
            document.getElementById('cartDrawer').classList.add('active');
            document.getElementById('cartOverlay').classList.add('active');
        }
        function closeCartDrawer() {
            document.getElementById('cartDrawer').classList.remove('active');
            document.getElementById('cartOverlay').classList.remove('active');
        }

        function showToast(msg) {
            const container = document.getElementById('toastBoxContainer');
            const toast = document.createElement('div');
            toast.className = 'toast-box';
            toast.innerText = msg;
            container.appendChild(toast);
            setTimeout(() => toast.remove(), 3200);
        }

        function addToCartAjax(productId, variantId = null, quantity = 1) {
            fetch("{{ route('cart.add') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    product_id: productId,
                    variant_id: variantId,
                    quantity: quantity
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message);
                    if(document.getElementById('headerCartCount')) document.getElementById('headerCartCount').innerText = data.cartCount;
                    if(document.getElementById('drawerCartCount')) document.getElementById('drawerCartCount').innerText = data.cartCount;
                    if(document.getElementById('drawerSubtotal')) document.getElementById('drawerSubtotal').innerText = '৳ ' + data.cartTotal;
                    renderCartItems(data.cart);
                    openCartDrawer();

                    // Trigger GA4 & Meta Pixel eCommerce dataLayer event
                    if (data.added_item) {
                        window.trackEcommerceEvent('add_to_cart', {
                            ecommerce: {
                                currency: 'BDT',
                                value: Number(data.added_item.price) * Number(data.added_item.quantity),
                                items: [{
                                    item_id: String(data.added_item.id),
                                    item_name: data.added_item.name,
                                    price: Number(data.added_item.price),
                                    quantity: Number(data.added_item.quantity),
                                    item_category: data.added_item.category || 'Boutique',
                                    item_brand: 'Meghduti Paridhaan'
                                }]
                            }
                        }, 'AddToCart', {
                            content_name: data.added_item.name,
                            content_ids: [String(data.added_item.id)],
                            content_type: 'product',
                            value: Number(data.added_item.price) * Number(data.added_item.quantity),
                            currency: 'BDT'
                        });
                    }
                }
            })
            .catch(err => {
                console.error(err);
            });
        }

        function removeFromCart(key) {
            fetch(`/cart/remove/${key}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    "Accept": "application/json"
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast('Item removed from bag');
                    const el = document.getElementById(`cart-item-${key}`);
                    if (el) el.remove();
                    if(document.getElementById('headerCartCount')) document.getElementById('headerCartCount').innerText = data.cartCount;
                    if(document.getElementById('drawerCartCount')) document.getElementById('drawerCartCount').innerText = data.cartCount;
                    if(document.getElementById('drawerSubtotal')) document.getElementById('drawerSubtotal').innerText = '৳ ' + data.cartTotal;

                    window.dataLayer = window.dataLayer || [];
                    window.dataLayer.push({ ecommerce: null });
                    window.dataLayer.push({
                        event: 'remove_from_cart',
                        ecommerce: {
                            items: [{ item_id: String(key) }]
                        }
                    });
                }
            });
        }

        function renderCartItems(cart) {
            const body = document.getElementById('cartDrawerBody');
            if (!cart || Object.keys(cart).length === 0) {
                body.innerHTML = `
                    <div style="text-align: center; padding: 50px 10px; color: var(--text-muted);">
                        <i class="fa-solid fa-bag-shopping" style="font-size: 40px; opacity: 0.25; margin-bottom: 12px; color: var(--brand-green);"></i>
                        <p style="font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Your shopping bag is empty.</p>
                    </div>`;
                return;
            }

            let html = '';
            for (const key in cart) {
                const item = cart[key];
                html += `
                    <div class="cart-item-row" id="cart-item-${key}">
                        <img src="${item.image}" class="cart-item-img" alt="${item.name}">
                        <div style="flex: 1;">
                            <div style="font-size: 13px; font-weight: 700; color: #111827; margin-bottom: 2px;">${item.name}</div>
                            ${item.variant_info ? `<div style="font-size: 11px; color: var(--text-muted); margin-bottom: 4px;">${item.variant_info}</div>` : ''}
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 6px;">
                                <div style="font-size: 13px; font-weight: 800; color: var(--brand-green);">৳ ${item.price} × ${item.quantity}</div>
                                <button onclick="removeFromCart('${key}')" style="background: none; border: none; color: #EF4444; cursor: pointer; font-size: 12px;"><i class="fa-solid fa-trash-can"></i></button>
                            </div>
                        </div>
                    </div>`;
            }
            body.innerHTML = html;
        }

        // Global Wishlist Toggle Function
        function toggleWishlist(productId, btnElement) {
            if (!productId) return;

            const btn = btnElement || (window.event ? window.event.currentTarget : null);
            if (btn) btn.classList.add('heart-bouncing');

            fetch('/wishlist/toggle', {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    "Accept": "application/json"
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message);

                    // Update all buttons across page matching this product
                    const btns = document.querySelectorAll(`[data-wishlist-id="${productId}"]`);
                    btns.forEach(b => {
                        const icon = b.querySelector('i');
                        if (data.status === 'added') {
                            b.classList.add('active', 'in-wishlist');
                            if (icon) {
                                icon.classList.remove('fa-regular');
                                icon.classList.add('fa-solid');
                            }
                        } else {
                            b.classList.remove('active', 'in-wishlist');
                            if (icon) {
                                icon.classList.remove('fa-solid');
                                icon.classList.add('fa-regular');
                            }
                        }
                    });

                    // Update header wishlist badge count
                    const headerCount = document.getElementById('headerWishlistCount');
                    if (headerCount) {
                        headerCount.innerText = data.count;
                    }

                    // GTM & Meta event
                    if (data.status === 'added' && window.trackEcommerceEvent) {
                        window.trackEcommerceEvent('add_to_wishlist', {
                            ecommerce: {
                                currency: 'BDT',
                                items: [{ item_id: String(productId), item_name: data.product_name || '' }]
                            }
                        }, 'AddToWishlist', {
                            content_ids: [String(productId)],
                            content_name: data.product_name || '',
                            content_type: 'product'
                        });
                    }
                }
            })
            .catch(err => {
                console.error('Wishlist toggle error:', err);
            })
            .finally(() => {
                if (btn) {
                    setTimeout(() => btn.classList.remove('heart-bouncing'), 400);
                }
            });
        }

        // Mobile touch dropdown handling for category header navbar
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownItems = document.querySelectorAll('.cat-nav-item.has-dropdown');
            dropdownItems.forEach(item => {
                const chevron = item.querySelector('.cat-chevron');
                if (chevron) {
                    chevron.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        // Close others
                        dropdownItems.forEach(other => {
                            if (other !== item) other.classList.remove('menu-open');
                        });
                        item.classList.toggle('menu-open');
                    });
                }
            });
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.cat-nav-item.has-dropdown')) {
                    document.querySelectorAll('.cat-nav-item.menu-open').forEach(el => el.classList.remove('menu-open'));
                }
            });
        });

        // Google Translate & Language Switcher Logic
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                includedLanguages: 'en,bn',
                autoDisplay: false
            }, 'google_translate_element');
        }

        function getCookie(name) {
            const v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
            return v ? decodeURIComponent(v[2]) : null;
        }

        function setCookie(name, value, days) {
            const d = new Date();
            d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
            const expires = "expires=" + d.toGMTString();
            const host = window.location.hostname;
            document.cookie = name + "=" + value + ";path=/;" + expires;
            document.cookie = name + "=" + value + ";path=/;domain=" + host + ";" + expires;
            const parts = host.split('.');
            if (parts.length >= 2) {
                const root = parts.slice(-2).join('.');
                document.cookie = name + "=" + value + ";path=/;domain=." + root + ";" + expires;
            }
        }

        function updateLangButtons(activeLang) {
            document.querySelectorAll('.site-lang-switch .lang-btn').forEach(btn => {
                if (btn.getAttribute('data-lang') === activeLang) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
        }

        function setSiteLanguage(lang) {
            if (lang === 'bn') {
                setCookie('googtrans', '/en/bn', 30);
            } else {
                setCookie('googtrans', '/en/en', 30);
                setCookie('googtrans', '', -1);
            }

            const select = document.querySelector('.goog-te-combo');
            if (select) {
                select.value = lang;
                select.dispatchEvent(new Event('change'));
            }
            window.location.reload();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const trans = getCookie('googtrans');
            const activeLang = (trans && trans.indexOf('/bn') !== -1) ? 'bn' : 'en';
            updateLangButtons(activeLang);

            // Popup Modal handler
            const popupOverlay = document.getElementById('sitePopupOverlay');
            if (popupOverlay) {
                if (!sessionStorage.getItem('meghduti_popup_seen')) {
                    const delay = {{ $popupDelay ?? 1 }} * 1000;
                    setTimeout(function() {
                        popupOverlay.style.display = 'flex';
                        setTimeout(function() {
                            popupOverlay.classList.add('show');
                        }, 20);
                    }, delay);

                    const closeSitePopup = function() {
                        popupOverlay.classList.remove('show');
                        setTimeout(function() {
                            popupOverlay.style.display = 'none';
                        }, 300);
                        sessionStorage.setItem('meghduti_popup_seen', 'true');
                    };

                    const closeBtn = document.getElementById('sitePopupClose');
                    if (closeBtn) closeBtn.addEventListener('click', closeSitePopup);
                    popupOverlay.addEventListener('click', function(e) {
                        if (e.target === popupOverlay) closeSitePopup();
                    });
                }
            }
        });
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    <!-- Site Popup Banner Modal -->
    @php
        $popupActive = \App\Models\Setting::get('popup_active', '0');
        $popupImage = \App\Models\Setting::get('popup_image');
        $popupLink = \App\Models\Setting::get('popup_link', '');
        $popupTitle = \App\Models\Setting::get('popup_title', 'Offer');
        $popupDelay = (int) \App\Models\Setting::get('popup_delay', '1');

        $popupImageUrl = null;
        if ($popupImage) {
            if (\Illuminate\Support\Str::startsWith($popupImage, ['http://', 'https://'])) {
                $popupImageUrl = $popupImage;
            } elseif (\Illuminate\Support\Str::startsWith($popupImage, 'images/')) {
                $popupImageUrl = asset($popupImage);
            } else {
                $popupImageUrl = asset('images/popup/' . $popupImage);
            }
        }
    @endphp

    @if($popupActive == '1' && $popupImageUrl)
        <div id="sitePopupOverlay" class="site-popup-overlay" style="display: none;">
            <div class="site-popup-modal">
                <button type="button" class="site-popup-close-btn" id="sitePopupClose" aria-label="Close popup">&times;</button>
                <div class="site-popup-content">
                    @if(!empty($popupLink))
                        <a href="{{ $popupLink }}" class="site-popup-link">
                            <img src="{{ $popupImageUrl }}" alt="{{ $popupTitle }}" class="site-popup-img">
                        </a>
                    @else
                        <img src="{{ $popupImageUrl }}" alt="{{ $popupTitle }}" class="site-popup-img">
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Hidden Google Translate Element -->
    <div id="google_translate_element" style="display:none !important;"></div>
    @stack('scripts')
</body>
</html>
