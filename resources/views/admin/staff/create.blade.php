@extends('layouts.admin')

@section('title', 'Add New Staff')
@section('breadcrumb', 'Staff / Create')

@section('content')
<form action="{{ route('admin.staff.store') }}" method="POST">
    @csrf

    <div class="card" style="padding: 18px; margin-bottom: 16px; border-radius: 10px;">
        <h3 class="card-title" style="margin-bottom: 14px; font-size: 14px; font-weight: 700; display: flex; align-items: center; gap: 8px; border-bottom: 1px solid #E2E8F0; padding-bottom: 10px;">
            <i class="fa-solid fa-user-plus" style="color: var(--admin-primary);"></i> Staff Information
        </h3>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 12px;">
            <div>
                <label style="display: block; font-size: 11.5px; font-weight: 700; color: #374151; margin-bottom: 4px;">Staff Name <span style="color: red;">*</span></label>
                <input type="text" name="name" class="form-control" style="font-size: 12.5px; padding: 6px 10px;" placeholder="e.g. Arif Hossain" required>
            </div>
            <div>
                <label style="display: block; font-size: 11.5px; font-weight: 700; color: #374151; margin-bottom: 4px;">Email Address <span style="color: red;">*</span></label>
                <input type="email" name="email" class="form-control" style="font-size: 12.5px; padding: 6px 10px;" placeholder="staff@example.com" required>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 12px;">
            <div>
                <label style="display: block; font-size: 11.5px; font-weight: 700; color: #374151; margin-bottom: 4px;">Phone Number</label>
                <input type="text" name="phone" class="form-control" style="font-size: 12.5px; padding: 6px 10px;" placeholder="017xxxxxxxx">
            </div>
            <div>
                <label style="display: block; font-size: 11.5px; font-weight: 700; color: #374151; margin-bottom: 4px;">Login Password <span style="color: red;">*</span></label>
                <input type="password" name="password" class="form-control" style="font-size: 12.5px; padding: 6px 10px;" placeholder="Minimum 6 characters" required>
            </div>
        </div>

        <div style="margin-bottom: 12px;">
            <label style="display: block; font-size: 11.5px; font-weight: 700; color: #374151; margin-bottom: 4px;">Assigned Role <span style="color: red;">*</span></label>
            <select name="role_id" class="form-control" style="font-size: 12.5px; padding: 6px 10px;" required>
                <option value="">-- Select Role --</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }} ({{ $role->permissions->count() }} Perms)</option>
                @endforeach
            </select>
        </div>

        <div style="margin-top: 6px;">
            <label style="display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; cursor: pointer;">
                <input type="checkbox" name="is_active" value="1" checked style="width: 15px; height: 15px;">
                Active Account
            </label>
        </div>
    </div>

    <!-- Custom Direct Permissions (Override) -->
    <div class="card" style="padding: 18px; margin-bottom: 20px; border-radius: 10px;">
        <div style="margin-bottom: 12px; border-bottom: 1px solid #E2E8F0; padding-bottom: 8px;">
            <h4 style="margin: 0; font-size: 13.5px; font-weight: 800; color: #1E293B; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-key" style="color: #EA580C;"></i> Direct Permissions Override (Optional)
            </h4>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 12px;">
            @foreach($permissions as $module => $modulePerms)
                <div style="border: 1px solid #E2E8F0; border-radius: 8px; padding: 10px; background: #F8FAFC;">
                    <strong style="font-size: 12px; color: #1E293B; display: block; margin-bottom: 6px; border-bottom: 1px solid #E2E8F0; padding-bottom: 4px;">
                        {{ $module }}
                    </strong>
                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        @foreach($modulePerms as $perm)
                            <label style="display: flex; align-items: center; gap: 6px; font-size: 11.5px; color: #334155; cursor: pointer;">
                                <input type="checkbox" name="custom_permissions[]" value="{{ $perm->id }}" style="width: 14px; height: 14px;">
                                <span>{{ $perm->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div style="display: flex; justify-content: flex-end; gap: 8px;">
        <a href="{{ route('admin.staff.index') }}" class="btn-admin-secondary" style="font-size: 12px; padding: 6px 14px;">Cancel</a>
        <button type="submit" class="btn-orange-search" style="font-size: 12px; padding: 6px 18px;">
            <i class="fa-solid fa-check"></i> Save Staff
        </button>
    </div>
</form>
@endsection
