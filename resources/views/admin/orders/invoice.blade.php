<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->order_number }} | {{ \App\Models\Setting::get('site_name', 'Meghduti Paridhaan') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', 'Hind Siliguri', sans-serif;
            background: #F1F5F9;
            color: #0F172A;
            padding: 30px 15px;
            font-size: 13.5px;
            line-height: 1.5;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .no-print-bar {
            max-width: 820px;
            margin: 0 auto 16px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }
        .btn-print {
            background: #EA580C;
            color: #FFFFFF;
            box-shadow: 0 2px 8px rgba(234, 88, 12, 0.3);
        }
        .btn-print:hover { background: #C2410C; }
        .btn-back {
            background: #FFFFFF;
            color: #475569;
            border: 1px solid #CBD5E1;
        }
        .btn-back:hover { background: #F8FAFC; color: #0F172A; }

        .invoice-card {
            background: #FFFFFF;
            max-width: 820px;
            margin: 0 auto;
            padding: 40px;
            border-radius: 14px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.06);
            border: 1px solid #E2E8F0;
        }

        .inv-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #F1F5F9;
            padding-bottom: 24px;
        }
        .inv-brand img {
            max-height: 44px;
            object-fit: contain;
            margin-bottom: 8px;
        }
        .inv-brand-text {
            font-size: 26px;
            font-weight: 900;
            letter-spacing: -0.5px;
            color: #0F172A;
            line-height: 1;
        }
        .inv-brand-text span {
            color: #EA580C;
        }
        .inv-meta {
            text-align: right;
        }
        .inv-title {
            font-size: 24px;
            font-weight: 900;
            color: #EA580C;
            letter-spacing: 1px;
            line-height: 1;
        }
        .inv-num {
            font-size: 14px;
            font-weight: 800;
            color: #0F172A;
            margin-top: 6px;
        }

        .inv-parties {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-top: 24px;
            padding-bottom: 20px;
            border-bottom: 1px solid #F1F5F9;
        }
        .party-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #94A3B8;
            margin-bottom: 4px;
        }
        .party-name {
            font-size: 15px;
            font-weight: 800;
            color: #0F172A;
        }
        .party-info {
            color: #475569;
            font-size: 12.5px;
            margin-top: 4px;
            line-height: 1.4;
        }

        .badge-status {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .inv-table {
            width: 100%;
            border-collapse: collapse;
            margin: 24px 0 20px 0;
        }
        .inv-table th {
            background: #F8FAFC;
            color: #475569;
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 10px 12px;
            border-bottom: 1.5px solid #E2E8F0;
            text-align: left;
        }
        .inv-table td {
            padding: 12px;
            border-bottom: 1px solid #F1F5F9;
            color: #1E293B;
            font-size: 13px;
        }
        .inv-table td.num {
            text-align: right;
            font-variant-numeric: tabular-nums;
        }

        .inv-totals-wrap {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 10px;
            padding-top: 10px;
        }
        .inv-note-box {
            max-width: 420px;
            background: #F8FAFC;
            border-radius: 8px;
            padding: 12px 14px;
            border: 1px solid #E2E8F0;
            font-size: 12px;
            color: #64748B;
        }
        .inv-totals-box {
            width: 290px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            font-size: 13px;
            color: #475569;
        }
        .total-row.grand {
            border-top: 2px dashed #CBD5E1;
            margin-top: 8px;
            padding-top: 10px;
            font-size: 17px;
            font-weight: 800;
            color: #EA580C;
        }

        .inv-sign {
            margin-top: 48px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            padding-top: 20px;
        }
        .sign-box {
            text-align: center;
            width: 170px;
            border-top: 1px dashed #94A3B8;
            padding-top: 6px;
            font-size: 11.5px;
            font-weight: 700;
            color: #64748B;
        }

        .inv-footer {
            margin-top: 36px;
            padding-top: 16px;
            border-top: 1px solid #F1F5F9;
            text-align: center;
            font-size: 11.5px;
            color: #94A3B8;
        }

        @media print {
            body { background: #FFFFFF; padding: 0; }
            .no-print-bar { display: none !important; }
            .invoice-card {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                max-width: 100% !important;
            }
            @page {
                size: A4;
                margin: 12mm 15mm;
            }
        }
    </style>
</head>
<body>
    @php
        $siteLogo = \App\Models\Setting::get('site_logo');
        $siteName = \App\Models\Setting::get('site_name', 'Meghduti Paridhaan');
        $sitePhone = \App\Models\Setting::get('phone', \App\Models\Setting::get('site_phone', ''));
        $siteEmail = \App\Models\Setting::get('email', \App\Models\Setting::get('site_email', ''));
        $siteAddress = \App\Models\Setting::get('address', \App\Models\Setting::get('site_address', 'Dhaka, Bangladesh'));
    @endphp

    <!-- Print Navigation Bar -->
    <div class="no-print-bar">
        <a href="javascript:window.history.back()" class="btn-action btn-back">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
        <button onclick="window.print()" class="btn-action btn-print">
            <i class="fa-solid fa-print"></i> Print Invoice
        </button>
    </div>

    <div class="invoice-card">
        <!-- Invoice Header -->
        <div class="inv-header">
            <div class="inv-brand">
                @if($siteLogo && file_exists(public_path($siteLogo)))
                    <img src="{{ asset($siteLogo) }}" alt="{{ $siteName }}">
                @else
                    <div class="inv-brand-text">{{ substr($siteName, 0, 2) }}<span>{{ substr($siteName, 2) }}</span></div>
                @endif
                <div style="font-size: 11px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: #64748B; margin-top: 3px;">
                    Own The Fashion Wave
                </div>
                <div style="font-size: 12px; color: #64748B; margin-top: 6px; line-height: 1.4;">
                    {{ $siteAddress }}<br>
                    Hotline: {{ $sitePhone }} | Email: {{ $siteEmail }}
                </div>
            </div>

            <div class="inv-meta">
                <div class="inv-title">INVOICE</div>
                <div class="inv-num">#{{ $order->order_number }}</div>
                <div style="font-size: 12.5px; color: #64748B; margin-top: 3px;">
                    Date: {{ $order->created_at ? $order->created_at->format('d M, Y') : now()->format('d M, Y') }}
                </div>
                <div style="margin-top: 6px;">
                    <span class="badge-status" style="background: {{ $order->status_badge_color ?? '#f59e0b' }}20; color: {{ $order->status_badge_color ?? '#f59e0b' }};">
                        {{ $order->status_label }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Bill To / Delivery Info -->
        <div class="inv-parties">
            <div>
                <div class="party-label">Bill / Ship To:</div>
                <div class="party-name">{{ $order->customer_name }}</div>
                <div class="party-info">
                    <strong>Phone:</strong> {{ $order->customer_phone }}<br>
                    <strong>Address:</strong> {{ $order->customer_address }}<br>
                    <strong>Zone:</strong> {{ $order->shippingZone?->name ?? 'Standard Delivery' }}
                </div>
            </div>

            <div style="text-align: right;">
                <div class="party-label">Payment Information:</div>
                <div style="font-size: 14px; font-weight: 700; text-transform: uppercase; color: #0F172A;">
                    {{ $order->payment_method }}
                </div>
                <div style="margin-top: 4px;">
                    <span class="badge-status" style="background: {{ $order->payment_status === 'paid' ? '#16A34A20' : '#D9770620' }}; color: {{ $order->payment_status === 'paid' ? '#16A34A' : '#D97706' }};">
                        Payment: {{ ucfirst($order->payment_status) }}
                    </span>
                </div>
                <div style="font-size: 12px; color: #64748B; margin-top: 6px;">
                    Source: <strong style="text-transform: uppercase;">{{ $order->source ?? 'Web Store' }}</strong>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <table class="inv-table">
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;">#</th>
                    <th>Item Description</th>
                    <th style="width: 140px;">Variant</th>
                    <th style="width: 100px; text-align: right;">Price</th>
                    <th style="width: 60px; text-align: center;">Qty</th>
                    <th style="width: 110px; text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($order->items as $index => $item)
                    <tr>
                        <td style="text-align: center; color: #94A3B8; font-weight: 600;">{{ $index + 1 }}</td>
                        <td>
                            <strong style="color: #0F172A;">{{ $item->product_name }}</strong>
                        </td>
                        <td style="color: #64748B; font-size: 12px;">
                            {{ $item->variant_info ?: 'Standard' }}
                        </td>
                        <td class="num">৳{{ number_format($item->price, 2) }}</td>
                        <td style="text-align: center; font-weight: 700;">{{ $item->quantity }}</td>
                        <td class="num" style="font-weight: 700; color: #0F172A;">৳{{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #94A3B8; padding: 24px;">No items found in this order.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Totals & Notes -->
        <div class="inv-totals-wrap">
            <div class="inv-note-box">
                <strong style="color: #0F172A; display: block; margin-bottom: 4px;">Important Note:</strong>
                Please check the products in front of the delivery agent. For any queries or returns, please keep this invoice receipt and contact our customer helpline within 7 days.
                @if($order->customer_note)
                    <div style="margin-top: 6px; border-top: 1px dashed #CBD5E1; padding-top: 4px; color: #334155;">
                        <strong>Customer Note:</strong> {{ $order->customer_note }}
                    </div>
                @endif
            </div>

            <div class="inv-totals-box">
                <div class="total-row">
                    <span>Subtotal:</span>
                    <strong style="color: #0F172A;">৳{{ number_format($order->subtotal, 2) }}</strong>
                </div>
                <div class="total-row">
                    <span>Delivery Charge:</span>
                    <strong style="color: #0F172A;">৳{{ number_format($order->shipping_charge, 2) }}</strong>
                </div>
                @if($order->discount_amount > 0)
                    <div class="total-row" style="color: #16A34A;">
                        <span>Discount:</span>
                        <strong>-৳{{ number_format($order->discount_amount, 2) }}</strong>
                    </div>
                @endif
                <div class="total-row grand">
                    <span>Grand Total:</span>
                    <span>৳{{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Signatures -->
        <div class="inv-sign">
            <div class="sign-box">Customer Signature</div>
            <div class="sign-box">Authorized Signature</div>
        </div>

        <!-- Footer -->
        <div class="inv-footer">
            Thank you for shopping with {{ $siteName }}! &bull; System generated invoice &bull; {{ date('Y') }}
        </div>
    </div>
</body>
</html>
