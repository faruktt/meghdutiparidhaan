@extends('layouts.admin')

@section('title', 'Roles & Permissions')
@section('breadcrumb', 'Roles & Permissions')

@section('content')
<!-- Filter & Action Bar (Order Style) -->
<div class="filter-card-bar">
    <div class="filter-left-group">
        <div class="search-input-wrap">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="roleSearchInput" class="filter-search-input" placeholder="Search roles..." oninput="filterRoleRows()">
        </div>
    </div>

    <div style="display: flex; gap: 8px;">
        <a href="{{ route('admin.staff.index') }}" class="btn-admin-secondary" style="font-size: 12px; padding: 6px 12px; height: 34px;">
            <i class="fa-solid fa-user-shield"></i> Staff Members
        </a>
        <a href="{{ route('admin.roles.create') }}" class="btn-orange-add">
            <i class="fa-solid fa-plus"></i> Create New Role
        </a>
    </div>
</div>

<!-- Roles Table (Order Table Style) -->
<div class="table-card-container">
    <table class="admin-table" id="rolesTable">
        <thead>
            <tr>
                <th style="width: 45px; text-align: center;">#</th>
                <th>Role Name</th>
                <th style="width: 140px;">Slug</th>
                <th>Description</th>
                <th style="text-align: center; width: 160px;">Permissions</th>
                <th style="text-align: center; width: 110px;">Assigned Staff</th>
                <th style="text-align: right; width: 100px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($roles as $index => $role)
                <tr>
                    <td style="text-align: center; color: #9CA3AF; font-weight: 600;">
                        {{ $index + 1 }}
                    </td>
                    <td>
                        <strong style="color: #111827; font-size: 12.5px;">{{ $role->name }}</strong>
                        @if($role->slug === 'super-admin')
                            <span class="badge" style="background: #FEF3C7; color: #D97706; margin-left: 4px; font-size: 9.5px; padding: 1px 5px;">Super Admin</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge" style="background: #F1F5F9; color: #334155; font-family: monospace; font-size: 10.5px;">{{ $role->slug }}</span>
                    </td>
                    <td style="color: #6B7280; font-size: 11.5px;">
                        {{ $role->description ?? '—' }}
                    </td>
                    <td style="text-align: center;">
                        @if($role->slug === 'super-admin')
                            <span style="background: #FEF3C7; color: #D97706; padding: 2px 8px; border-radius: 99px; font-size: 11px; font-weight: 700;">
                                <i class="fa-solid fa-crown" style="font-size: 9px;"></i> All Access ({{ $role->permissions_count }} Perms)
                            </span>
                        @else
                            <span style="background: #EFF6FF; color: #2563EB; padding: 2px 8px; border-radius: 99px; font-size: 11px; font-weight: 700;">
                                <i class="fa-solid fa-key" style="font-size: 9px;"></i> {{ $role->permissions_count }} Permissions
                            </span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <span style="background: #F0FDF4; color: #16A34A; padding: 2px 8px; border-radius: 99px; font-size: 11px; font-weight: 700;">
                            <i class="fa-solid fa-user-group" style="font-size: 9px;"></i> {{ $role->admins_count }}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <div class="action-btns-wrap" style="justify-content: flex-end;">
                            <a href="{{ route('admin.roles.edit', $role) }}" class="btn-tbl-action btn-tbl-edit" title="Edit Permissions">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            @if($role->slug !== 'super-admin')
                                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this role?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-tbl-action btn-tbl-delete" title="Delete">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #9CA3AF; padding: 30px;">No roles found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
    function filterRoleRows() {
        const q = (document.getElementById('roleSearchInput').value || '').toLowerCase();
        const rows = document.querySelectorAll('#rolesTable tbody tr');
        rows.forEach(r => {
            const text = r.innerText.toLowerCase();
            r.style.display = text.includes(q) ? '' : 'none';
        });
    }
</script>
@endpush
