@extends('layouts.admin')

@section('title', 'Order #' . $order->order_number)

@section('content')
<div class="card-header-flex">
    <div>
        <a href="{{ route('admin.orders.index') }}" style="font-size: 13px; color: var(--text-muted); text-decoration: none;">
            <i class="fa-solid fa-arrow-left"></i> Back to Orders
        </a>
        <h1 style="font-size: 22px; font-weight: 800; color: var(--text-dark); margin-top: 4px;">
            Order #{{ $order->order_number }}
        </h1>
    </div>
    <div style="display: flex; gap: 8px; align-items: center;">
        <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" class="btn-admin-secondary">
            <i class="fa-solid fa-print"></i> Print Invoice
        </a>
        <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই অর্ডারটি (#{{ $order->order_number }}) মুছে ফেলতে চান? মুছে ফেললে অর্ডারটি সম্পূর্ণ ডিলিট হয়ে যাবে।');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-admin-secondary" style="color: #DC2626; border-color: #FCA5A5; background: #FFF5F5;" title="Delete Order">
                <i class="fa-solid fa-trash-can"></i> Delete Order
            </button>
        </form>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; @media(max-width: 900px){grid-template-columns: 1fr;}">
    <!-- Left Column: Items & Totals -->
    <div>
        <div class="table-card-container" style="margin-bottom: 20px;">
            <div style="padding: 12px 16px; border-bottom: 1px solid var(--admin-border); background: #FFFFFF;">
                <h3 style="font-size: 13.5px; font-weight: 700; color: #111827; margin: 0;">অর্ডার আইটেমস ({{ $order->items->count() }})</h3>
            </div>
            <div style="overflow-x: auto;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>প্রোডাক্ট</th>
                            <th>ভেরিয়েন্ট</th>
                            <th>একক মূল্য</th>
                            <th style="text-align: center;">পরিমাণ</th>
                            <th style="text-align: right;">মোট</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div style="font-weight: 700;">{{ $item->product_name }}</div>
                                </td>
                                <td>
                                    <span style="font-size: 12px; color: var(--text-muted);">{{ $item->variant_info ?? 'Standard' }}</span>
                                </td>
                                <td>৳{{ number_format($item->price) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td style="text-align: right; font-weight: 700;">
                                    ৳{{ number_format($item->subtotal) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Summary Breakdown -->
            <div style="padding: 20px; background: #F8FAFC; border-top: 1px solid var(--admin-border);">
                <div style="max-width: 300px; margin-left: auto;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 13.5px;">
                        <span style="color: var(--text-muted);">Subtotal:</span>
                        <strong>৳{{ number_format($order->subtotal) }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 13.5px;">
                        <span style="color: var(--text-muted);">Delivery Charge:</span>
                        <strong>৳{{ number_format($order->shipping_charge) }}</strong>
                    </div>
                    @if($order->discount_amount > 0)
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 13.5px; color: #10B981;">
                            <span>Discount:</span>
                            <strong>-৳{{ number_format($order->discount_amount) }}</strong>
                        </div>
                    @endif
                    <div style="display: flex; justify-content: space-between; font-size: 18px; font-weight: 800; color: var(--admin-primary); border-top: 2px dashed var(--admin-border); padding-top: 10px; margin-top: 10px;">
                        <span>Grand Total:</span>
                        <span>৳{{ number_format($order->total) }}</span>
                    </div>
                </div>
            </div>
        </div>

        @if($order->customer_note)
            <div class="card">
                <h4 style="font-size: 14px; font-weight: 700; margin-bottom: 8px; color: var(--text-dark);">Customer Note:</h4>
                <p style="font-size: 13.5px; color: var(--text-muted); line-height: 1.5;">{{ $order->customer_note }}</p>
            </div>
        @endif
    </div>

    <!-- Right Column: Status Update & Customer Info -->
    <div>
        <!-- Courier Booking Card -->
        @php
            $activeCourier = \App\Services\CourierService::getActiveCourier();
        @endphp
        <div class="card" style="border: 1px solid #BFDBFE; background: #F8FAFC;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; border-bottom: 1px solid #E2E8F0; padding-bottom: 8px;">
                <h3 class="card-title" style="margin: 0; font-size: 14px; display: flex; align-items: center; gap: 6px; color: #1E3A8A;">
                    <i class="fa-solid fa-truck-fast" style="color: #2563EB;"></i> কুরিয়ার বুকিং
                </h3>
                <span style="font-size: 11px; font-weight: 700; color: #64748B;">Active: {{ ucfirst($activeCourier) }}</span>
            </div>

            @if($order->consignment_id)
                <div style="font-size: 13px; line-height: 1.6; margin-bottom: 12px;">
                    <div><span style="color: #64748B;">কুরিয়ার:</span> <strong style="text-transform: capitalize;">{{ $order->courier_name_formatted }}</strong></div>
                    <div><span style="color: #64748B;">Consignment / Tracking ID:</span> <strong style="font-family: monospace; color: #2563EB;">{{ $order->consignment_id }}</strong></div>
                    <div><span style="color: #64748B;">স্ট্যাটাস:</span> <span class="badge" style="background: #EFF6FF; color: #1D4ED8;">{{ $order->courier_status ?? 'in_review' }}</span></div>
                </div>
                <button type="button" class="btn-admin-secondary" style="width: 100%; justify-content: center; font-size: 12px;" onclick="openCourierModal({{ $order->id }}, '{{ addslashes($order->order_number) }}', '{{ addslashes($order->customer_name) }}', '{{ addslashes($order->customer_phone) }}', '{{ addslashes($order->customer_address) }}', {{ (float)$order->total }}, '{{ $order->courier_name ?: $activeCourier }}')">
                    <i class="fa-solid fa-pen-to-square"></i> তথ্য পরিবর্তন করে পুনরায় পাঠান
                </button>
            @else
                <p style="font-size: 12px; color: #64748B; margin-bottom: 12px;">
                    এই অর্ডারটি এখনো কোনো কুরিয়ারে বুকিং করা হয়নি। এক ক্লিকে বুকিং করতে নিচের বাটনে চাপ দিন।
                </p>
                <button type="button" class="btn-admin-primary" style="width: 100%; justify-content: center; font-size: 13px; background: #2563EB;" onclick="openCourierModal({{ $order->id }}, '{{ addslashes($order->order_number) }}', '{{ addslashes($order->customer_name) }}', '{{ addslashes($order->customer_phone) }}', '{{ addslashes($order->customer_address) }}', {{ (float)$order->total }}, '{{ $activeCourier }}')">
                    <i class="fa-solid fa-truck-fast"></i> {{ ucfirst($activeCourier) }} এ অর্ডার পাঠান
                </button>
            @endif
        </div>

        <!-- Update Status Card -->
        <div class="card">
            <h3 class="card-title" style="margin-bottom: 16px;">Update Order Status</h3>
            <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 6px; color: var(--text-muted); text-transform: uppercase;">Order Pipeline Status</label>
                    <select name="status" class="form-control" style="font-weight: 700;">
                        @foreach($statuses as $key => $status)
                            <option value="{{ $key }}" {{ $order->status === $key ? 'selected' : '' }}>{{ is_array($status) ? $status['label'] : $status }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 6px; color: var(--text-muted); text-transform: uppercase;">Internal Admin Note</label>
                    <textarea name="admin_note" rows="2" class="form-control" placeholder="Add tracking code or internal note...">{{ $order->admin_note }}</textarea>
                </div>

                <button type="submit" class="btn-admin-primary" style="width: 100%; justify-content: center;">
                    Save Status Update
                </button>
            </form>
        </div>

        <!-- Customer Information -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                <h3 class="card-title" style="margin: 0;">Customer Details</h3>
                <div id="orderDetailFraudBox">
                    <button type="button" class="btn-admin-secondary" style="font-size: 11px; padding: 4px 8px;" onclick="checkCourierFraudShow('{{ $order->customer_phone }}')">
                        <i class="fa-solid fa-shield-halved" style="color: #2563EB;"></i> Fraud Check
                    </button>
                </div>
            </div>
            <div style="font-size: 13.5px; display: flex; flex-direction: column; gap: 10px;">
                <div>
                    <span style="color: var(--text-muted); font-size: 11px; text-transform: uppercase; font-weight: 700; display: block;">Name</span>
                    <strong>{{ $order->customer_name }}</strong>
                </div>
                <div>
                    <span style="color: var(--text-muted); font-size: 11px; text-transform: uppercase; font-weight: 700; display: block;">Phone</span>
                    <div style="display: flex; align-items: center; gap: 10px; margin-top: 2px;">
                        <a href="tel:{{ $order->customer_phone }}" style="color: var(--admin-primary); font-weight: 700; text-decoration: none;">
                            <i class="fa-solid fa-phone"></i> {{ $order->customer_phone }}
                        </a>
                        @php
                            $cPhone = preg_replace('/[^0-9]/', '', $order->customer_phone);
                            if(strlen($cPhone) === 11 && str_starts_with($cPhone, '01')) {
                                $cPhone = '88' . $cPhone;
                            }
                        @endphp
                        <a href="https://wa.me/{{ $cPhone }}" target="_blank" style="color: #16A34A; font-weight: 700; text-decoration: none; font-size: 12px;">
                            <i class="fa-brands fa-whatsapp"></i> WhatsApp
                        </a>
                    </div>
                </div>
                <div>
                    <span style="color: var(--text-muted); font-size: 11px; text-transform: uppercase; font-weight: 700; display: block;">Delivery Address</span>
                    <p style="margin: 0; line-height: 1.4;">{{ $order->customer_address }}</p>
                </div>
                <div>
                    <span style="color: var(--text-muted); font-size: 11px; text-transform: uppercase; font-weight: 700; display: block;">Shipping Zone</span>
                    <strong>{{ $order->shippingZone?->name ?? 'Standard' }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Send to Courier Modal -->
<div id="sendCourierModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 250; align-items: center; justify-content: center; backdrop-filter: blur(2px);">
    <div style="background: white; border-radius: 8px; width: 100%; max-width: 520px; padding: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.25);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; border-bottom: 1px solid #E2E8F0; padding-bottom: 10px;">
            <div>
                <h3 style="font-size: 16px; font-weight: 800; margin: 0; color: #0F172A; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-truck-fast" style="color: #2563EB;"></i> কুরিয়ারে বুকিং / অর্ডার ক্রিয়েট
                </h3>
                <span id="courierOrderSubtitle" style="font-size: 12px; color: #64748B;">Order: #{{ $order->order_number }}</span>
            </div>
            <button type="button" onclick="closeCourierModal()" style="background: none; border: none; font-size: 18px; cursor: pointer; color: #64748B;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="courierDispatchForm" onsubmit="submitCourierDispatch(event)">
            @csrf
            
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12px; font-weight: 700; color: #374151; margin-bottom: 4px;">কুরিয়ার সার্ভিস নির্বাচন করুন</label>
                <select name="courier_name" id="modalCourierSelect" class="form-control" style="font-weight: 700;">
                    <option value="steadfast">Steadfast Courier</option>
                    <option value="pathao">Pathao Courier</option>
                    <option value="redx">RedX Courier</option>
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #374151; margin-bottom: 4px;">কাস্টমার নাম *</label>
                    <input type="text" name="customer_name" id="modalCustomerName" class="form-control" required>
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #374151; margin-bottom: 4px;">মোবাইল নম্বর *</label>
                    <input type="text" name="customer_phone" id="modalCustomerPhone" class="form-control" required>
                </div>
            </div>

            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12px; font-weight: 700; color: #374151; margin-bottom: 4px;">সম্পূর্ণ ডেলিভারি ঠিকানা *</label>
                <textarea name="customer_address" id="modalCustomerAddress" rows="2" class="form-control" required></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #374151; margin-bottom: 4px;">ক্যাশ অন ডেলিভারি (COD Amount) *</label>
                    <input type="number" step="0.01" name="cod_amount" id="modalCodAmount" class="form-control" required style="font-weight: 700; color: var(--admin-primary);">
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #374151; margin-bottom: 4px;">ডেলিভারি নোট (Optional)</label>
                    <input type="text" name="note" id="modalDeliveryNote" class="form-control" placeholder="যেমন: সাবধানে ডেলিভারি করুন">
                </div>
            </div>

            <div id="courierModalAlert" style="display: none; padding: 10px; border-radius: 6px; font-size: 12px; margin-bottom: 12px;"></div>

            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" onclick="closeCourierModal()" class="btn-admin-secondary">বাতিল</button>
                <button type="submit" id="courierSubmitBtn" class="btn-orange-search" style="padding: 8px 18px;">
                    <i class="fa-solid fa-paper-plane"></i> কুরিয়ারে অর্ডার পাঠান
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Fraud Checker Detail Popup Modal -->
<div id="fraudModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 260; align-items: center; justify-content: center; backdrop-filter: blur(2px);">
    <div style="background: white; border-radius: 8px; width: 100%; max-width: 440px; padding: 22px; box-shadow: 0 10px 25px rgba(0,0,0,0.25); max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; border-bottom: 1px solid #E2E8F0; padding-bottom: 8px;">
            <strong style="font-size: 14px; color: #0F172A; display: flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-shield-halved" style="color: #EA580C;"></i> BD Courier ফ্রড চেকার রিপোর্ট
            </strong>
            <button type="button" onclick="closeFraudModal()" style="background: none; border: none; font-size: 16px; cursor: pointer; color: #64748B;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div id="fraudModalBody"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let activeCourierOrderId = {{ $order->id }};

    function openCourierModal(orderId, orderNumber, name, phone, address, total, courier) {
        activeCourierOrderId = orderId;
        document.getElementById('modalCustomerName').value = name;
        document.getElementById('modalCustomerPhone').value = phone;
        document.getElementById('modalCustomerAddress').value = address;
        document.getElementById('modalCodAmount').value = total;
        document.getElementById('modalCourierSelect').value = courier || 'steadfast';
        document.getElementById('courierModalAlert').style.display = 'none';
        document.getElementById('sendCourierModal').style.display = 'flex';
    }

    function closeCourierModal() {
        document.getElementById('sendCourierModal').style.display = 'none';
    }

    function submitCourierDispatch(e) {
        e.preventDefault();
        const submitBtn = document.getElementById('courierSubmitBtn');
        const alertBox = document.getElementById('courierModalAlert');

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> কুরিয়ারে বুকিং হচ্ছে...';
        alertBox.style.display = 'none';

        const payload = {
            customer_name: document.getElementById('modalCustomerName').value,
            customer_phone: document.getElementById('modalCustomerPhone').value,
            customer_address: document.getElementById('modalCustomerAddress').value,
            cod_amount: document.getElementById('modalCodAmount').value,
            note: document.getElementById('modalDeliveryNote').value,
            courier_name: document.getElementById('modalCourierSelect').value
        };

        fetch(`/admin/orders/${activeCourierOrderId}/send-to-courier`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> কুরিয়ারে অর্ডার পাঠান';

            if (data.success) {
                alertBox.style.display = 'block';
                alertBox.style.background = '#DCFCE7';
                alertBox.style.color = '#15803D';
                alertBox.innerHTML = `<strong>সফল!</strong> ${data.message} Tracking Code: <strong>${data.tracking_code || data.consignment_id}</strong>`;
                setTimeout(() => window.location.reload(), 1200);
            } else {
                alertBox.style.display = 'block';
                alertBox.style.background = '#FEE2E2';
                alertBox.style.color = '#991B1B';
                alertBox.innerHTML = `<strong>ব্যর্থ হয়েছে:</strong> ${data.message}`;
            }
        })
        .catch(err => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> কুরিয়ারে অর্ডার পাঠান';
            alertBox.style.display = 'block';
            alertBox.style.background = '#FEE2E2';
            alertBox.style.color = '#991B1B';
            alertBox.innerHTML = `<strong>Error:</strong> ${err.message}`;
        });
    }

    function checkCourierFraudShow(phone) {
        const box = document.getElementById('orderDetailFraudBox');
        box.innerHTML = `<span style="font-size: 11px; color: #2563EB;"><i class="fa-solid fa-spinner fa-spin"></i> Checking BD Courier...</span>`;

        fetch('{{ route("admin.orders.check_phone_fraud") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ phone: phone })
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                let badgeColor = res.success_rate >= 80 ? '#DCFCE7' : (res.success_rate >= 60 ? '#FEF3C7' : '#FEE2E2');
                let textColor = res.success_rate >= 80 ? '#166534' : (res.success_rate >= 60 ? '#92400E' : '#991B1B');
                
                let breakdownHtml = '';
                if (res.breakdown && res.breakdown.length > 0) {
                    breakdownHtml = '<div style="margin-top: 6px; display: flex; flex-direction: column; gap: 4px;">';
                    res.breakdown.forEach(item => {
                        breakdownHtml += `
                            <div style="display: flex; justify-content: space-between; font-size: 10px; background: rgba(255,255,255,0.7); padding: 2px 6px; border-radius: 4px;">
                                <span>${item.courier}:</span>
                                <span><strong>${item.success_parcel}</strong> / ${item.total_parcel} (${item.success_ratio}%)</span>
                            </div>
                        `;
                    });
                    breakdownHtml += '</div>';
                }

                box.innerHTML = `
                    <button type="button" onclick='showFraudModalDetail(${JSON.stringify(res)})' style="all: unset; cursor: pointer; display: block; width: 100%;">
                        <div style="background: ${badgeColor}; color: ${textColor}; padding: 6px 10px; border-radius: 6px; font-size: 11.5px; font-weight: 700; width: 100%; margin-top: 6px; box-sizing: border-box; text-align: left;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span><i class="fa-solid fa-shield-halved"></i> ${res.success_rate}% Success (${res.success_parcels}/${res.total_parcels})</span>
                                <span style="font-size: 10px; text-decoration: underline;">বিস্তারিত দেখুন</span>
                            </div>
                            <div style="font-size: 10.5px; font-weight: normal; margin-top: 3px; opacity: 0.9;">
                                ${res.risk_level} • মোট: ${res.total_parcels} টি | সফল: ${res.success_parcels}
                            </div>
                        </div>
                    </button>
                `;
                // Also open modal directly on click
                showFraudModalDetail(res);
            } else {
                box.innerHTML = `
                    <div style="background: #FEE2E2; color: #DC2626; padding: 6px 10px; border-radius: 6px; font-size: 11px; margin-top: 6px;">
                        <i class="fa-solid fa-triangle-exclamation"></i> ${res.message}
                        <div style="margin-top: 4px;">
                            <a href="{{ route('admin.couriers.index') }}" style="color: #2563EB; font-weight: 700; text-decoration: underline;">সেটিংস এ API Key দিন</a>
                        </div>
                    </div>
                `;
            }
        })
        .catch(err => {
            box.innerHTML = `<span style="font-size: 11px; color: #DC2626;">Error checking API</span>`;
        });
    }

    function showFraudModalDetail(data) {
        let breakdownHtml = '';
        if (data.breakdown && data.breakdown.length > 0) {
            breakdownHtml = `
                <div style="margin-top: 14px; border-top: 1px solid #E2E8F0; padding-top: 10px;">
                    <div style="font-size: 12px; font-weight: 800; color: #1E293B; margin-bottom: 8px;">কুরিয়ার ভিত্তিক ডেলিভারি হিসাব:</div>
                    <div style="display: flex; flex-direction: column; gap: 6px;">
            `;
            data.breakdown.forEach(item => {
                let isOrdered = item.total_parcel > 0;
                let rowBg = isOrdered ? '#F0FDF4' : '#FFFFFF';
                let rowBorder = isOrdered ? '#BBF7D0' : '#E2E8F0';
                let courierColor = isOrdered ? '#15803D' : '#334155';
                let ratioBadge = isOrdered ? (item.success_ratio >= 80 ? '#DCFCE7' : '#FEE2E2') : '#F1F5F9';
                let ratioTextColor = isOrdered ? (item.success_ratio >= 80 ? '#15803D' : '#991B1B') : '#64748B';

                breakdownHtml += `
                    <div style="display: flex; justify-content: space-between; align-items: center; background: ${rowBg}; border: 1px solid ${rowBorder}; border-radius: 6px; padding: 6px 10px; font-size: 11.5px;">
                        <span style="font-weight: 700; color: ${courierColor};">${item.courier}</span>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <span style="color: #64748B;">মোট: <strong>${item.total_parcel}</strong></span>
                            <span style="color: #16A34A; font-weight: 700;">সফল: ${item.success_parcel}</span>
                            <span style="color: #DC2626; font-weight: 700;">রিটার্ন: ${item.cancelled_parcel}</span>
                            <span style="background: ${ratioBadge}; color: ${ratioTextColor}; padding: 2px 6px; border-radius: 4px; font-weight: 800;">${item.success_ratio}%</span>
                        </div>
                    </div>
                `;
            });
            breakdownHtml += `</div></div>`;
        }

        let verdictHtml = '';
        if (data.risk_verdict) {
            verdictHtml = `
                <div style="background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 6px; padding: 8px 12px; font-size: 11.5px; color: #1E40AF; margin-bottom: 12px; text-align: left;">
                    <i class="fa-solid fa-circle-info"></i> ${data.risk_verdict}
                </div>
            `;
        }

        document.getElementById('fraudModalBody').innerHTML = `
            <div style="text-align: center; margin-bottom: 12px;">
                <div style="font-size: 32px; font-weight: 900; color: ${data.success_rate >= 80 ? '#16A34A' : (data.success_rate >= 60 ? '#D97706' : '#DC2626')}; line-height: 1.1;">
                    ${data.success_rate}%
                </div>
                <div style="font-size: 12px; font-weight: 700; color: #475569; margin-top: 4px;">সার্বিক ডেলিভারি সাকসেস রেট</div>
                <span class="badge" style="margin-top: 6px; background: ${data.success_rate >= 80 ? '#DCFCE7' : '#FEE2E2'}; color: ${data.success_rate >= 80 ? '#15803D' : '#991B1B'}; font-size: 11px;">
                    ${data.risk_level}
                </span>
            </div>

            ${verdictHtml}

            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px; padding: 12px; font-size: 12.5px; line-height: 1.7; margin-bottom: 10px;">
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #64748B;">মোবাইল নম্বর:</span>
                    <strong>${data.phone}</strong>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #64748B;">মোট পার্সেল (সকল কুরিয়ার মিলিয়ে):</span>
                    <strong>${data.total_parcels} টি</strong>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #16A34A;">সফল ডেলিভারি:</span>
                    <strong style="color: #16A34A;">${data.success_parcels} টি</strong>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #DC2626;">ক্যান্সেল / রিটার্ন:</span>
                    <strong style="color: #DC2626;">${data.cancelled_parcels} টি</strong>
                </div>
            </div>

            ${breakdownHtml}

            <div style="font-size: 11px; color: #94A3B8; text-align: center; margin-top: 10px;">
                ডাটা সোর্স: <strong>${data.provider}</strong>
            </div>
        `;
        document.getElementById('fraudModal').style.display = 'flex';
    }

    function closeFraudModal() {
        document.getElementById('fraudModal').style.display = 'none';
    }
        });
    }
</script>
@endpush
