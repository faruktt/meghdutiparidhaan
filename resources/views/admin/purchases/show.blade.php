@extends('layouts.admin')

@section('title', 'Purchase #' . $purchase->purchase_number)

@section('content')
<div class="card-header-flex">
    <div>
        <a href="{{ route('admin.purchases.index') }}" style="font-size: 13px; color: var(--text-muted); text-decoration: none;">
            <i class="fa-solid fa-arrow-left"></i> Back to Purchases
        </a>
        <h1 style="font-size: 22px; font-weight: 800; color: var(--text-dark); margin-top: 4px;">
            Purchase Invoice #{{ $purchase->purchase_number }}
        </h1>
    </div>
</div>

<div class="card" style="padding: 0; overflow: hidden; margin-bottom: 24px;">
    <div style="padding: 20px; border-bottom: 1px solid var(--admin-border); background: #F8FAFC; display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; font-size: 13.5px;">
        <div>
            <span style="color: var(--text-muted); font-size: 11px; text-transform: uppercase; font-weight: 700; display: block;">Supplier</span>
            <strong>{{ $purchase->supplier?->name ?? 'Direct Purchase' }}</strong>
        </div>
        <div>
            <span style="color: var(--text-muted); font-size: 11px; text-transform: uppercase; font-weight: 700; display: block;">Date</span>
            <strong>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M, Y') }}</strong>
        </div>
        <div>
            <span style="color: var(--text-muted); font-size: 11px; text-transform: uppercase; font-weight: 700; display: block;">Total Cost</span>
            <strong style="color: var(--admin-primary); font-size: 15px;">৳{{ number_format($purchase->total_amount) }}</strong>
        </div>
        <div>
            <span style="color: var(--text-muted); font-size: 11px; text-transform: uppercase; font-weight: 700; display: block;">Status</span>
            <span style="display: inline-block; padding: 2px 8px; border-radius: 99px; font-size: 11px; font-weight: 700; background: #D1FAE5; color: #065F46; text-transform: uppercase;">
                {{ $purchase->payment_status }}
            </span>
        </div>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Product Description</th>
                <th>Unit Cost Price</th>
                <th>Quantity Received</th>
                <th style="text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchase->items as $item)
                <tr>
                    <td>
                        <strong>{{ $item->product_name }}</strong>
                        @if($item->variant_info)
                            <div style="font-size: 12px; color: var(--text-muted);">{{ $item->variant_info }}</div>
                        @endif
                    </td>
                    <td>৳{{ number_format($item->cost_price) }}</td>
                    <td><strong style="color: #10B981;">+{{ $item->quantity }} pcs</strong></td>
                    <td style="text-align: right; font-weight: 700;">৳{{ number_format($item->subtotal) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
