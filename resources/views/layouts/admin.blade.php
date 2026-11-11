<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') | {{ \App\Models\Setting::get('site_name', 'Meghduti Paridhaan') }}</title>

    <!-- Google Fonts: Hind Siliguri + Plus Jakarta Sans (Exact font from design) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --admin-bg: #F4F6F9;
            --admin-sidebar: #FFFFFF;
            --admin-card: #FFFFFF;
            --admin-border: #E5E7EB;
            --admin-border-light: #F1F5F9;
            --admin-primary: #EA580C;
            --admin-primary-hover: #C2410C;
            --text-dark: #111827;
            --text-body: #374151;
            --text-muted: #6B7280;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Hind Siliguri', 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--admin-bg);
            color: var(--text-body);
            display: flex;
            min-height: 100vh;
            font-size: 13px;
            -webkit-font-smoothing: antialiased;
        }

        /* Sidebar (Clean White & Sohoj ERP Theme) */
        .admin-sidebar {
            width: 225px;
            background: var(--admin-sidebar);
            border-right: 1px solid var(--admin-border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            overflow-y: auto;
        }
        .sidebar-brand {
            padding: 12px 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid var(--admin-border);
            background: #FFFFFF;
        }
        .sidebar-brand img {
            height: 32px;
            width: auto;
            max-width: 150px;
            object-fit: contain;
            display: block;
            margin: 0 auto;
        }
        .brand-text-block {
            display: flex;
            flex-direction: column;
        }
        .brand-main-title {
            font-size: 15px;
            font-weight: 800;
            color: #111827;
            line-height: 1.1;
            letter-spacing: -0.2px;
        }
        .brand-sub-title {
            font-size: 8.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #9CA3AF;
            margin-top: 1px;
        }
        .sidebar-menu {
            padding: 10px 8px;
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 2px;
            flex: 1;
        }
        .menu-header {
            font-size: 9.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #9CA3AF;
            padding: 10px 10px 3px;
        }
        .nav-item a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 7px 10px;
            border-radius: 6px;
            color: #4B5563;
            text-decoration: none;
            font-size: 12.5px;
            font-weight: 600;
            line-height: 1.3;
            transition: all 0.15s ease;
        }
        .nav-item a:hover {
            background: #F3F4F6;
            color: #111827;
        }
        .nav-item.active a {
            background: var(--admin-primary);
            color: #FFFFFF;
            font-weight: 700;
        }
        .nav-item a i {
            width: 16px;
            font-size: 13px;
            text-align: center;
            color: #64748B;
        }
        .nav-item.active a i {
            color: #FFFFFF;
        }
        .sidebar-badge {
            margin-left: auto;
            background: #EF4444;
            color: white;
            font-size: 9.5px;
            font-weight: 800;
            padding: 1px 6px;
            border-radius: 99px;
            line-height: 1.2;
        }
        .nav-item.active .sidebar-badge {
            background: rgba(255, 255, 255, 0.3);
            color: white;
        }
        .sidebar-user-footer {
            padding: 10px 14px;
            border-top: 1px solid var(--admin-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Main Content Wrapper */
        .admin-main {
            margin-left: 225px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: calc(100% - 225px);
        }

        /* Top Header (Seamless with background like in Sohoj Restaurant image) */
        .admin-header {
            background: var(--admin-bg);
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            position: sticky;
            top: 0;
            z-index: 90;
        }
        .header-breadcrumbs {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #6B7280;
            font-weight: 600;
        }
        .header-breadcrumbs a {
            color: #6B7280;
            text-decoration: none;
        }
        .header-breadcrumbs a:hover {
            color: #111827;
        }
        .header-breadcrumbs .sep {
            color: #9CA3AF;
            font-size: 11px;
        }
        .header-breadcrumbs .current {
            color: #111827;
            font-weight: 700;
        }
        .header-right {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .btn-view-site {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 700;
            color: #4B5563;
            text-decoration: none;
            padding: 5px 12px;
            border-radius: 6px;
            border: 1px solid var(--admin-border);
            background: #FFFFFF;
            transition: all 0.15s;
        }
        .btn-view-site:hover {
            background: #F3F4F6;
            color: #111827;
        }
        .admin-user-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 12.5px;
            font-weight: 700;
            color: #111827;
            text-decoration: none;
            padding: 4px 12px 4px 6px;
            border-radius: 99px;
            border: 1px solid var(--admin-border);
            background: #FFFFFF;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }
        .admin-user-tag:hover {
            border-color: var(--admin-primary);
            background: #FFF7ED;
            color: var(--admin-primary);
            box-shadow: 0 2px 8px rgba(234, 88, 12, 0.12);
        }
        .admin-user-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #FFF7ED;
            color: var(--admin-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            border: 1px solid #FFEDD5;
        }

        /* Content Body */
        .content-body {
            padding: 0 24px 36px;
            flex: 1;
        }

        /* Stat Cards Row */
        .stat-cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-bottom: 16px;
        }
        .stat-card-compact {
            background: #FFFFFF;
            border: 1px solid var(--admin-border);
            border-radius: 8px;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        }
        .stat-icon-wrap {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }
        .stat-icon-orange { background: #FFF7ED; color: #EA580C; }
        .stat-icon-green { background: #F0FDF4; color: #16A34A; }
        .stat-icon-purple { background: #FAF5FF; color: #9333EA; }
        .stat-icon-blue { background: #EFF6FF; color: #2563EB; }
        .stat-card-sub {
            font-size: 10.5px;
            font-weight: 700;
            color: #6B7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .stat-card-val {
            font-size: 18px;
            font-weight: 800;
            color: #111827;
            line-height: 1.2;
            font-variant-numeric: tabular-nums;
        }

        /* Sohoj Restaurant Filter Card Bar */
        .filter-card-bar {
            background: #FFFFFF;
            border: 1px solid var(--admin-border);
            border-radius: 8px;
            padding: 10px 14px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        }
        .filter-left-group {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            flex: 1;
        }
        .search-input-wrap {
            position: relative;
            display: inline-block;
        }
        .search-input-wrap i {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
            font-size: 12px;
        }
        .filter-search-input {
            padding: 6px 12px 6px 32px;
            border-radius: 6px;
            border: 1px solid var(--admin-border);
            font-size: 12px;
            height: 34px;
            outline: none;
            background: #FFFFFF;
            color: #111827;
            width: 270px;
            font-family: inherit;
            transition: border-color 0.15s;
        }
        .filter-search-input:focus {
            border-color: var(--admin-primary);
        }
        .filter-select {
            padding: 6px 10px;
            border-radius: 6px;
            border: 1px solid var(--admin-border);
            font-size: 12px;
            height: 34px;
            outline: none;
            background: #FFFFFF;
            color: #4B5563;
            font-family: inherit;
            cursor: pointer;
        }
        .filter-select:focus {
            border-color: var(--admin-primary);
        }
        .btn-orange-search {
            background: var(--admin-primary);
            color: #FFFFFF;
            padding: 6px 16px;
            height: 34px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 12px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background 0.15s;
        }
        .btn-orange-search:hover {
            background: var(--admin-primary-hover);
        }
        .btn-orange-add {
            background: var(--admin-primary);
            color: #FFFFFF;
            padding: 6px 16px;
            height: 34px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 12px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background 0.15s;
        }
        .btn-orange-add:hover {
            background: var(--admin-primary-hover);
        }

        /* ========================================================
           SOHOJ RESTAURANT ERP CLEAN WHITE GRID TABLE SYSTEM
           ======================================================== */
        .table-card-container {
            background: #FFFFFF;
            border-radius: 8px;
            border: 1px solid var(--admin-border);
            overflow-x: auto;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            margin-bottom: 24px;
        }
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            line-height: 1.4;
            color: #374151;
            background: #FFFFFF;
        }
        .admin-table th {
            background: #FFFFFF;
            color: #6B7280;
            font-weight: 600;
            text-align: left;
            padding: 10px 14px;
            border-bottom: 1px solid var(--admin-border);
            border-right: 1px solid #F1F5F9;
            font-size: 11.5px;
            white-space: nowrap;
        }
        .admin-table th:last-child {
            border-right: none;
        }
        .admin-table th:first-child {
            text-align: center;
            width: 45px;
        }
        .admin-table tbody tr {
            background-color: #FFFFFF;
            transition: background-color 0.15s ease;
        }
        .admin-table td {
            padding: 9px 14px;
            border-bottom: 1px solid #F3F4F6;
            border-right: 1px solid #F3F4F6;
            vertical-align: middle;
            color: #374151;
            font-size: 12px;
            background-color: inherit;
        }
        .admin-table td:last-child {
            border-right: none;
        }
        .admin-table td:first-child {
            text-align: center;
            color: #9CA3AF;
            font-size: 11px;
            width: 45px;
        }
        .admin-table tr:last-child td {
            border-bottom: none;
        }
        .admin-table tbody tr:hover {
            filter: brightness(0.97);
        }

        /* Order number / link styling (Orange as in screenshot) */
        .invoice-orange {
            color: #EA580C;
            font-weight: 700;
            font-size: 11.5px;
            text-decoration: none;
        }
        .invoice-orange:hover {
            text-decoration: underline;
        }

        /* Price text */
        .tbl-price {
            font-weight: 800;
            color: #111827;
            font-size: 12px;
            font-variant-numeric: tabular-nums;
        }

        /* Action Buttons (Round 28px circles as in screenshot) */
        .action-btns-wrap {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            justify-content: flex-end;
        }
        .btn-tbl-action {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 11.5px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .btn-tbl-view {
            background: #EFF6FF;
            color: #2563EB;
        }
        .btn-tbl-view:hover {
            background: #DBEAFE;
            color: #1D4ED8;
        }
        .btn-tbl-edit {
            background: #FFF7ED;
            color: #EA580C;
        }
        .btn-tbl-edit:hover {
            background: #FFEDD5;
            color: #C2410C;
        }
        .btn-tbl-delete {
            background: #FEF2F2;
            color: #DC2626;
        }
        .btn-tbl-delete:hover {
            background: #FEE2E2;
            color: #B91C1C;
        }
        .btn-tbl-invoice {
            background: #F3F4F6;
            color: #4B5563;
        }
        .btn-tbl-invoice:hover {
            background: #E5E7EB;
            color: #111827;
        }

        /* Status & Payment Badges (Pills as in screenshot) */
        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 600;
            text-transform: capitalize;
            line-height: 1.3;
        }
        .badge-success { background: #DCFCE7; color: #15803D; }
        .badge-warning { background: #FEF3C7; color: #D97706; }
        .badge-danger  { background: #FEE2E2; color: #DC2626; }
        .badge-info    { background: #E0F2FE; color: #0284C7; }
        .badge-neutral { background: #F3F4F6; color: #4B5563; }

        /* Compact Pagination Styling */
        .pagination, nav[role="navigation"] {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
        }
        .pagination .page-item .page-link,
        nav[role="navigation"] a,
        nav[role="navigation"] span {
            padding: 4px 10px !important;
            font-size: 12px !important;
            border-radius: 4px !important;
            border: 1px solid var(--admin-border) !important;
        }

        /* Generic Cards & Forms */
        .card {
            background: #FFFFFF;
            border-radius: 12px;
            border: 1px solid var(--admin-border);
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            padding: 20px;
            margin-bottom: 20px;
        }
        .card-header-flex {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }
        .card-title {
            font-size: 16px;
            font-weight: 800;
            color: var(--text-dark);
        }
        .btn-admin-primary {
            background: var(--admin-primary);
            color: white;
            padding: 8px 18px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 12.5px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background 0.15s;
        }
        .btn-admin-primary:hover { background: var(--admin-primary-hover); }

        .btn-admin-secondary {
            background: #F8FAFC;
            color: var(--text-dark);
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 12.5px;
            border: 1px solid var(--admin-border);
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-admin-secondary:hover { background: #F1F5F9; }

        .btn-icon {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            border: 1px solid var(--admin-border);
            background: white;
            color: var(--text-muted);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            text-decoration: none;
            font-size: 12px;
        }
        .btn-icon:hover { border-color: var(--admin-primary); color: var(--admin-primary); }

        .form-control {
            width: 100%;
            padding: 9px 12px;
            border-radius: 8px;
            border: 1px solid var(--admin-border);
            font-size: 13px;
            outline: none;
            background: white;
            font-family: inherit;
        }
        .form-control:focus {
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.1);
        }

        .alert {
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .alert-success { background: #D1FAE5; color: #065F46; border: 1px solid #A7F3D0; }
        .alert-error { background: #FEE2E2; color: #991B1B; border: 1px solid #FECACA; }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="admin-sidebar">
        <div class="sidebar-brand">
            @php
                $siteName = \App\Models\Setting::get('site_name', 'Meghduti Paridhaan');
                $adminLogo = \App\Models\Setting::get('site_logo');
                $adminLogoUrl = $adminLogo ? asset('images/' . $adminLogo) : null;
            @endphp
            <a href="{{ route('admin.dashboard') }}" style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; text-decoration: none;">
                @if($adminLogoUrl && file_exists(public_path('images/' . $adminLogo)))
                    <img src="{{ $adminLogoUrl }}" alt="{{ $siteName }}">
                @else
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: #5C141D; color: #FFFFFF; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 800;">
                            {{ strtoupper(substr($siteName, 0, 1)) }}
                        </div>
                        <div class="brand-text-block">
                            <span class="brand-main-title" style="font-size: 13.5px; font-weight: 800; color: #5C141D;">{{ $siteName }}</span>
                        </div>
                    </div>
                @endif
            </a>
        </div>

        <ul class="sidebar-menu">
            <li class="menu-header">OVERVIEW</li>
            <li class="nav-item {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-chart-pie"></i> <span>Dashboard</span></a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.pos*') ? 'active' : '' }}">
                <a href="{{ route('admin.pos.index') }}"><i class="fa-solid fa-cash-register"></i> <span>POS / New Order</span></a>
            </li>

            <li class="menu-header">OPERATIONS</li>
            @if(auth('admin')->user()->hasPermission('view_orders'))
            <li class="nav-item {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                <a href="{{ route('admin.orders.index') }}">
                    <i class="fa-solid fa-cart-shopping"></i> <span>Orders</span>
                    @php $pendingCount = \App\Models\Order::where('status', 'pending')->count(); @endphp
                    @if($pendingCount > 0)
                        <span class="sidebar-badge">{{ $pendingCount }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.draft_orders*') ? 'active' : '' }}">
                <a href="{{ route('admin.draft_orders.index') }}">
                    <i class="fa-solid fa-clipboard-list"></i> <span>Draft Orders</span>
                    @php $draftCount = \App\Models\DraftOrder::where('status', 'pending')->count(); @endphp
                    @if($draftCount > 0)
                        <span class="sidebar-badge" style="background: #F59E0B;">{{ $draftCount }}</span>
                    @endif
                </a>
            </li>
            @endif
            <li class="nav-item {{ request()->routeIs('admin.customers*') ? 'active' : '' }}">
                <a href="{{ route('admin.customers.index') }}"><i class="fa-solid fa-users"></i> <span>Customers</span></a>
            </li>

            <li class="menu-header">STAFF & ROLES</li>
            @if(auth('admin')->user()->hasPermission('view_staff'))
            <li class="nav-item {{ request()->routeIs('admin.staff*') ? 'active' : '' }}">
                <a href="{{ route('admin.staff.index') }}"><i class="fa-solid fa-user-shield"></i> <span>Staff Members</span></a>
            </li>
            @endif
            @if(auth('admin')->user()->hasPermission('manage_roles'))
            <li class="nav-item {{ request()->routeIs('admin.roles*') ? 'active' : '' }}">
                <a href="{{ route('admin.roles.index') }}"><i class="fa-solid fa-shield-halved"></i> <span>Roles & Permissions</span></a>
            </li>
            @endif

            <li class="menu-header">CATALOG</li>
            <li class="nav-item {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                <a href="{{ route('admin.products.index') }}"><i class="fa-solid fa-shirt"></i> <span>Products</span></a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                <a href="{{ route('admin.categories.index') }}"><i class="fa-solid fa-tags"></i> <span>Categories</span></a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.colors*') ? 'active' : '' }}">
                <a href="{{ route('admin.colors.index') }}"><i class="fa-solid fa-palette"></i> <span>Colors</span></a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.sizes*') ? 'active' : '' }}">
                <a href="{{ route('admin.sizes.index') }}"><i class="fa-solid fa-ruler"></i> <span>Sizes</span></a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.landing*') ? 'active' : '' }}">
                <a href="{{ route('admin.landing.index') }}"><i class="fa-solid fa-wand-magic-sparkles"></i> <span>Landing Pages</span></a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.pages*') ? 'active' : '' }}">
                <a href="{{ route('admin.pages.index') }}"><i class="fa-solid fa-file-lines"></i> <span>Pages</span></a>
            </li>

            <li class="menu-header">INVENTORY</li>
            <li class="nav-item {{ request()->routeIs('admin.purchases*') ? 'active' : '' }}">
                <a href="{{ route('admin.purchases.index') }}"><i class="fa-solid fa-truck-ramp-box"></i> <span>Purchases</span></a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.suppliers*') ? 'active' : '' }}">
                <a href="{{ route('admin.suppliers.index') }}"><i class="fa-solid fa-building-user"></i> <span>Suppliers</span></a>
            </li>

            <li class="menu-header">REPORTS</li>
            <li class="nav-item {{ request()->routeIs('admin.reports.sales') ? 'active' : '' }}">
                <a href="{{ route('admin.reports.sales') }}"><i class="fa-solid fa-chart-line"></i> <span>Sales Report</span></a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.reports.stock') ? 'active' : '' }}">
                <a href="{{ route('admin.reports.stock') }}"><i class="fa-solid fa-boxes-stacked"></i> <span>Stock Report</span></a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.reports.purchase') ? 'active' : '' }}">
                <a href="{{ route('admin.reports.purchase') }}"><i class="fa-solid fa-file-invoice-dollar"></i> <span>Purchases Report</span></a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.reports.visitors') ? 'active' : '' }}">
                <a href="{{ route('admin.reports.visitors') }}"><i class="fa-solid fa-users-viewfinder"></i> <span>Visitor Analytics</span></a>
            </li>

            <li class="menu-header">SETTINGS</li>
            <li class="nav-item {{ request()->routeIs('admin.banners*') ? 'active' : '' }}">
                <a href="{{ route('admin.banners.index') }}"><i class="fa-solid fa-image"></i> <span>Banners & Sliders</span></a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.popup*') ? 'active' : '' }}">
                <a href="{{ route('admin.popup.index') }}"><i class="fa-solid fa-window-restore"></i> <span>Popup Banner</span></a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.order-statuses*') ? 'active' : '' }}">
                <a href="{{ route('admin.order-statuses.index') }}"><i class="fa-solid fa-list-check"></i> <span>Order Statuses</span></a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.shipping*') ? 'active' : '' }}">
                <a href="{{ route('admin.shipping.index') }}"><i class="fa-solid fa-truck"></i> <span>Shipping Zones</span></a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.couriers*') ? 'active' : '' }}">
                <a href="{{ route('admin.couriers.index') }}"><i class="fa-solid fa-truck-fast"></i> <span>Couriers & APIs</span></a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                <a href="{{ route('admin.settings.index') }}"><i class="fa-solid fa-gear"></i> <span>General Settings</span></a>
            </li>
        </ul>

        <div class="sidebar-user-footer">
            <a href="{{ route('admin.profile') }}" style="display: flex; align-items: center; gap: 8px; text-decoration: none; color: inherit; flex: 1; min-width: 0;" title="Edit Profile & Password">
                <div class="admin-user-avatar" style="width: 28px; height: 28px; font-size: 11px;"><i class="fa-solid fa-user-gear"></i></div>
                <div style="line-height: 1.2; overflow: hidden;">
                    <div style="font-weight: 700; font-size: 12px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ auth('admin')->user()->name }}</div>
                    <div style="font-size: 9.5px; color: #94A3B8;">
                        {{ auth('admin')->user()->role?->name ?? 'Super Admin' }}
                        @if(auth('admin')->user()->branch)
                            • {{ auth('admin')->user()->branch->name }}
                        @endif
                    </div>
                </div>
            </a>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" title="Logout" style="background: none; border: none; color: #DC2626; cursor: pointer; font-size: 13px; padding: 2px;">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="admin-main">
        <header class="admin-header">
            <div class="header-breadcrumbs">
                <a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house"></i> {{ \App\Models\Setting::get('site_name', 'Meghduti Paridhaan') }}</a>
                <span class="sep">></span>
                <span class="current">@yield('breadcrumb', 'Dashboard')</span>
            </div>

            <div class="header-right">
                <a href="{{ route('home') }}" target="_blank" class="btn-view-site">
                    <i class="fa-solid fa-globe"></i> View Website
                </a>
                <a href="{{ route('admin.profile') }}" class="admin-user-tag" title="Edit Profile & Password">
                    <div class="admin-user-avatar">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>
                    <span>{{ auth('admin')->user()->name }}</span>
                    <i class="fa-solid fa-pen-to-square" style="font-size: 11px; color: var(--admin-primary); margin-left: 2px;"></i>
                </a>
            </div>
        </header>

        <div class="content-body">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    @stack('scripts')
</body>
</html>
