@extends('layouts.admin')

@section('title', 'Staff Management')
@section('breadcrumb', 'Staff Management')

@section('content')
<!-- Top Stat Cards (Order Style) -->
<div class="stat-cards-grid">
    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-blue">
            <i class="fa-solid fa-user-shield"></i>
        </div>
        <div>
            <div class="stat-card-sub">Total Staff</div>
            <div class="stat-card-val">{{ $staffMembers->count() }}</div>
        </div>
    </div>

    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-green">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div>
            <div class="stat-card-sub">Active Staff</div>
            <div class="stat-card-val">{{ $staffMembers->where('is_active', true)->count() }}</div>
        </div>
    </div>

    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-purple">
            <i class="fa-solid fa-shield-halved"></i>
        </div>
        <div>
            <div class="stat-card-sub">Staff Roles</div>
            <div class="stat-card-val">{{ \App\Models\Role::count() }}</div>
        </div>
    </div>
</div>

<!-- Filter & Action Bar (Order Style) -->
<div class="filter-card-bar">
    <div class="filter-left-group">
        <div class="search-input-wrap">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="staffSearchInput" class="filter-search-input" placeholder="Search staff by name, email or phone..." oninput="filterStaffRows()">
        </div>
    </div>

    <div style="display: flex; gap: 8px;">
        <a href="{{ route('admin.roles.index') }}" class="btn-admin-secondary" style="font-size: 12px; padding: 6px 12px; height: 34px;">
            <i class="fa-solid fa-shield-halved"></i> Roles & Permissions
        </a>
        <a href="{{ route('admin.staff.create') }}" class="btn-orange-add">
            <i class="fa-solid fa-plus"></i> Add New Staff
        </a>
    </div>
</div>

<!-- Staff Table (Order Table Style) -->
<div class="table-card-container">
    <table class="admin-table" id="staffTable">
        <thead>
            <tr>
                <th style="width: 45px; text-align: center;">#</th>
                <th>Staff Name</th>
                <th>Email & Phone</th>
                <th>Assigned Role</th>
                <th style="text-align: center; width: 90px;">Status</th>
                <th style="text-align: right; width: 100px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($staffMembers as $index => $member)
                <tr>
                    <td style="text-align: center; color: #9CA3AF; font-weight: 600;">
                        {{ $index + 1 }}
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: #EFF6FF; color: #2563EB; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px; flex-shrink: 0;">
                                {{ strtoupper(substr($member->name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight: 700; color: #1E293B; font-size: 12.5px;">{{ $member->name }}</div>
                                @if(auth('admin')->id() === $member->id)
                                    <span class="badge" style="background: #FEF3C7; color: #D97706; font-size: 9.5px; padding: 1px 6px;">Current User</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-size: 12px; color: #334155;">{{ $member->email }}</div>
                        @if($member->phone)
                            <div style="font-size: 11px; color: #64748B;">{{ $member->phone }}</div>
                        @endif
                    </td>
                    <td>
                        @if($member->role)
                            <span class="badge" style="background: #EFF6FF; color: #2563EB; font-weight: 700; font-size: 11px;">
                                <i class="fa-solid fa-shield-halved" style="font-size: 9.5px;"></i> {{ $member->role->name }}
                            </span>
                        @else
                            <span class="badge" style="background: #FEF3C7; color: #D97706; font-weight: 700; font-size: 11px;">
                                <i class="fa-solid fa-crown" style="font-size: 9.5px;"></i> Super Admin
                            </span>
                        @endif
                        @if($member->customPermissions->count() > 0)
                            <div style="font-size: 10px; color: #EA580C; margin-top: 2px; font-weight: 600;">
                                +{{ $member->customPermissions->count() }} Custom Perms
                            </div>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        @if(auth('admin')->id() === $member->id)
                            <span class="badge-status badge-success" style="padding: 2px 8px; font-size: 10.5px;">Active</span>
                        @else
                            <button type="button" onclick="toggleStaffStatus({{ $member->id }})" id="staffStatusBtn_{{ $member->id }}" class="badge-status {{ $member->is_active ? 'badge-success' : 'badge-secondary' }}" style="border: none; cursor: pointer; padding: 2px 8px; font-size: 10.5px;">
                                {{ $member->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        <div class="action-btns-wrap" style="justify-content: flex-end;">
                            <a href="{{ route('admin.staff.edit', $member) }}" class="btn-tbl-action btn-tbl-edit" title="Edit Staff">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            @if(auth('admin')->id() !== $member->id)
                                <form action="{{ route('admin.staff.destroy', $member) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this staff member?');">
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
                    <td colspan="7" style="text-align: center; color: #9CA3AF; padding: 30px;">No staff members found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
    function filterStaffRows() {
        const q = (document.getElementById('staffSearchInput').value || '').toLowerCase();
        const rows = document.querySelectorAll('#staffTable tbody tr');
        rows.forEach(r => {
            const text = r.innerText.toLowerCase();
            r.style.display = text.includes(q) ? '' : 'none';
        });
    }

    function toggleStaffStatus(id) {
        fetch(`/admin/staff/${id}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const btn = document.getElementById(`staffStatusBtn_${id}`);
                if (data.is_active) {
                    btn.innerText = 'Active';
                    btn.className = 'badge-status badge-success';
                } else {
                    btn.innerText = 'Inactive';
                    btn.className = 'badge-status badge-secondary';
                }
            } else {
                alert(data.message || 'Error updating status');
            }
        });
    }
</script>
@endpush
