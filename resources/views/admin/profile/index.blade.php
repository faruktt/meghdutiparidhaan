@extends('layouts.admin')

@section('title', 'Admin Profile Settings')
@section('breadcrumb', 'Profile Settings')

@section('content')
<div class="card-header-flex" style="margin-bottom: 20px;">
    <div>
        <a href="{{ route('admin.dashboard') }}" style="font-size: 13px; color: var(--text-muted); text-decoration: none;">
            <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
        </a>
        <h1 style="font-size: 22px; font-weight: 800; color: var(--text-dark); margin-top: 4px;">Account & Security Settings</h1>
        <p style="font-size: 13px; color: var(--text-muted); margin-top: 2px;">Manage your administrator profile details, contact email, and security password.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 24px; @media(max-width: 900px){grid-template-columns: 1fr;}">
    
    <!-- Profile Info Card -->
    <div>
        <div class="card" style="margin-bottom: 24px;">
            <div style="display: flex; align-items: center; gap: 16px; padding-bottom: 18px; margin-bottom: 18px; border-bottom: 1px solid var(--admin-border);">
                <div style="width: 58px; height: 58px; border-radius: 50%; background: #FFF7ED; border: 2px solid #FED7AA; color: var(--admin-primary); display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 800;">
                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                </div>
                <div>
                    <h3 style="font-size: 17px; font-weight: 800; color: var(--text-dark); margin-bottom: 2px;">{{ $admin->name }}</h3>
                    <div style="font-size: 12.5px; color: var(--text-muted); display: flex; align-items: center; gap: 8px;">
                        <span><i class="fa-solid fa-envelope"></i> {{ $admin->email }}</span>
                        <span>&bull;</span>
                        <span style="display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 700; background: #EEF2F6; color: #334155;">
                            {{ $admin->role?->name ?? 'Super Admin' }}
                        </span>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px; color: var(--text-dark);">
                        Full Name <span style="color: red;">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           value="{{ old('name', $admin->name) }}" 
                           class="form-control @error('name') is-invalid @enderror" 
                           required>
                    @error('name')
                        <div style="font-size: 12px; color: #DC2626; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px; color: var(--text-dark);">
                        Email Address <span style="color: red;">*</span>
                    </label>
                    <input type="email" 
                           name="email" 
                           value="{{ old('email', $admin->email) }}" 
                           class="form-control @error('email') is-invalid @enderror" 
                           required>
                    @error('email')
                        <div style="font-size: 12px; color: #DC2626; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 4px; color: var(--text-muted);">Assigned Role</label>
                    <input type="text" value="{{ $admin->role?->name ?? 'Super Admin (Full Access)' }}" class="form-control" style="background: #F8FAFC; color: #64748B;" readonly>
                </div>

                <button type="submit" class="btn-admin-primary" style="padding: 10px 22px; font-size: 13.5px;">
                    <i class="fa-solid fa-floppy-disk"></i> Save Profile Changes
                </button>
            </form>
        </div>
    </div>

    <!-- Password Change Card -->
    <div>
        <div class="card">
            <div class="card-header-flex" style="margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid var(--admin-border);">
                <div>
                    <h3 class="card-title" style="font-size: 16px;"><i class="fa-solid fa-lock" style="color: var(--admin-primary); margin-right: 6px;"></i> Change Password</h3>
                    <p style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">Ensure your account is using a long, random password to stay secure.</p>
                </div>
            </div>

            <form action="{{ route('admin.profile.password') }}" method="POST">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px; color: var(--text-dark);">
                        Current Password <span style="color: red;">*</span>
                    </label>
                    <input type="password" 
                           name="current_password" 
                           class="form-control @error('current_password') is-invalid @enderror" 
                           placeholder="Enter current password" 
                           required>
                    @error('current_password')
                        <div style="font-size: 12px; color: #DC2626; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px; color: var(--text-dark);">
                        New Password <span style="color: red;">*</span>
                    </label>
                    <input type="password" 
                           name="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           placeholder="Minimum 6 characters" 
                           required>
                    @error('password')
                        <div style="font-size: 12px; color: #DC2626; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px; color: var(--text-dark);">
                        Confirm New Password <span style="color: red;">*</span>
                    </label>
                    <input type="password" 
                           name="password_confirmation" 
                           class="form-control" 
                           placeholder="Confirm new password" 
                           required>
                </div>

                <button type="submit" class="btn-admin-primary" style="width: 100%; justify-content: center; padding: 11px; font-size: 14px; background: #0F172A;">
                    <i class="fa-solid fa-key"></i> Update Security Password
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
