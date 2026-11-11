@extends('layouts.admin')

@section('title', 'Orders')
@section('breadcrumb', 'Orders')

@section('content')
<!-- Top Stat Cards (Sohoj Theme) -->
<div class="stat-cards-grid">
    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-orange">
            <i class="fa-solid fa-cart-shopping"></i>
        </div>
        <div>
            <div class="stat-card-sub">Today's Orders</div>
            <div class="stat-card-val">{{ \App\Models\Order::whereDate('created_at', today())->count() }}</div>
        </div>
    </div>

    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-green">
            <i class="fa-solid fa-bangladeshi-taka-sign"></i>
        </div>
        <div>
            <div class="stat-card-sub">Total Revenue</div>
            <div class="stat-card-val">৳{{ number_format(\App\Models\Order::where('status', '!=', 'cancelled')->sum('total'), 2) }}</div>
        </div>
    </div>

    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-purple">
            <i class="fa-solid fa-clock"></i>
        </div>
        <div>
            <div class="stat-card-sub">Pending Orders</div>
            <div class="stat-card-val">{{ \App\Models\Order::where('status', 'pending')->count() }}</div>
        </div>
    </div>
</div>

<!-- Search & Filter Bar -->
<div class="filter-card-bar">
    <form method="GET" action="{{ route('admin.orders.index') }}" class="filter-left-group">
        <div class="search-input-wrap">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" name="search" value="{{ request('search') }}" class="filter-search-input" placeholder="অর্ডার #, কাস্টমার নাম বা ফোন দিয়ে খুঁজুন...">
        </div>

        <select name="status" class="filter-select">
            <option value="">সব স্ট্যাটাস</option>
            @foreach($statuses as $key => $status)
                <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ is_array($status) ? $status['label'] : $status }}</option>
            @endforeach
        </select>

        <select name="payment" class="filter-select">
            <option value="">সব পেমেন্ট</option>
            <option value="cod" {{ request('payment') === 'cod' ? 'selected' : '' }}>COD</option>
            <option value="bkash" {{ request('payment') === 'bkash' ? 'selected' : '' }}>bKash</option>
            <option value="nagad" {{ request('payment') === 'nagad' ? 'selected' : '' }}>Nagad</option>
        </select>

        <button type="submit" class="btn-orange-search">
            <i class="fa-solid fa-magnifying-glass"></i> খুঁজুন
        </button>

        @if(request()->anyFilled(['search', 'status', 'payment']))
            <a href="{{ route('admin.orders.index') }}" class="btn-admin-secondary" style="padding: 6px 12px; font-size: 11.5px; border-radius: 6px;">রিসেট</a>
        @endif
    </form>

    <div style="display: flex; gap: 8px;">
        <a href="{{ route('admin.order-statuses.index') }}" class="btn-admin-secondary" style="font-size: 12px; padding: 6px 12px; height: 34px;">
            <i class="fa-solid fa-sliders"></i> স্ট্যাটাস ম্যানেজ
        </a>
        <a href="{{ route('admin.pos.index') }}" class="btn-orange-add">
            <i class="fa-solid fa-plus"></i> নতুন অর্ডার
        </a>
    </div>
</div>

<!-- Orders Table (Row Color changes by Status) -->
<div class="table-card-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th style="width: 45px; text-align: center;">#</th>
                <th>সময়</th>
                <th>অর্ডার #</th>
                <th>অর্ডার চ্যানেল</th>
                <th>কাস্টমার ও ফ্রড চেক</th>
                <th style="text-align: center;">আইটেম</th>
                <th>মোট</th>
                <th>পেমেন্ট</th>
                <th>কুরিয়ার বুকিং</th>
                <th>অর্ডার স্ট্যাটাস</th>
                <th style="text-align: right; width: 110px;">অ্যাকশন</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $index => $order)
                <tr style="background-color: {{ $order->status_row_bg }}; border-left: 4px solid {{ $order->status_badge_color }};">
                    <td style="text-align: center; color: #9CA3AF; font-weight: 600;">
                        {{ $orders->firstItem() + $index }}
                    </td>
                    <td style="white-space: nowrap; color: #64748B;">
                        {{ $order->created_at->format('d M, h:i A') }}
                    </td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="invoice-orange">
                            {{ $order->order_number }}
                        </a>
                        <span style="font-size: 10px; color: #9CA3AF; text-transform: uppercase; margin-left: 3px;">({{ $order->source ?? 'web' }})</span>
                    </td>
                    <td>
                        @if($order->source === 'pos')
                            <span class="badge" style="background: #FFF7ED; color: #EA580C; font-weight: 700; font-size: 10.5px; padding: 2px 7px; border-radius: 4px;">
                                <i class="fa-solid fa-cash-register"></i> POS Order
                            </span>
                        @else
                            <span class="badge" style="background: #F1F5F9; color: #475569; font-weight: 600; font-size: 10.5px; padding: 2px 7px; border-radius: 4px;">
                                <i class="fa-solid fa-globe"></i> Website
                            </span>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight: 700; color: #111827;">{{ $order->customer_name }}</div>
                        <div style="font-size: 11px; color: #6B7280;">{{ $order->customer_phone }}</div>
                        <div style="margin-top: 4px;" id="fraud-box-{{ $order->id }}">
                            <button type="button" class="btn-fraud-pill" onclick="checkCourierFraud('{{ $order->customer_phone }}', 'fraud-box-{{ $order->id }}')">
                                <i class="fa-solid fa-shield-halved"></i> Fraud Check
                            </button>
                        </div>
                    </td>
                    <td style="text-align: center;">
                        <span style="background: #F3F4F6; color: #4B5563; padding: 2px 7px; border-radius: 99px; font-size: 11px; font-weight: 600;">
                            {{ $order->items->count() }} item{{ $order->items->count() > 1 ? 's' : '' }}
                        </span>
                    </td>
                    <td>
                        <span class="tbl-price">৳{{ number_format($order->total, 2) }}</span>
                    </td>
                    <td>
                        <span class="badge-status {{ $order->payment_status === 'paid' ? 'badge-success' : 'badge-warning' }}" style="border-radius: 4px; padding: 2px 7px;">
                            {{ $order->payment_status === 'paid' ? 'পরিশোধিত' : 'বাকি' }}
                        </span>
                        <div style="font-size: 10px; color: #6B7280; font-weight: 600; text-transform: uppercase; margin-top: 2px;">
                            {{ $order->payment_method }}
                        </div>
                    </td>
                    <td>
                        @if($order->consignment_id)
                            <div style="font-size: 11px; font-weight: 800; color: #1D4ED8; background: #EFF6FF; border: 1px solid #BFDBFE; padding: 2px 7px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;" title="Courier Status: {{ $order->courier_status ?? 'in_review' }}">
                                <i class="fa-solid fa-truck"></i> {{ $order->courier_name_formatted }}
                            </div>
                            <div style="font-size: 10px; font-family: monospace; color: #475569; margin-top: 2px;" title="Consignment / Tracking ID">
                                {{ $order->consignment_id }}
                            </div>
                        @else
                            @php
                                $actCourier = \App\Services\CourierService::getActiveCourier();
                            @endphp
                            <button type="button" class="btn-courier-pill btn-courier-{{ $actCourier }}" onclick="openCourierModal({{ $order->id }}, '{{ addslashes($order->order_number) }}', '{{ addslashes($order->customer_name) }}', '{{ addslashes($order->customer_phone) }}', '{{ addslashes($order->customer_address) }}', {{ (float)$order->total }}, '{{ $actCourier }}')">
                                <i class="fa-solid fa-truck-fast"></i> {{ ucfirst($actCourier) }}
                            </button>
                        @endif
                    </td>
                    <td>
                        <span class="badge-status" style="background: {{ $order->status_badge_bg }}; color: {{ $order->status_badge_color }}; font-weight: 700; cursor: pointer;" onclick="openQuickStatusModal({{ $order->id }}, '{{ $order->order_number }}', '{{ $order->status }}')" title="Click to change status">
                            {{ $order->status_label }} <i class="fa-solid fa-chevron-down" style="font-size: 8px; margin-left: 2px;"></i>
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <div class="action-btns-wrap">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn-tbl-action btn-tbl-view" title="View Order">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn-tbl-action btn-tbl-edit" title="Process / Edit">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" class="btn-tbl-action btn-tbl-invoice" title="Invoice">
                                <i class="fa-solid fa-print"></i>
                            </a>
                            <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই অর্ডারটি (#{{ $order->order_number }}) ডিলিট করতে চান?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-tbl-action btn-tbl-delete" title="Delete Order">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" style="text-align: center; color: var(--text-muted); padding: 36px;">
                        কোন অর্ডার পাওয়া যায়নি।
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($orders->hasPages())
        <div style="padding: 12px 18px; border-top: 1px solid var(--admin-border); background: #FFFFFF;">
            {{ $orders->links() }}
        </div>
    @endif
</div>

<!-- Quick Status Update Modal -->
<div id="quickStatusModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 200; align-items: center; justify-content: center; backdrop-filter: blur(2px);">
    <div style="background: white; border-radius: 8px; width: 100%; max-width: 400px; padding: 22px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
            <h3 style="font-size: 15px; font-weight: 800; margin: 0; color: #111827;">Update Order Status</h3>
            <button type="button" onclick="closeQuickStatusModal()" style="background: none; border: none; font-size: 16px; cursor: pointer; color: #64748B;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="quickStatusForm" method="POST">
            @csrf
            @method('PATCH')
            <div style="font-size: 12px; color: #64748B; margin-bottom: 12px;" id="quickOrderTitle">
                Order: <strong>#ORD</strong>
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 12px; font-weight: 700; color: #374151; margin-bottom: 6px;">Select New Status</label>
                <select name="status" id="quickStatusSelect" class="form-control" style="font-weight: 700;">
                    @foreach($statuses as $key => $status)
                        <option value="{{ $key }}">{{ is_array($status) ? $status['label'] : $status }}</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 12px; font-weight: 700; color: #374151; margin-bottom: 6px;">Note (Optional)</label>
                <textarea name="admin_note" rows="2" class="form-control" placeholder="Add tracking code or internal note..."></textarea>
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" onclick="closeQuickStatusModal()" class="btn-admin-secondary">Cancel</button>
                <button type="submit" class="btn-orange-search">Save Status</button>
            </div>
        </form>
    </div>
</div>

<!-- Send to Courier Dispatch Modal (With Edit option for Address, Name, Phone) -->
<div id="sendCourierModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 250; align-items: center; justify-content: center; backdrop-filter: blur(2px);">
    <div style="background: white; border-radius: 8px; width: 100%; max-width: 520px; padding: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.25);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; border-bottom: 1px solid #E2E8F0; padding-bottom: 10px;">
            <div>
                <h3 style="font-size: 16px; font-weight: 800; margin: 0; color: #0F172A; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-truck-fast" style="color: #2563EB;"></i> কুরিয়ারে বুকিং / অর্ডার ক্রিয়েট
                </h3>
                <span id="courierOrderSubtitle" style="font-size: 12px; color: #64748B;">Order: #ORD</span>
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

@push('styles')
<style>
    .btn-fraud-pill {
        background: #EFF6FF;
        color: #2563EB;
        border: 1px solid #BFDBFE;
        font-size: 10.5px;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 4px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.2s;
    }
    .btn-fraud-pill:hover {
        background: #DBEAFE;
        color: #1D4ED8;
    }
    .btn-courier-pill {
        border: none;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 4px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.2s;
        color: #FFFFFF;
    }
    .btn-courier-steadfast {
        background: #2563EB;
    }
    .btn-courier-steadfast:hover {
        background: #1D4ED8;
    }
    .btn-courier-pathao {
        background: #DC2626;
    }
    .btn-courier-pathao:hover {
        background: #B91C1C;
    }
    .btn-courier-redx {
        background: #EA580C;
    }
    .btn-courier-redx:hover {
        background: #C2410C;
    }
</style>
@endpush

@push('scripts')
<script>
    let activeCourierOrderId = null;

    function openQuickStatusModal(orderId, orderNumber, currentStatus) {
        document.getElementById('quickStatusForm').action = `/admin/orders/${orderId}/status`;
        document.getElementById('quickOrderTitle').innerHTML = `Order: <strong style="color: var(--admin-primary);">${orderNumber}</strong>`;
        document.getElementById('quickStatusSelect').value = currentStatus;
        document.getElementById('quickStatusModal').style.display = 'flex';
    }

    function closeQuickStatusModal() {
        document.getElementById('quickStatusModal').style.display = 'none';
    }

    // Courier Dispatch Modal
    function openCourierModal(orderId, orderNumber, name, phone, address, total, courier) {
        activeCourierOrderId = orderId;
        document.getElementById('courierOrderSubtitle').innerHTML = `Order: <strong style="color: #2563EB;">#${orderNumber}</strong>`;
        document.getElementById('modalCustomerName').value = name;
        document.getElementById('modalCustomerPhone').value = phone;
        document.getElementById('modalCustomerAddress').value = address;
        document.getElementById('modalCodAmount').value = total;
        document.getElementById('modalCourierSelect').value = courier || 'steadfast';
        
        const alertBox = document.getElementById('courierModalAlert');
        alertBox.style.display = 'none';
        
        document.getElementById('sendCourierModal').style.display = 'flex';
    }

    function closeCourierModal() {
        document.getElementById('sendCourierModal').style.display = 'none';
    }

    function submitCourierDispatch(e) {
        e.preventDefault();
        if (!activeCourierOrderId) return;

        const form = document.getElementById('courierDispatchForm');
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

    // Fraud Checker (BD Courier API: app.courier.com.bd)
    function checkCourierFraud(phone, containerId) {
        const container = document.getElementById(containerId);
        if (!container) return;

        container.innerHTML = `<span style="font-size: 10px; color: #2563EB;"><i class="fa-solid fa-spinner fa-spin"></i> Checking BD Courier...</span>`;

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
                let badgeColor = '#DCFCE7';
                let textColor = '#166534';
                let icon = 'fa-circle-check';

                if (res.success_rate < 60) {
                    badgeColor = '#FEE2E2';
                    textColor = '#991B1B';
                    icon = 'fa-triangle-exclamation';
                } else if (res.success_rate < 80) {
                    badgeColor = '#FEF3C7';
                    textColor = '#92400E';
                    icon = 'fa-circle-exclamation';
                }

                container.innerHTML = `
                    <button type="button" class="btn-fraud-pill" style="background: ${badgeColor}; color: ${textColor}; border-color: ${badgeColor};" onclick='showFraudModalDetail(${JSON.stringify(res)})'>
                        <i class="fa-solid ${icon}"></i> ${res.success_rate}% Success (${res.success_parcels}/${res.total_parcels})
                    </button>
                `;
            } else {
                container.innerHTML = `
                    <button type="button" class="btn-fraud-pill" style="background: #FEE2E2; color: #DC2626; border-color: #FECACA;" onclick='showFraudErrorModal(${JSON.stringify(res.message || "Failed")})'>
                        <i class="fa-solid fa-triangle-exclamation"></i> API Key লাগবে
                    </button>
                `;
            }
        })
        .catch(err => {
            container.innerHTML = `<span style="font-size: 10px; color: #DC2626;"><i class="fa-solid fa-circle-exclamation"></i> Error</span>`;
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

    function showFraudErrorModal(message) {
        document.getElementById('fraudModalBody').innerHTML = `
            <div style="text-align: center; padding: 10px 0;">
                <div style="font-size: 36px; color: #EA580C; margin-bottom: 10px;">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <h4 style="font-size: 15px; font-weight: 800; color: #1E293B; margin-bottom: 8px;">BD Courier API Key প্রয়োজন</h4>
                <p style="font-size: 12.5px; color: #475569; line-height: 1.5; margin-bottom: 16px;">
                    ${message}
                </p>
                <div style="display: flex; gap: 8px; justify-content: center;">
                    <a href="{{ route('admin.couriers.index') }}" class="btn-orange-search" style="font-size: 12px; padding: 7px 14px; text-decoration: none;">
                        <i class="fa-solid fa-gear"></i> কুরিয়ার সেটিংস পেজে যান
                    </a>
                    <a href="https://app.courier.com.bd/api" target="_blank" rel="noopener noreferrer" class="btn-admin-secondary" style="font-size: 12px; padding: 7px 14px; text-decoration: none;">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> app.courier.com.bd
                    </a>
                </div>
            </div>
        `;
        document.getElementById('fraudModal').style.display = 'flex';
    }

    function closeFraudModal() {
        document.getElementById('fraudModal').style.display = 'none';
    }
</script>
@endpush
