@extends('layouts.admin')

@section('title', 'Draft / Incomplete Orders')
@section('breadcrumb', 'Draft Orders')

@section('content')
<!-- Top Stat Cards -->
<div class="stat-cards-grid">
    <div class="stat-card-compact">
        <div class="stat-icon-wrap" style="background: #FEF3C7; color: #D97706;">
            <i class="fa-solid fa-hourglass-half"></i>
        </div>
        <div>
            <div class="stat-card-sub">Pending Drafts (Unfinished)</div>
            <div class="stat-card-val" style="color: #D97706;">{{ $counts['pending'] }}</div>
        </div>
    </div>

    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-green">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div>
            <div class="stat-card-sub">Converted to Orders</div>
            <div class="stat-card-val">{{ $counts['converted'] }}</div>
        </div>
    </div>

    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-purple">
            <i class="fa-solid fa-clipboard-list"></i>
        </div>
        <div>
            <div class="stat-card-sub">Total Recorded Incomplete</div>
            <div class="stat-card-val">{{ $counts['total'] }}</div>
        </div>
    </div>
</div>

<!-- Search & Filter Bar -->
<div class="filter-card-bar">
    <form method="GET" action="{{ route('admin.draft_orders.index') }}" class="filter-left-group">
        <div class="search-input-wrap">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" name="search" value="{{ request('search') }}" class="filter-search-input" placeholder="কাস্টমার নাম, মোবাইল নম্বর বা ঠিকানা...">
        </div>

        <select name="status" class="filter-select">
            <option value="pending" {{ request('status', 'pending') === 'pending' ? 'selected' : '' }}>শুধু পেন্ডিং ড্রাফট</option>
            <option value="converted" {{ request('status') === 'converted' ? 'selected' : '' }}>কনভার্টেড অর্ডার</option>
            <option value="" {{ request()->has('status') && request('status') === '' ? 'selected' : '' }}>সকল ড্রাফট</option>
        </select>

        <button type="submit" class="btn-orange-search">
            <i class="fa-solid fa-magnifying-glass"></i> ফিল্টার
        </button>

        @if(request()->anyFilled(['search', 'status']))
            <a href="{{ route('admin.draft_orders.index') }}" class="btn-admin-secondary" style="padding: 6px 12px; font-size: 11.5px; border-radius: 6px;">রিসেট</a>
        @endif
    </form>
</div>

<!-- Draft Orders Table -->
<div class="table-card-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th style="width: 45px; text-align: center;">#</th>
                <th style="width: 130px;">সময়</th>
                <th>কাস্টমার তথ্য (নাম ও ফোন)</th>
                <th>ঠিকানা</th>
                <th>ব্যাগের পণ্যসমূহ</th>
                <th>মোট মূল্য</th>
                <th style="width: 110px;">ড্রাফট স্ট্যাটাস</th>
                <th style="text-align: right; width: 140px;">অ্যাকশন</th>
            </tr>
        </thead>
        <tbody>
            @forelse($draftOrders as $index => $draft)
                <tr>
                    <td style="text-align: center; color: #9CA3AF; font-weight: 600;">
                        {{ $draftOrders->firstItem() + $index }}
                    </td>
                    <td style="white-space: nowrap; color: #64748B; font-size: 11px;">
                        <div>{{ $draft->created_at->format('d M, h:i A') }}</div>
                        <div style="color: #9CA3AF; font-size: 10px;">{{ $draft->created_at->diffForHumans() }}</div>
                    </td>
                    <td>
                        <div style="font-weight: 700; color: #111827; font-size: 13px;">
                            {{ $draft->customer_name ?: 'নাম প্রদান করেনি' }}
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px; margin-top: 3px;">
                            <span style="font-size: 12px; color: #4B5563; font-weight: 600;">
                                {{ $draft->customer_phone ?: 'ফোন নম্বর নেই' }}
                            </span>
                            @if($draft->customer_phone)
                                <a href="tel:{{ $draft->customer_phone }}" class="btn-tbl-action" style="background: #EFF6FF; color: #2563EB;" title="কল করুন">
                                    <i class="fa-solid fa-phone" style="font-size: 10px;"></i>
                                </a>
                                @php
                                    $cleanPhone = preg_replace('/[^0-9]/', '', $draft->customer_phone);
                                    if(strlen($cleanPhone) === 11 && str_starts_with($cleanPhone, '01')) {
                                        $cleanPhone = '88' . $cleanPhone;
                                    }
                                @endphp
                                <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode('আসসালামু আলাইকুম ' . ($draft->customer_name ?: 'স্যার') . ', মেঘদূতী পরিধানে আপনার পছন্দের অর্ডারে কোনো সমস্যা হচ্ছে কি? আমরা কি সহায়তা করতে পারি?') }}" target="_blank" class="btn-tbl-action" style="background: #DCFCE7; color: #16A34A;" title="WhatsApp-এ যোগাযোগ করুন">
                                    <i class="fa-brands fa-whatsapp" style="font-size: 11px;"></i>
                                </a>
                            @endif
                        </div>
                    </td>
                    <td style="max-width: 180px; font-size: 11.5px; color: #4B5563;">
                        {{ $draft->customer_address ?: 'ঠিকানা লেখেনি' }}
                    </td>
                    <td>
                        <div style="display: flex; flex-direction: column; gap: 4px; max-width: 250px;">
                            @if(!empty($draft->cart_data) && is_array($draft->cart_data))
                                @foreach($draft->cart_data as $cItem)
                                    <div style="display: flex; align-items: center; gap: 6px; font-size: 11.5px;">
                                        @if(!empty($cItem['image']))
                                            <img src="{{ $cItem['image'] }}" style="width: 24px; height: 28px; object-fit: cover; border-radius: 3px; border: 1px solid #E5E7EB;">
                                        @endif
                                        <span style="font-weight: 600; color: #1F2937; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 160px;" title="{{ $cItem['name'] ?? '' }}">
                                            {{ $cItem['name'] ?? 'Product' }}
                                        </span>
                                        <span style="color: #6B7280; font-size: 11px;">×{{ $cItem['quantity'] ?? 1 }}</span>
                                    </div>
                                @endforeach
                            @else
                                <span style="color: #9CA3AF; font-size: 11px;">কার্ট খালি</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <span class="tbl-price" style="font-size: 13px;">৳{{ number_format($draft->total, 2) }}</span>
                    </td>
                    <td>
                        @if($draft->status === 'converted')
                            <span class="badge" style="background: #DCFCE7; color: #15803D; font-weight: 700; font-size: 10.5px; padding: 3px 8px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="fa-solid fa-circle-check"></i> কনভার্টেড
                            </span>
                            @if($draft->order_id)
                                <a href="{{ route('admin.orders.show', $draft->order_id) }}" style="display: block; font-size: 10px; color: #2563EB; font-weight: 600; margin-top: 2px;">
                                    অর্ডার দেখুন #{{ $draft->order_id }}
                                </a>
                            @endif
                        @else
                            <span class="badge" style="background: #FEF3C7; color: #B45309; font-weight: 700; font-size: 10.5px; padding: 3px 8px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="fa-solid fa-clock"></i> পেন্ডিং ড্রাফট
                            </span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        <div class="action-btns-wrap" style="justify-content: flex-end;">
                            @if($draft->status !== 'converted')
                                <form action="{{ route('admin.draft_orders.convert', $draft->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('এই ড্রাফটটিকে অফিশিয়াল অর্ডারে কনভার্ট করতে চান?');">
                                    @csrf
                                    <button type="submit" class="btn-tbl-action" style="background: #ECFDF5; color: #059669; width: auto; padding: 0 8px; font-weight: 700; font-size: 11px; gap: 4px;" title="Convert to Official Order">
                                        <i class="fa-solid fa-arrow-right-to-bracket"></i> অর্ডার বানান
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('admin.draft_orders.destroy', $draft->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('এই ড্রাফটটি মুছে ফেলতে চান?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-tbl-action btn-tbl-delete" title="Delete Draft">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 40px;">
                        <i class="fa-solid fa-clipboard-check" style="font-size: 32px; color: #CBD5E1; margin-bottom: 10px; display: block;"></i>
                        বর্তমানে কোনো পেন্ডিং ড্রাফট বা অসম্পূর্ণ অর্ডার নেই।
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($draftOrders->hasPages())
        <div style="padding: 12px 18px; border-top: 1px solid var(--admin-border); background: #FFFFFF;">
            {{ $draftOrders->links() }}
        </div>
    @endif
</div>
@endsection

