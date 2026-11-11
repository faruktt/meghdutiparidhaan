@extends('layouts.app')

@section('title', 'Your Shopping Bag | Meghduti Paridhaan')

@push('styles')
<style>
    .cart-page-layout {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
        margin: 30px auto 60px;
    }
    @media (max-width: 900px) {
        .cart-page-layout { grid-template-columns: 1fr; }
    }
    .cart-table-card {
        background: #FFFFFF;
        border-radius: 8px;
        border: 1px solid #E5E7EB;
        padding: 24px;
    }
    .cart-summary-card {
        background: #FFFFFF;
        border-radius: 8px;
        border: 1px solid #E5E7EB;
        padding: 24px;
        height: fit-content;
        position: sticky;
        top: 90px;
    }
    .cart-row {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px 0;
        border-bottom: 1px solid #F3F4F6;
    }
    .cart-row-img {
        width: 75px;
        height: 90px;
        object-fit: cover;
        border-radius: 4px;
        background: #F4F4F5;
        border: 1px solid #E5E7EB;
    }
    .cart-row-info {
        flex: 1;
    }
    .cart-qty-ctrl {
        display: flex;
        align-items: center;
        border: 1px solid #D1D5DB;
        border-radius: 4px;
        width: fit-content;
    }
    .cart-qty-btn {
        background: none;
        border: none;
        padding: 6px 12px;
        cursor: pointer;
        font-weight: 700;
        color: #374151;
    }
    .cart-qty-btn:hover { background: #F3F4F6; }
    .cart-qty-val {
        width: 38px;
        text-align: center;
        font-weight: 700;
        font-size: 13px;
    }
    .summary-line {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 13px;
        color: #4B5563;
    }
    .summary-total {
        display: flex;
        justify-content: space-between;
        font-size: 16px;
        font-weight: 800;
        padding-top: 14px;
        border-top: 1px dashed #D1D5DB;
        margin: 14px 0 20px;
        color: var(--brand-green);
    }
    .btn-checkout-green-action {
        display: block;
        width: 100%;
        background: var(--brand-green);
        color: #FFFFFF;
        padding: 13px;
        text-align: center;
        font-size: 13.5px;
        font-weight: 700;
        border-radius: 4px;
        border: none;
        cursor: pointer;
        transition: background 0.2s;
        text-decoration: none;
    }
    .btn-checkout-green-action:hover {
        background: var(--brand-green-hover);
        color: #FFFFFF;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div style="padding: 24px 0 0;">
        <h1 style="font-size: 22px; font-weight: 800; color: #111827;">শপিং ব্যাগ (SHOPPING BAG)</h1>
    </div>

    @if(!empty($cart) && count($cart) > 0)
        <div class="cart-page-layout">
            <div class="cart-table-card">
                <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 16px; border-bottom: 1px solid #E5E7EB;">
                    <div style="font-weight: 700; font-size: 13.5px; color: #111827;">পণ্যের বিবরণী</div>
                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: #EF4444; font-size: 12px; font-weight: 600; cursor: pointer;">
                            <i class="fa-solid fa-trash-can"></i> সব পণ্য মুছুন
                        </button>
                    </form>
                </div>

                @foreach($cart as $key => $item)
                    <div class="cart-row" id="page-cart-item-{{ $key }}">
                        <img src="{{ $item['image'] }}" class="cart-row-img" alt="{{ $item['name'] }}">
                        <div class="cart-row-info">
                            <h4 style="font-size: 14px; font-weight: 700; margin-bottom: 4px; color: #111827;">{{ $item['name'] }}</h4>
                            @if(!empty($item['variant_info']))
                                <div style="font-size: 11px; color: #6B7280; margin-bottom: 6px;">{{ $item['variant_info'] }}</div>
                            @endif
                            <div style="font-size: 14px; font-weight: 800; color: var(--brand-green);">৳ {{ number_format($item['price']) }}</div>
                        </div>

                        <div class="cart-qty-ctrl">
                            <form action="{{ route('cart.update') }}" method="POST" style="display: flex; align-items: center;">
                                @csrf
                                <input type="hidden" name="key" value="{{ $key }}">
                                <button type="submit" name="quantity" value="{{ max(1, $item['quantity'] - 1) }}" class="cart-qty-btn">-</button>
                                <span class="cart-qty-val">{{ $item['quantity'] }}</span>
                                <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}" class="cart-qty-btn">+</button>
                            </form>
                        </div>

                        <div style="font-weight: 800; font-size: 15px; min-width: 90px; text-align: right; color: #111827;">
                            ৳ {{ number_format($item['price'] * $item['quantity']) }}
                        </div>

                        <form action="{{ route('cart.remove', $key) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: none; border: none; color: #9CA3AF; cursor: pointer; padding: 6px;" title="Remove Item">
                                <i class="fa-solid fa-xmark" style="font-size: 16px;"></i>
                            </button>
                        </form>
                    </div>
                @endforeach

                <div style="margin-top: 24px;">
                    <a href="{{ route('shop.index') }}" style="color: var(--brand-green); font-weight: 700; font-size: 13px;">
                        <i class="fa-solid fa-arrow-left"></i> আরও শাড়ি ও পোশাক দেখুন
                    </a>
                </div>
            </div>

            <div class="cart-summary-card">
                <h3 style="font-size: 15px; font-weight: 800; color: #111827; margin-bottom: 14px; border-bottom: 1px solid #E5E7EB; padding-bottom: 8px;">অর্ডার সারাংশ</h3>
                <div class="summary-line">
                    <span>মোট আইটেম:</span>
                    <strong style="color: #111827;">{{ array_sum(array_column($cart, 'quantity')) }} টি</strong>
                </div>
                <div class="summary-line">
                    <span>সাবটোটাল:</span>
                    <strong style="color: #111827;">৳ {{ number_format($total) }}</strong>
                </div>
                <div class="summary-line">
                    <span>ডেলিভারি চার্জ:</span>
                    <span style="font-size: 12px; color: var(--brand-green); font-weight: 700;">চেকআউট পেজে যুক্ত হবে</span>
                </div>

                <div class="summary-total">
                    <span>সর্বমোট:</span>
                    <span>৳ {{ number_format($total) }}</span>
                </div>

                <a href="{{ route('checkout.index') }}" class="btn-checkout-green-action">
                    <i class="fa-solid fa-lock"></i> চেকআউট সম্পন্ন করুন
                </a>
            </div>
        </div>
    @else
        <div style="text-align: center; padding: 70px 20px; background: white; border-radius: 8px; border: 1px solid #E5E7EB; margin: 30px 0 60px;">
            <i class="fa-solid fa-bag-shopping" style="font-size: 50px; color: #D1D5DB; margin-bottom: 16px;"></i>
            <h2 style="font-size: 18px; font-weight: 800; color: #111827;">আপনার শপিং ব্যাগ বর্তমানে খালি</h2>
            <p style="color: #6B7280; margin: 8px 0 20px; font-size: 13px;">মেঘদূতী পরিধান এর এক্সক্লুসিভ শাড়ি ও থ্রি-পিস কালেকশন দেখুন।</p>
            <a href="{{ route('shop.index') }}" class="btn-checkout-green-action" style="display: inline-block; width: auto; padding: 10px 32px;">
                কেনাকাটা শুরু করুন
            </a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // GA4: view_cart Tracking
    document.addEventListener('DOMContentLoaded', function() {
        if (window.trackEcommerceEvent && {{ !empty($cart) && count($cart) > 0 ? 'true' : 'false' }}) {
            window.trackEcommerceEvent('view_cart', {
                ecommerce: {
                    currency: 'BDT',
                    value: {{ (float) $total }},
                    items: [
                        @if(!empty($cart))
                            @foreach($cart as $item)
                            {
                                item_id: '{{ $item['product_id'] }}',
                                item_name: '{{ addslashes($item['name']) }}',
                                price: {{ (float) $item['price'] }},
                                quantity: {{ (int) $item['quantity'] }},
                                item_brand: 'Meghduti Paridhaan'
                            }{{ !$loop->last ? ',' : '' }}
                            @endforeach
                        @endif
                    ]
                }
            });
        }
    });
</script>
@endpush
