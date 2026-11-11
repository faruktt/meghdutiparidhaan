@extends('layouts.app')

@section('title', 'Track Your Order | ' . \App\Models\Setting::get('site_name', 'Meghduti Paridhaan'))

@push('styles')
<style>
    .track-stepper {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin: 40px 0;
    }
    .track-stepper::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 20px;
        right: 20px;
        height: 2px;
        background: #E4E4E7;
        z-index: 1;
    }
    .step-item {
        position: relative;
        z-index: 2;
        text-align: center;
        flex: 1;
    }
    .step-icon {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #FFFFFF;
        border: 2px solid #E4E4E7;
        color: #A1A1AA;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        margin-bottom: 8px;
        transition: all 0.3s;
    }
    .step-item.completed .step-icon {
        background: #10B981;
        border-color: #10B981;
        color: white;
    }
    .step-item.active .step-icon {
        background: #B5876D;
        border-color: #B5876D;
        color: white;
        box-shadow: 0 0 0 4px rgba(181, 135, 109, 0.25);
    }
    .step-title {
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #71717A;
    }
    .step-item.completed .step-title, .step-item.active .step-title {
        color: #18181B;
    }
</style>
@endpush

@section('content')
<div class="container" style="max-width: 800px; margin: 40px auto 70px;">
    <!-- Search Form -->
    <div style="background: white; border-radius: 4px; border: 1px solid #E4E4E7; padding: 36px; text-align: center; margin-bottom: 30px;">
        <h1 style="font-size: 22px; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase; color: #18181B; margin-bottom: 8px;">
            <i class="fa-solid fa-truck-fast" style="color: #B5876D; margin-right: 6px;"></i> LIVE ORDER TRACKING
        </h1>
        <p style="color: #71717A; font-size: 13px; margin-bottom: 24px;">
            Enter your order number or phone number below to check real-time status
        </p>

        <form action="{{ route('track.order') }}" method="GET" style="display: flex; gap: 10px; max-width: 500px; margin: 0 auto;">
            <input type="text" name="search" value="{{ request('search') }}" style="flex: 1; padding: 12px 18px; font-size: 13.5px; border: 1px solid #D4D4D8; border-radius: 2px; outline: none;" placeholder="e.g. ORD-20261028-1234 or 017xxxxxxxx" required>
            <button type="submit" class="btn-black-pill" style="width: auto; padding: 12px 28px; margin: 0; border-radius: 2px;">
                TRACK NOW
            </button>
        </form>
    </div>

    @if(request('search'))
        @if($order)
            @php
                $statusHierarchy = ['pending' => 1, 'confirmed' => 2, 'processing' => 3, 'shipped' => 4, 'delivered' => 5];
                $currentStep = $statusHierarchy[$order->status] ?? 1;
            @endphp
            <div style="background: white; border-radius: 4px; border: 1px solid #E4E4E7; padding: 36px;">
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #E4E4E7; padding-bottom: 16px;">
                    <div>
                        <div style="font-size: 11px; font-weight: 700; color: #71717A; text-transform: uppercase; letter-spacing: 1px;">ORDER NUMBER</div>
                        <h2 style="font-size: 20px; font-weight: 900; color: #18181B; letter-spacing: 1px;">{{ $order->order_number }}</h2>
                    </div>
                    <div style="text-align: right;">
                        <span style="display: inline-block; padding: 6px 14px; border-radius: 2px; font-size: 11px; font-weight: 800; background: #000000; color: white; text-transform: uppercase; letter-spacing: 0.5px;">
                            {{ $order->status }}
                        </span>
                        <div style="font-size: 11.5px; color: #71717A; margin-top: 4px;">{{ $order->created_at->format('M d, Y') }}</div>
                    </div>
                </div>

                <!-- Live Stepper -->
                <div class="track-stepper">
                    <div class="step-item {{ $currentStep > 1 ? 'completed' : ($currentStep == 1 ? 'active' : '') }}">
                        <div class="step-icon"><i class="fa-solid fa-receipt"></i></div>
                        <div class="step-title">Pending</div>
                    </div>
                    <div class="step-item {{ $currentStep > 2 ? 'completed' : ($currentStep == 2 ? 'active' : '') }}">
                        <div class="step-icon"><i class="fa-solid fa-check"></i></div>
                        <div class="step-title">Confirmed</div>
                    </div>
                    <div class="step-item {{ $currentStep > 3 ? 'completed' : ($currentStep == 3 ? 'active' : '') }}">
                        <div class="step-icon"><i class="fa-solid fa-box-open"></i></div>
                        <div class="step-title">Processing</div>
                    </div>
                    <div class="step-item {{ $currentStep > 4 ? 'completed' : ($currentStep == 4 ? 'active' : '') }}">
                        <div class="step-icon"><i class="fa-solid fa-truck"></i></div>
                        <div class="step-title">Shipped</div>
                    </div>
                    <div class="step-item {{ $currentStep == 5 ? 'completed active' : '' }}">
                        <div class="step-icon"><i class="fa-solid fa-house-chimney"></i></div>
                        <div class="step-title">Delivered</div>
                    </div>
                </div>

                <!-- Order Info -->
                <div style="background: #FAFAFA; border-radius: 4px; padding: 20px; margin-top: 30px; border: 1px solid #E4E4E7;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; font-size: 13px;">
                        <div>
                            <span style="color: #71717A;">Recipient:</span>
                            <strong style="display: block; color: #18181B;">{{ $order->customer_name }}</strong>
                        </div>
                        <div>
                            <span style="color: #71717A;">Phone:</span>
                            <strong style="display: block; color: #18181B;">{{ $order->customer_phone }}</strong>
                        </div>
                        <div style="grid-column: span 2;">
                            <span style="color: #71717A;">Delivery Address:</span>
                            <strong style="display: block; color: #18181B;">{{ $order->customer_address }}</strong>
                        </div>
                        <div>
                            <span style="color: #71717A;">Payment:</span>
                            <strong style="display: block; color: #18181B; text-transform: uppercase;">{{ $order->payment_method }} ({{ $order->payment_status }})</strong>
                        </div>
                        <div>
                            <span style="color: #71717A;">Total Payable:</span>
                            <strong style="display: block; color: #B5876D; font-size: 15px;">৳ {{ number_format($order->total) }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div style="text-align: center; padding: 40px; background: white; border-radius: 4px; border: 1px solid #E4E4E7;">
                <i class="fa-solid fa-circle-exclamation" style="font-size: 36px; color: #EF4444; margin-bottom: 12px;"></i>
                <h3 style="font-size: 16px; font-weight: 700; text-transform: uppercase;">No order found</h3>
                <p style="color: #71717A; font-size: 13px; margin-top: 4px;">Please check your order number or phone number and try again.</p>
            </div>
        @endif
    @endif
</div>
@endsection
