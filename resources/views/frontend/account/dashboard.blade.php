@extends('layouts.app')

@section('title', 'My Account | ' . \App\Models\Setting::get('site_name', 'Meghduti Paridhaan'))

@push('styles')
<style>
    .account-layout {
        display: grid;
        grid-template-columns: 260px 1fr;
        gap: 30px;
        margin: 40px auto 70px;
    }
    @media (max-width: 900px) {
        .account-layout { grid-template-columns: 1fr; }
    }
    .profile-sidebar {
        background: white;
        border-radius: 4px;
        border: 1px solid #E4E4E7;
        padding: 24px;
        height: fit-content;
    }
    .profile-avatar {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: #000000;
        color: #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: 800;
        margin: 0 auto 12px;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="account-layout">
        <!-- Sidebar -->
        <aside class="profile-sidebar">
            <div style="text-align: center; border-bottom: 1px solid #E4E4E7; padding-bottom: 20px; margin-bottom: 20px;">
                <div class="profile-avatar">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h3 style="font-size: 16px; font-weight: 800; text-transform: uppercase; color: #18181B;">{{ $user->name }}</h3>
                <div style="font-size: 12px; color: #71717A; margin-top: 2px;">{{ $user->phone }}</div>
            </div>

            <ul style="list-style: none; display: flex; flex-direction: column; gap: 8px;">
                <li>
                    <a href="{{ route('account.dashboard') }}" style="display: flex; align-items: center; gap: 10px; padding: 10px 14px; border-radius: 2px; font-weight: 700; font-size: 13px; color: #000000; background: #F4F4F5;">
                        <i class="fa-solid fa-bag-shopping"></i> My Orders
                    </a>
                </li>
                <li>
                    <a href="{{ route('wishlist.index') }}" style="display: flex; align-items: center; gap: 10px; padding: 10px 14px; border-radius: 2px; font-weight: 600; font-size: 13px; color: #374151; background: transparent; transition: background 0.2s;" onmouseover="this.style.background='#F4F4F5'" onmouseout="this.style.background='transparent'">
                        <i class="fa-regular fa-heart"></i> My Wishlist
                    </a>
                </li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 10px 14px; border-radius: 2px; font-weight: 600; font-size: 13px; color: #EF4444; background: none; border: none; cursor: pointer; text-align: left;">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i> Sign Out
                        </button>
                    </form>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main>
            <div style="background: white; border-radius: 4px; border: 1px solid #E4E4E7; padding: 26px; margin-bottom: 30px;">
                <h2 style="font-size: 16px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 20px; color: #18181B;">
                    ORDER HISTORY ({{ $orders->total() }})
                </h2>

                @if($orders->count() > 0)
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                            <thead>
                                <tr style="border-bottom: 1px solid #E4E4E7; text-align: left; color: #71717A; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">
                                    <th style="padding: 12px 8px;">Order No</th>
                                    <th style="padding: 12px 8px;">Date</th>
                                    <th style="padding: 12px 8px;">Items</th>
                                    <th style="padding: 12px 8px;">Total</th>
                                    <th style="padding: 12px 8px;">Status</th>
                                    <th style="padding: 12px 8px; text-align: right;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $ord)
                                    <tr style="border-bottom: 1px solid #F4F4F5;">
                                        <td style="padding: 14px 8px; font-weight: 800; color: #18181B;">
                                            {{ $ord->order_number }}
                                        </td>
                                        <td style="padding: 14px 8px; color: #71717A;">
                                            {{ $ord->created_at->format('M d, Y') }}
                                        </td>
                                        <td style="padding: 14px 8px;">
                                            {{ $ord->items->count() }} items
                                        </td>
                                        <td style="padding: 14px 8px; font-weight: 700; color: #B5876D;">
                                            ৳ {{ number_format($ord->total) }}
                                        </td>
                                        <td style="padding: 14px 8px;">
                                            <span style="display: inline-block; padding: 3px 8px; border-radius: 2px; font-size: 10px; font-weight: 800; background: #000000; color: white; text-transform: uppercase;">
                                                {{ $ord->status }}
                                            </span>
                                        </td>
                                        <td style="padding: 14px 8px; text-align: right;">
                                            <a href="{{ route('track.order', ['search' => $ord->order_number]) }}" class="btn-outline-pill" style="padding: 4px 12px; font-size: 11px; display: inline-block; width: auto;">
                                                Track
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div style="margin-top: 20px;">
                        {{ $orders->links() }}
                    </div>
                @else
                    <div style="text-align: center; padding: 50px 20px; color: #71717A;">
                        <i class="fa-solid fa-receipt" style="font-size: 36px; opacity: 0.3; margin-bottom: 10px;"></i>
                        <p style="font-size: 13px;">You have not placed any orders yet.</p>
                        <a href="{{ route('shop.index') }}" class="btn-black-pill" style="display: inline-block; width: auto; margin-top: 14px; padding: 10px 24px;">Start Shopping</a>
                    </div>
                @endif
            </div>

            <!-- Profile Info Form -->
            <div style="background: white; border-radius: 4px; border: 1px solid #E4E4E7; padding: 26px;">
                <h3 style="font-size: 16px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 20px; color: #18181B;">UPDATE PROFILE</h3>
                <form action="{{ route('account.profile.update') }}" method="POST">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 700; text-transform: uppercase; margin-bottom: 6px;">Your Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required style="width: 100%; padding: 10px 14px; border: 1px solid #D4D4D8; border-radius: 2px; font-size: 13px; outline: none;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 700; text-transform: uppercase; margin-bottom: 6px;">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" style="width: 100%; padding: 10px 14px; border: 1px solid #D4D4D8; border-radius: 2px; font-size: 13px; outline: none;">
                        </div>
                    </div>
                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 12px; font-weight: 700; text-transform: uppercase; margin-bottom: 6px;">Default Address</label>
                        <textarea name="address" rows="2" style="width: 100%; padding: 10px 14px; border: 1px solid #D4D4D8; border-radius: 2px; font-size: 13px; outline: none;">{{ old('address', $user->address) }}</textarea>
                    </div>
                    <button type="submit" class="btn-black-pill" style="display: inline-block; width: auto; padding: 10px 24px;">SAVE CHANGES</button>
                </form>
            </div>
        </main>
    </div>
</div>
@endsection
