@extends('layouts.admin')

@section('title', 'Customer: ' . $customer->name)

@section('content')
<div class="card-header-flex">
    <div>
        <a href="{{ route('admin.customers.index') }}" style="font-size: 13px; color: var(--text-muted); text-decoration: none;">
            <i class="fa-solid fa-arrow-left"></i> Back to Customers
        </a>
        <h1 style="font-size: 22px; font-weight: 800; color: var(--text-dark); margin-top: 4px;">
            Customer Profile: {{ $customer->name }}
        </h1>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px; @media(max-width: 900px){grid-template-columns: 1fr;}">
    <!-- Customer Info Card -->
    <div class="card">
        <h3 class="card-title" style="margin-bottom: 16px;">Contact Info</h3>
        <div style="font-size: 13.5px; display: flex; flex-direction: column; gap: 12px;">
            <div>
                <span style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: var(--text-muted); display: block;">Full Name</span>
                <strong>{{ $customer->name }}</strong>
            </div>
            <div>
                <span style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: var(--text-muted); display: block;">Phone Number</span>
                <a href="tel:{{ $customer->phone }}" style="color: var(--admin-primary); font-weight: 700; text-decoration: none;">
                    {{ $customer->phone }}
                </a>
            </div>
            <div>
                <span style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: var(--text-muted); display: block;">Email Address</span>
                <span>{{ $customer->email ?? 'Not provided' }}</span>
            </div>
            <div>
                <span style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: var(--text-muted); display: block;">Default Address</span>
                <p style="margin: 0;">{{ $customer->address ?? 'Not specified' }}</p>
            </div>
            <div>
                <span style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: var(--text-muted); display: block;">Registration Date</span>
                <span>{{ $customer->created_at->format('d M, Y h:i A') }}</span>
            </div>
        </div>
    </div>

    <!-- Lifetime Orders -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <div style="padding: 16px 20px; border-bottom: 1px solid var(--admin-border); background: #F8FAFC;">
            <h3 class="card-title">Order History ({{ $customer->orders->count() }})</h3>
        </div>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customer->orders as $ord)
                    <tr>
                        <td><strong style="color: var(--admin-primary);">{{ $ord->order_number }}</strong></td>
                        <td>{{ $ord->created_at->format('d M, Y') }}</td>
                        <td><strong>৳{{ number_format($ord->total) }}</strong></td>
                        <td>
                            <span style="display: inline-block; padding: 2px 8px; border-radius: 99px; font-size: 11px; font-weight: 800; background: {{ $ord->status_badge_color }}; color: white; text-transform: uppercase;">
                                {{ $ord->status_label }}
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <a href="{{ route('admin.orders.show', $ord->id) }}" class="btn-icon">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 30px;">
                            No orders placed yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
