@extends('layouts.app')

@section('title', 'Secure Checkout | Meghduti Paridhaan - Dhaka')

@push('styles')
<style>
    .mp-checkout-header {
        padding: 24px 0 14px;
        border-bottom: 1px solid #E5E7EB;
        margin-bottom: 24px;
    }
    .mp-checkout-title {
        font-size: 22px;
        font-weight: 800;
        color: #111827;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .mp-checkout-grid {
        display: grid;
        grid-template-columns: 1.35fr 1fr;
        gap: 28px;
        margin-bottom: 60px;
        align-items: start;
    }
    @media (max-width: 900px) {
        .mp-checkout-grid { grid-template-columns: 1fr; }
    }

    .mp-form-card {
        background: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .mp-section-subtitle {
        font-size: 14.5px;
        font-weight: 800;
        color: #1F2937;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        gap: 8px;
        padding-bottom: 10px;
        border-bottom: 1px solid #F3F4F6;
    }
    .step-num-badge {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: var(--brand-green);
        color: #FFFFFF;
        font-size: 11px;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .form-group-item {
        margin-bottom: 16px;
    }
    .form-item-label {
        display: block;
        font-size: 12.5px;
        font-weight: 700;
        color: #374151;
        margin-bottom: 6px;
    }
    .form-item-label span {
        color: #EF4444;
    }
    .form-item-input {
        width: 100%;
        padding: 10px 14px;
        border-radius: 4px;
        border: 1px solid #D1D5DB;
        font-size: 13px;
        outline: none;
        font-family: inherit;
        color: #111827;
        background: #FFFFFF;
        transition: border 0.2s, box-shadow 0.2s;
    }
    .form-item-input:focus {
        border-color: var(--brand-green);
        box-shadow: 0 0 0 3px rgba(30, 126, 52, 0.1);
    }

    /* Delivery Option Pills */
    .delivery-radio-box {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 14px;
        border: 1.5px solid #E5E7EB;
        border-radius: 6px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.2s;
        background: #FFFFFF;
    }
    .delivery-radio-box:hover {
        border-color: #9CA3AF;
    }
    .delivery-radio-box.active {
        border-color: var(--brand-green);
        background: #F0FDF4;
    }

    /* Payment Option Pills */
    .payment-radio-box {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 14px;
        border: 1.5px solid #E5E7EB;
        border-radius: 6px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.2s;
        background: #FFFFFF;
    }
    .payment-radio-box:hover {
        border-color: #9CA3AF;
    }
    .payment-radio-box.active {
        border-color: var(--brand-green);
        background: #F0FDF4;
    }

    /* Order Summary Item */
    .order-mini-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid #F3F4F6;
    }
    .order-mini-img {
        width: 52px;
        height: 60px;
        object-fit: cover;
        border-radius: 4px;
        background: #F4F4F5;
        border: 1px solid #E5E7EB;
    }

    .btn-confirm-order-green {
        display: block;
        width: 100%;
        background: var(--brand-green);
        color: #FFFFFF;
        padding: 14px;
        text-align: center;
        font-size: 14px;
        font-weight: 800;
        letter-spacing: 0.5px;
        border-radius: 4px;
        border: none;
        cursor: pointer;
        margin-top: 20px;
        transition: background 0.2s;
    }
    .btn-confirm-order-green:hover {
        background: var(--brand-green-hover);
    }
</style>
@endpush

@section('content')
<div class="container">
    
    <div class="mp-checkout-header">
        <h1 class="mp-checkout-title">
            <i class="fa-solid fa-lock" style="color: var(--brand-green); font-size: 18px;"></i>
            অর্ডার নিশ্চিতকরণ (Secure Checkout)
        </h1>
        <p style="font-size: 12.5px; color: #6B7280; margin-top: 4px;">
            অনুগ্রহ করে আপনার নাম, মোবাইল নম্বর ও সঠিক ঠিকানা প্রদান করে অর্ডারটি কনফার্ম করুন।
        </p>
    </div>

    @if (isset($errors) && $errors->any())
        <div style="background: #FEF2F2; border: 1px solid #FCA5A5; color: #991B1B; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; font-size: 13px;">
            <div style="font-weight: 700; margin-bottom: 4px;"><i class="fa-solid fa-triangle-exclamation"></i> অনুগ্রহ করে নিচের তথ্যগুলো পূরণ করুন:</div>
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
        @csrf
        
        <div class="mp-checkout-grid">
            
            <!-- Left Side: Shipping & Payment Details -->
            <div class="mp-form-card">
                
                <div class="mp-section-subtitle">
                    <span class="step-num-badge">১</span>
                    ডেলিভারি ঠিকানা ও কাস্টমার তথ্য
                </div>

                <div class="form-group-item">
                    <label class="form-item-label">আপনার পূর্ণ নাম <span>*</span></label>
                    <input type="text" name="customer_name" class="form-item-input" placeholder="আপনার নাম লিখুন..." value="{{ old('customer_name', auth('web')->user()?->name) }}" required>
                </div>

                <div class="form-group-item">
                    <label class="form-item-label">সচল মোবাইল নম্বর <span>*</span></label>
                    <input type="tel" name="customer_phone" class="form-item-input" placeholder="017xxxxxxxx..." value="{{ old('customer_phone', auth('web')->user()?->phone) }}" required>
                </div>

                <div class="form-group-item">
                    <label class="form-item-label">সম্পূর্ণ ডেলিভারি ঠিকানা (বাসা/রোড, থানা, জেলা) <span>*</span></label>
                    <textarea name="customer_address" rows="3" class="form-item-input" placeholder="বাসা নম্বর, রোড, এলাকা/গ্রাম, থানা, জেলা উল্লেখ করুন..." required>{{ old('customer_address', auth('web')->user()?->address) }}</textarea>
                </div>

                <div class="mp-section-subtitle" style="margin-top: 26px;">
                    <span class="step-num-badge">২</span>
                    ডেলিভারি এলাকা নির্বাচন করুন
                </div>

                <div class="form-group-item">
                    @foreach($shippingZones as $index => $zone)
                        <label class="delivery-radio-box {{ $index === 0 ? 'active' : '' }}" onclick="selectShippingZone({{ $zone->id }}, {{ $zone->charge }}, this)">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <input type="radio" name="shipping_zone_id" value="{{ $zone->id }}" {{ $index === 0 ? 'checked' : '' }} style="accent-color: var(--brand-green);">
                                <span style="font-weight: 700; font-size: 13.5px; color: #111827;">{{ $zone->name }}</span>
                            </div>
                            <span style="font-weight: 800; color: var(--brand-green);">৳ {{ number_format($zone->charge) }}</span>
                        </label>
                    @endforeach
                </div>

                <div class="mp-section-subtitle" style="margin-top: 26px;">
                    <span class="step-num-badge">৩</span>
                    পেমেন্ট পদ্ধতি
                </div>

                <div class="form-group-item">
                    <label class="payment-radio-box active" onclick="selectPaymentMethod(this)">
                        <input type="radio" name="payment_method" value="cod" checked style="accent-color: var(--brand-green);">
                        <i class="fa-solid fa-hand-holding-dollar" style="font-size: 22px; color: var(--brand-green);"></i>
                        <div>
                            <div style="font-weight: 700; font-size: 13.5px; color: #111827;">ক্যাশ অন ডেলিভারি (Cash on Delivery)</div>
                            <div style="font-size: 11.5px; color: #6B7280;">পণ্য হাতে পেয়ে দেখে ডেলিভারি ম্যানের কাছে টাকা দিন।</div>
                        </div>
                    </label>

                    <label class="payment-radio-box" onclick="selectPaymentMethod(this)">
                        <input type="radio" name="payment_method" value="bkash" style="accent-color: var(--brand-green);">
                        <i class="fa-solid fa-mobile-screen-button" style="color: #E2136E; font-size: 22px;"></i>
                        <div>
                            <div style="font-weight: 700; font-size: 13.5px; color: #111827;">বিকাশ পেমেন্ট (bKash)</div>
                            <div style="font-size: 11.5px; color: #6B7280;">বিকাশ একাউন্টের মাধ্যমে দ্রুত পেমেন্ট সম্পন্ন করুন।</div>
                        </div>
                    </label>
                </div>

                <div class="form-group-item" style="margin-top: 10px;">
                    <label class="form-item-label">অর্ডার সংক্রান্ত বিশেষ নির্দেশনা (ঐচ্ছিক)</label>
                    <input type="text" name="customer_note" class="form-item-input" placeholder="ডেলিভারির সময় কোনো বিশেষ তথ্য জানাতে চাইলে লিখুন...">
                </div>

            </div>

            <!-- Right Side: Order Summary -->
            <div>
                <div class="mp-form-card" style="position: sticky; top: 90px;">
                    
                    <div style="font-size: 15px; font-weight: 800; color: #111827; padding-bottom: 12px; border-bottom: 1px solid #E5E7EB; margin-bottom: 14px; display: flex; align-items: center; justify-content: space-between;">
                        <span>অর্ডার বিবরণী (Order Summary)</span>
                        <span style="font-size: 12px; color: var(--brand-green); font-weight: 700;">{{ count($cart) }} টি আইটেম</span>
                    </div>

                    <!-- Items List -->
                    <div style="max-height: 280px; overflow-y: auto; margin-bottom: 16px;">
                        @foreach($cart as $item)
                            <div class="order-mini-row">
                                <img src="{{ $item['image'] }}" class="order-mini-img" alt="{{ $item['name'] }}">
                                <div style="flex: 1;">
                                    <div style="font-size: 12.5px; font-weight: 700; color: #111827; line-height: 1.3;">{{ $item['name'] }}</div>
                                    @if(!empty($item['variant_info']))
                                        <div style="font-size: 11px; color: #6B7280;">{{ $item['variant_info'] }}</div>
                                    @endif
                                    <div style="font-size: 12px; color: #6B7280; margin-top: 3px;">
                                        ৳ {{ number_format($item['price']) }} × {{ $item['quantity'] }}
                                    </div>
                                </div>
                                <div style="font-weight: 800; font-size: 13.5px; color: #111827;">
                                    ৳ {{ number_format($item['price'] * $item['quantity']) }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Calculation Row -->
                    <div style="border-top: 1px solid #E5E7EB; padding-top: 12px; display: flex; flex-direction: column; gap: 8px; font-size: 13px;">
                        <div style="display: flex; justify-content: space-between; color: #4B5563;">
                            <span>সাবটোটাল:</span>
                            <span style="font-weight: 700; color: #111827;">৳ <span id="checkoutSubtotal">{{ number_format($subtotal) }}</span></span>
                        </div>
                        <div style="display: flex; justify-content: space-between; color: #4B5563;">
                            <span>ডেলিভারি চার্জ:</span>
                            <span style="font-weight: 700; color: #111827;">৳ <span id="checkoutShipping">{{ number_format($shippingCharge) }}</span></span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 16px; font-weight: 800; color: var(--brand-green); border-top: 1px dashed #D1D5DB; padding-top: 10px; margin-top: 4px;">
                            <span>সর্বমোট প্রদেয়:</span>
                            <span>৳ <span id="checkoutGrandTotal">{{ number_format($subtotal + $shippingCharge) }}</span></span>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="orderConfirmBtn" class="btn-confirm-order-green">
                        <i class="fa-solid fa-circle-check"></i> <span id="confirmBtnText">অর্ডার কনফার্ম করুন</span>
                    </button>

                    <!-- Trust badge -->
                    <div style="display: flex; align-items: center; justify-content: center; gap: 6px; margin-top: 14px; font-size: 11.5px; color: #6B7280;">
                        <i class="fa-solid fa-shield-halved" style="color: var(--brand-green);"></i> ১০০% নিরাপদ ও বিশ্বস্ত ডেলিভারি
                    </div>

                </div>
            </div>

        </div>

    </form>

</div>
@endsection

@push('scripts')
<script>
    let subtotal = {{ $subtotal }};

    // 1. GA4 & Meta Pixel: begin_checkout / InitiateCheckout Tracking (Instant + Safe)
    function triggerBeginCheckoutTracking() {
        if (window.trackEcommerceEvent) {
            window.trackEcommerceEvent('begin_checkout', {
                ecommerce: {
                    currency: 'BDT',
                    value: {{ (float) $subtotal }},
                    items: [
                        @foreach($cart as $item)
                        {
                            item_id: '{{ $item['product_id'] }}',
                            item_name: '{{ addslashes($item['name']) }}',
                            price: {{ (float) $item['price'] }},
                            quantity: {{ (int) $item['quantity'] }},
                            item_brand: 'Meghduti Paridhaan'
                        }{{ !$loop->last ? ',' : '' }}
                        @endforeach
                    ]
                }
            }, 'InitiateCheckout', {
                content_ids: [@foreach($cart as $item)'{{ $item['product_id'] }}'{{ !$loop->last ? ',' : '' }}@endforeach],
                contents: [
                    @foreach($cart as $item)
                    {
                        id: '{{ $item['product_id'] }}',
                        quantity: {{ (int) $item['quantity'] }},
                        item_price: {{ (float) $item['price'] }}
                    }{{ !$loop->last ? ',' : '' }}
                    @endforeach
                ],
                num_items: {{ array_sum(array_column($cart, 'quantity')) }},
                value: {{ (float) $subtotal }},
                currency: 'BDT'
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', triggerBeginCheckoutTracking);
    } else {
        triggerBeginCheckoutTracking();
    }

    // 2. Draft Order Auto-Save (Customer Name & Number Auto-Save)
    let draftSaveTimer = null;
    function autoSaveDraftOrder() {
        clearTimeout(draftSaveTimer);
        draftSaveTimer = setTimeout(function() {
            const name = document.querySelector('input[name="customer_name"]')?.value.trim() || '';
            const phone = document.querySelector('input[name="customer_phone"]')?.value.trim() || '';
            const address = document.querySelector('textarea[name="customer_address"]')?.value.trim() || '';
            const zoneId = document.querySelector('input[name="shipping_zone_id"]:checked')?.value || '';

            // Only auto-save if phone has at least 6 digits or name has at least 2 chars
            if (phone.length >= 6 || name.length >= 2) {
                fetch("{{ route('checkout.draft') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({
                        customer_name: name,
                        customer_phone: phone,
                        customer_address: address,
                        shipping_zone_id: zoneId
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        console.log('📝 Draft order auto-saved:', data.draft_id);
                    }
                })
                .catch(err => console.log('Draft save error:', err));
            }
        }, 1200);
    }

    // Attach listeners for customer inputs to trigger auto-save
    ['customer_name', 'customer_phone'].forEach(name => {
        const input = document.querySelector(`input[name="${name}"]`);
        if (input) {
            input.addEventListener('input', autoSaveDraftOrder);
            input.addEventListener('blur', autoSaveDraftOrder);
        }
    });
    const addrInput = document.querySelector('textarea[name="customer_address"]');
    if (addrInput) {
        addrInput.addEventListener('input', autoSaveDraftOrder);
        addrInput.addEventListener('blur', autoSaveDraftOrder);
    }

    function selectShippingZone(zoneId, charge, elem) {
        document.querySelectorAll('.delivery-radio-box').forEach(el => el.classList.remove('active'));
        elem.classList.add('active');
        const radio = elem.querySelector('input[type="radio"]');
        if (radio) radio.checked = true;

        document.getElementById('checkoutShipping').innerText = Number(charge).toLocaleString();
        document.getElementById('checkoutGrandTotal').innerText = (subtotal + Number(charge)).toLocaleString();
        autoSaveDraftOrder();
    }

    function selectPaymentMethod(elem) {
        document.querySelectorAll('.payment-radio-box').forEach(el => el.classList.remove('active'));
        elem.classList.add('active');
        const radio = elem.querySelector('input[type="radio"]');
        if (radio) radio.checked = true;
    }

    // 3. Confirm Order button event tracking on submit
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        // Track AddPaymentInfo & order_submit event
        if (window.trackEcommerceEvent) {
            const grandTotal = Number(document.getElementById('checkoutGrandTotal')?.innerText.replace(/,/g, '')) || subtotal;
            const payMethod = document.querySelector('input[name="payment_method"]:checked')?.value || 'cod';
            
            window.trackEcommerceEvent('add_payment_info', {
                ecommerce: {
                    currency: 'BDT',
                    value: grandTotal,
                    payment_type: payMethod.toUpperCase(),
                    items: [
                        @foreach($cart as $item)
                        {
                            item_id: '{{ $item['product_id'] }}',
                            item_name: '{{ addslashes($item['name']) }}',
                            price: {{ (float) $item['price'] }},
                            quantity: {{ (int) $item['quantity'] }},
                            item_brand: 'Meghduti Paridhaan'
                        }{{ !$loop->last ? ',' : '' }}
                        @endforeach
                    ]
                }
            }, 'AddPaymentInfo', {
                value: grandTotal,
                currency: 'BDT'
            });
        }

        const btn = document.getElementById('orderConfirmBtn');
        if (btn) {
            btn.style.opacity = '0.75';
            btn.style.pointerEvents = 'none';
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> <span>অর্ডার প্রসেসিং হচ্ছে...</span>';
        }
    });
</script>
@endpush
