@extends('layouts.app')

@section('title', 'Create Customer Account | ' . \App\Models\Setting::get('site_name', 'Meghduti Paridhaan'))

@section('content')
<div class="container" style="max-width: 460px; margin: 50px auto 80px;">
    <div style="background: white; border-radius: 4px; border: 1px solid #E4E4E7; padding: 36px;">
        <div style="text-align: center; margin-bottom: 24px;">
            <div style="width: 50px; height: 50px; border-radius: 50%; background: #FAFAFA; border: 1px solid #E4E4E7; color: #18181B; display: inline-flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 12px;">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <h1 style="font-size: 20px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; color: #18181B;">CREATE ACCOUNT</h1>
            <p style="font-size: 13px; color: #71717A; margin-top: 4px;">Sign up for fast checkout and real-time parcel updates</p>
        </div>

        @if($errors->any())
            <div style="background: #FEF2F2; color: #DC2626; border: 1px solid #FEE2E2; padding: 12px 16px; border-radius: 2px; font-size: 13px; margin-bottom: 20px;">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('register.post') }}" method="POST">
            @csrf
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 12px; font-weight: 700; text-transform: uppercase; margin-bottom: 8px;">Full Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. John Doe" required style="width: 100%; padding: 12px 14px; border: 1px solid #D4D4D8; border-radius: 2px; font-size: 13px; outline: none;">
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 12px; font-weight: 700; text-transform: uppercase; margin-bottom: 8px;">Phone Number *</label>
                <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="e.g. 017xxxxxxxx" required style="width: 100%; padding: 12px 14px; border: 1px solid #D4D4D8; border-radius: 2px; font-size: 13px; outline: none;">
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 12px; font-weight: 700; text-transform: uppercase; margin-bottom: 8px;">Email Address (Optional)</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="e.g. name@domain.com" style="width: 100%; padding: 12px 14px; border: 1px solid #D4D4D8; border-radius: 2px; font-size: 13px; outline: none;">
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 12px; font-weight: 700; text-transform: uppercase; margin-bottom: 8px;">Password *</label>
                <input type="password" name="password" placeholder="Minimum 6 characters" required style="width: 100%; padding: 12px 14px; border: 1px solid #D4D4D8; border-radius: 2px; font-size: 13px; outline: none;">
            </div>

            <div style="margin-bottom: 24px;">
                <label style="display: block; font-size: 12px; font-weight: 700; text-transform: uppercase; margin-bottom: 8px;">Confirm Password *</label>
                <input type="password" name="password_confirmation" placeholder="Re-enter your password" required style="width: 100%; padding: 12px 14px; border: 1px solid #D4D4D8; border-radius: 2px; font-size: 13px; outline: none;">
            </div>

            <button type="submit" class="btn-black-pill" style="padding: 13px; font-size: 12px;">
                CREATE ACCOUNT
            </button>
        </form>

        <div style="text-align: center; margin-top: 24px; font-size: 13px; color: #71717A;">
            Already have an account? <a href="{{ route('login') }}" style="color: #18181B; font-weight: 700; text-decoration: underline;">Sign In here</a>
        </div>
    </div>
</div>
@endsection
