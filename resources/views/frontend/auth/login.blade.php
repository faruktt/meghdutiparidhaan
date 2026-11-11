@extends('layouts.app')

@section('title', 'Customer Sign In | ' . \App\Models\Setting::get('site_name', 'Meghduti Paridhaan'))

@section('content')
<div class="container" style="max-width: 440px; margin: 60px auto 80px;">
    <div style="background: white; border-radius: 4px; border: 1px solid #E4E4E7; padding: 36px;">
        <div style="text-align: center; margin-bottom: 24px;">
            <div style="width: 50px; height: 50px; border-radius: 50%; background: #FAFAFA; border: 1px solid #E4E4E7; color: #18181B; display: inline-flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 12px;">
                <i class="fa-solid fa-user"></i>
            </div>
            <h1 style="font-size: 20px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; color: #18181B;">SIGN IN</h1>
            <p style="font-size: 13px; color: #71717A; margin-top: 4px;">Access your personal wardrobe and order tracking</p>
        </div>

        @if($errors->any())
            <div style="background: #FEF2F2; color: #DC2626; border: 1px solid #FEE2E2; padding: 12px 16px; border-radius: 2px; font-size: 13px; margin-bottom: 20px;">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 12px; font-weight: 700; text-transform: uppercase; margin-bottom: 8px;">Phone Number or Email</label>
                <input type="text" name="login" value="{{ old('login') }}" class="form-input" placeholder="e.g. 017xxxxxxxx or name@email.com" required autofocus style="width: 100%; padding: 12px 14px; border: 1px solid #D4D4D8; border-radius: 2px; font-size: 13px; outline: none;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 12px; font-weight: 700; text-transform: uppercase; margin-bottom: 8px;">Password</label>
                <input type="password" name="password" class="form-input" placeholder="••••••••" required style="width: 100%; padding: 12px 14px; border: 1px solid #D4D4D8; border-radius: 2px; font-size: 13px; outline: none;">
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; font-size: 12.5px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="remember"> Remember me
                </label>
                <a href="#" style="color: #71717A; text-decoration: underline;">Forgot password?</a>
            </div>

            <button type="submit" class="btn-black-pill" style="padding: 13px; font-size: 12px;">
                SIGN IN
            </button>
        </form>

        <div style="text-align: center; margin-top: 24px; font-size: 13px; color: #71717A;">
            Don't have an account? <a href="{{ route('register') }}" style="color: #18181B; font-weight: 700; text-decoration: underline;">Create Account</a>
        </div>
    </div>
</div>
@endsection
