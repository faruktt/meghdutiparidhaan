@extends('layouts.admin')

@section('title', 'Couriers & APIs')
@section('breadcrumb', 'Couriers & APIs')

@section('content')
<div class="card-header-flex" style="margin-bottom: 16px;">
    <div>
        <h1 style="font-size: 18px; font-weight: 800; color: var(--text-dark); margin: 0;">
            <i class="fa-solid fa-truck-fast" style="color: var(--admin-primary);"></i> Couriers & APIs Setup
        </h1>
    </div>
</div>

<form action="{{ route('admin.couriers.update') }}" method="POST">
    @csrf

    <!-- 1. Active Courier Selector -->
    <div class="card" style="padding: 14px 18px; margin-bottom: 16px;">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
            <span style="font-size: 13px; font-weight: 800; color: #1E293B;">
                <i class="fa-solid fa-circle-check" style="color: #2563EB;"></i> Active Courier:
            </span>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <label style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; border: 1.5px solid {{ $activeCourier === 'steadfast' ? '#2563EB' : '#E2E8F0' }}; background: {{ $activeCourier === 'steadfast' ? '#EFF6FF' : '#FFFFFF' }}; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 700; color: #0F172A;">
                    <input type="radio" name="active_courier" value="steadfast" {{ $activeCourier === 'steadfast' ? 'checked' : '' }} style="accent-color: #2563EB;">
                    Steadfast
                </label>

                <label style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; border: 1.5px solid {{ $activeCourier === 'pathao' ? '#DC2626' : '#E2E8F0' }}; background: {{ $activeCourier === 'pathao' ? '#FEF2F2' : '#FFFFFF' }}; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 700; color: #0F172A;">
                    <input type="radio" name="active_courier" value="pathao" {{ $activeCourier === 'pathao' ? 'checked' : '' }} style="accent-color: #DC2626;">
                    Pathao
                </label>

                <label style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; border: 1.5px solid {{ $activeCourier === 'redx' ? '#EA580C' : '#E2E8F0' }}; background: {{ $activeCourier === 'redx' ? '#FFF7ED' : '#FFFFFF' }}; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 700; color: #0F172A;">
                    <input type="radio" name="active_courier" value="redx" {{ $activeCourier === 'redx' ? 'checked' : '' }} style="accent-color: #EA580C;">
                    RedX
                </label>
            </div>
        </div>
    </div>

    <!-- 2. BD Courier Fraud Checker -->
    <div class="card" style="padding: 16px; margin-bottom: 16px; border-left: 4px solid #EA580C;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <h3 style="font-size: 14px; font-weight: 800; color: #C2410C; margin: 0; display: flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-shield-halved" style="color: #EA580C;"></i> BD Courier Fraud Checker API
            </h3>
            <a href="https://app.courier.com.bd/api" target="_blank" rel="noopener noreferrer" style="font-size: 11.5px; color: #2563EB; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> app.courier.com.bd/api
            </a>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 14px;">
            <div>
                <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 4px; color: #374151;">API Key / Secret Token</label>
                <input type="text" name="bdcourier_api_key" value="{{ $settings['bdcourier_api_key'] ?? '' }}" class="form-control" placeholder="Enter BD Courier API Key" style="font-family: monospace; font-size: 12.5px;">
            </div>
            <div>
                <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 4px; color: #374151;">API Endpoint</label>
                <input type="text" name="bdcourier_api_url" value="{{ $settings['bdcourier_api_url'] ?? 'https://api.bdcourier.com/courier-check' }}" class="form-control" style="font-family: monospace; font-size: 12px;">
            </div>
        </div>
    </div>

    <!-- 3. Steadfast Courier Credentials -->
    <div class="card" style="padding: 16px; margin-bottom: 16px; border-left: 4px solid #2563EB;">
        <h3 style="font-size: 14px; font-weight: 800; color: #1E3A8A; margin: 0 0 12px 0; display: flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-box-open" style="color: #2563EB;"></i> Steadfast Courier
        </h3>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px;">
            <div>
                <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 4px; color: #374151;">API Key</label>
                <input type="text" name="steadfast_api_key" value="{{ $settings['steadfast_api_key'] ?? '' }}" class="form-control" placeholder="Steadfast API Key" style="font-family: monospace; font-size: 12.5px;">
            </div>
            <div>
                <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 4px; color: #374151;">Secret Key</label>
                <input type="text" name="steadfast_secret_key" value="{{ $settings['steadfast_secret_key'] ?? '' }}" class="form-control" placeholder="Steadfast Secret Key" style="font-family: monospace; font-size: 12.5px;">
            </div>
            <div>
                <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 4px; color: #64748B;">Base URL</label>
                <input type="text" name="steadfast_base_url" value="{{ $settings['steadfast_base_url'] ?? 'https://portal.steadfast.com.bd/api/v1' }}" class="form-control" style="font-family: monospace; font-size: 11.5px;">
            </div>
        </div>
    </div>

    <!-- 4. Pathao Courier Credentials -->
    <div class="card" style="padding: 16px; margin-bottom: 16px; border-left: 4px solid #DC2626;">
        <h3 style="font-size: 14px; font-weight: 800; color: #991B1B; margin: 0 0 12px 0; display: flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-motorcycle" style="color: #DC2626;"></i> Pathao Courier
        </h3>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 12px;">
            <div>
                <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 4px; color: #374151;">Client ID</label>
                <input type="text" name="pathao_client_id" value="{{ $settings['pathao_client_id'] ?? '' }}" class="form-control" placeholder="Pathao Client ID" style="font-family: monospace; font-size: 12.5px;">
            </div>
            <div>
                <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 4px; color: #374151;">Client Secret</label>
                <input type="password" name="pathao_client_secret" value="{{ $settings['pathao_client_secret'] ?? '' }}" class="form-control" placeholder="Pathao Client Secret" style="font-family: monospace; font-size: 12.5px;">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 14px;">
            <div>
                <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 4px; color: #374151;">Email / Username</label>
                <input type="text" name="pathao_username" value="{{ $settings['pathao_username'] ?? '' }}" class="form-control" placeholder="merchant@email.com" style="font-size: 12.5px;">
            </div>
            <div>
                <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 4px; color: #374151;">Password</label>
                <input type="password" name="pathao_password" value="{{ $settings['pathao_password'] ?? '' }}" class="form-control" placeholder="Password" style="font-size: 12.5px;">
            </div>
            <div>
                <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 4px; color: #374151;">Store ID</label>
                <input type="text" name="pathao_store_id" value="{{ $settings['pathao_store_id'] ?? '' }}" class="form-control" placeholder="Store ID" style="font-size: 12.5px;">
            </div>
            <div>
                <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 4px; color: #64748B;">Base URL</label>
                <input type="text" name="pathao_base_url" value="{{ $settings['pathao_base_url'] ?? 'https://courier-api-bi.pathao.com' }}" class="form-control" style="font-family: monospace; font-size: 11.5px;">
            </div>
        </div>
    </div>

    <!-- 5. RedX Courier Credentials -->
    <div class="card" style="padding: 16px; margin-bottom: 20px; border-left: 4px solid #EA580C;">
        <h3 style="font-size: 14px; font-weight: 800; color: #9A3412; margin: 0 0 12px 0; display: flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-truck" style="color: #EA580C;"></i> RedX Courier
        </h3>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 14px;">
            <div>
                <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 4px; color: #374151;">Access Token</label>
                <input type="text" name="redx_api_token" value="{{ $settings['redx_api_token'] ?? '' }}" class="form-control" placeholder="RedX Bearer Token" style="font-family: monospace; font-size: 12.5px;">
            </div>
            <div>
                <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 4px; color: #64748B;">Base URL</label>
                <input type="text" name="redx_base_url" value="{{ $settings['redx_base_url'] ?? 'https://openapi.redx.com.bd/v1.0.0-beta' }}" class="form-control" style="font-family: monospace; font-size: 11.5px;">
            </div>
        </div>
    </div>

    <button type="submit" class="btn-admin-primary" style="padding: 10px 24px; font-size: 14px;">
        <i class="fa-solid fa-floppy-disk"></i> Save Settings
    </button>
</form>
@endsection
