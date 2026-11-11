@extends('layouts.app')

@section('title', 'Order Confirmed - Thank You | Meghduti Paridhaan')

@section('content')
<div class="container" style="max-width: 720px; margin: 40px auto 70px;">
    <div style="background: white; border-radius: 8px; border: 1px solid #E5E7EB; padding: 40px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
        <div style="width: 70px; height: 70px; border-radius: 50%; background: #ECFDF5; color: var(--brand-green); display: inline-flex; align-items: center; justify-content: center; font-size: 36px; margin-bottom: 20px;">
            <i class="fa-solid fa-circle-check"></i>
        </div>

        <h1 style="font-size: 22px; font-weight: 800; color: #111827; margin-bottom: 8px;">
            ধন্যবাদ! আপনার অর্ডারটি সফলভাবে গৃহীত হয়েছে
        </h1>
        <p style="color: #6B7280; font-size: 13.5px; margin-bottom: 24px;">
            আমাদের কাস্টমার কেয়ার প্রতিনিধি শীঘ্রই আপনার সাথে যোগাযোগ করে ডেলিভারি নিশ্চিত করবেন।
        </p>

        <!-- Order Number Badge -->
        <div style="background: #F9FAFB; border: 1.5px dashed var(--brand-green); border-radius: 6px; padding: 18px; margin-bottom: 28px;">
            <div style="font-size: 12px; font-weight: 700; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">ORDER NUMBER</div>
            <div style="font-size: 26px; font-weight: 900; color: var(--brand-green); margin-top: 4px; letter-spacing: 1px;">
                {{ $order->order_number }}
            </div>
            <div style="font-size: 12px; color: #9CA3AF; margin-top: 4px;">
                অর্ডার তারিখ: {{ $order->created_at->format('M d, Y h:i A') }}
            </div>
        </div>

        <!-- Details Grid -->
        <div style="text-align: left; background: #FAFAFA; border-radius: 6px; padding: 20px; margin-bottom: 28px; border: 1px solid #E5E7EB;">
            <h3 style="font-size: 13.5px; font-weight: 800; color: #111827; margin-bottom: 12px; border-bottom: 1px solid #E5E7EB; padding-bottom: 8px;">
                ডেলিভারি বিবরণ
            </h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; font-size: 13px;">
                <div>
                    <span style="color: #6B7280;">কাস্টমার নাম:</span>
                    <strong style="display: block; color: #111827; margin-top: 2px;">{{ $order->customer_name }}</strong>
                </div>
                <div>
                    <span style="color: #6B7280;">মোবাইল নম্বর:</span>
                    <strong style="display: block; color: #111827; margin-top: 2px;">{{ $order->customer_phone }}</strong>
                </div>
                <div style="grid-column: span 2;">
                    <span style="color: #6B7280;">ঠিকানা:</span>
                    <strong style="display: block; color: #111827; margin-top: 2px;">{{ $order->customer_address }}</strong>
                </div>
                <div>
                    <span style="color: #6B7280;">পেমেন্ট পদ্ধতি:</span>
                    <strong style="display: block; color: #111827; margin-top: 2px; text-transform: uppercase;">{{ $order->payment_method === 'cod' ? 'Cash on Delivery (COD)' : $order->payment_method }}</strong>
                </div>
                <div>
                    <span style="color: #6B7280;">মোট প্রদেয়:</span>
                    <strong style="display: block; color: var(--brand-green); font-size: 16px; margin-top: 2px;">৳ {{ number_format($order->total) }}</strong>
                </div>
            </div>
        </div>

        <!-- Ordered Items -->
        <div style="text-align: left; margin-bottom: 28px;">
            <h3 style="font-size: 13.5px; font-weight: 800; color: #111827; margin-bottom: 10px;">অর্ডারকৃত পণ্যসমূহ:</h3>
            @foreach($order->items as $item)
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px dashed #E5E7EB; font-size: 13px;">
                    <div>
                        <strong>{{ $item->product_name }}</strong>
                        @if($item->variant_info)
                            <span style="color: #6B7280; font-size: 11.5px;">({{ $item->variant_info }})</span>
                        @endif
                        <span style="color: #6B7280;"> × {{ $item->quantity }}</span>
                    </div>
                    <strong style="color: #111827;">৳ {{ number_format($item->subtotal) }}</strong>
                </div>
            @endforeach
        </div>

        <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
            <a href="{{ route('track.order', ['search' => $order->order_number]) }}" style="background: var(--brand-green); color: #FFFFFF; padding: 10px 24px; border-radius: 4px; font-size: 13px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-truck-fast"></i> ট্র্যাক অর্ডার
            </a>
            <a href="{{ route('shop.index') }}" style="background: #FFFFFF; border: 1px solid #D1D5DB; color: #374151; padding: 10px 24px; border-radius: 4px; font-size: 13px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-bag-shopping"></i> আরও কেনাকাটা করুন
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // GA4 & Meta Pixel: purchase / Purchase Tracking (Instant & Safe)
    function triggerPurchaseTracking() {
        if (window.trackEcommerceEvent) {
            window.trackEcommerceEvent('purchase', {
                ecommerce: {
                    transaction_id: '{{ $order->order_number }}',
                    value: {{ (float) $order->total }},
                    tax: 0,
                    shipping: {{ (float) ($order->delivery_fee ?? $order->shipping_charge ?? 0) }},
                    currency: 'BDT',
                    items: [
                        @foreach($order->items as $item)
                        {
                            item_id: '{{ $item->product_id }}',
                            item_name: '{{ addslashes($item->product_name) }}',
                            price: {{ (float) $item->unit_price }},
                            quantity: {{ (int) $item->quantity }},
                            item_brand: 'Meghduti Paridhaan'
                        }{{ !$loop->last ? ',' : '' }}
                        @endforeach
                    ]
                }
            }, 'Purchase', {
                content_type: 'product',
                contents: [
                    @foreach($order->items as $item)
                    {
                        id: '{{ $item->product_id }}',
                        quantity: {{ (int) $item->quantity }},
                        item_price: {{ (float) $item->unit_price }}
                    }{{ !$loop->last ? ',' : '' }}
                    @endforeach
                ],
                content_ids: [@foreach($order->items as $item)'{{ $item->product_id }}'{{ !$loop->last ? ',' : '' }}@endforeach],
                num_items: {{ $order->items->sum('quantity') }},
                value: {{ (float) $order->total }},
                currency: 'BDT'
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', triggerPurchaseTracking);
    } else {
        triggerPurchaseTracking();
    }
</script>
@endpush
