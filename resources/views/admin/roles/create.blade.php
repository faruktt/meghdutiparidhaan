@extends('layouts.admin')

@section('title', 'Create Role')
@section('breadcrumb', 'Roles / Create')

@section('content')
<form action="{{ route('admin.roles.store') }}" method="POST">
    @csrf

    <div class="card" style="padding: 18px; margin-bottom: 16px; border-radius: 10px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; border-bottom: 1px solid #E2E8F0; padding-bottom: 10px;">
            <h3 class="card-title" style="margin: 0; font-size: 14px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-shield-plus" style="color: var(--admin-primary);"></i> Role Details
            </h3>
            <div style="display: flex; gap: 8px;">
                <button type="button" onclick="toggleAllPermissions(true)" class="btn-admin-secondary" style="font-size: 11px; padding: 4px 8px;">Select All</button>
                <button type="button" onclick="toggleAllPermissions(false)" class="btn-admin-secondary" style="font-size: 11px; padding: 4px 8px;">Deselect All</button>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 14px;">
            <div>
                <label style="display: block; font-size: 11.5px; font-weight: 700; color: #374151; margin-bottom: 4px;">Role Name <span style="color: red;">*</span></label>
                <input type="text" name="name" class="form-control" style="font-size: 12.5px; padding: 6px 10px;" placeholder="e.g. Store Manager, Cashier, Inventory Lead" required>
            </div>
            <div>
                <label style="display: block; font-size: 11.5px; font-weight: 700; color: #374151; margin-bottom: 4px;">Description</label>
                <input type="text" name="description" class="form-control" style="font-size: 12.5px; padding: 6px 10px;" placeholder="Brief description of responsibilities...">
            </div>
        </div>
    </div>

    <!-- Permissions grouped by Module -->
    <div style="margin-bottom: 10px; font-size: 13px; font-weight: 800; color: #1E293B; display: flex; align-items: center; gap: 6px;">
        <i class="fa-solid fa-lock-open" style="color: #EA580C;"></i> Assign Permissions
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 14px; margin-bottom: 20px;">
        @foreach($permissions as $module => $modulePerms)
            <div class="card" style="padding: 14px; border: 1px solid #E2E8F0; border-radius: 10px; background: #FFFFFF;">
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #F1F5F9; padding-bottom: 8px; margin-bottom: 8px;">
                    <strong style="font-size: 12.5px; color: #1E293B; display: flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-folder-open" style="color: var(--admin-primary); font-size: 12px;"></i> {{ $module }}
                    </strong>
                    <button type="button" onclick="toggleModuleCheckboxes('{{ Str::slug($module) }}')" style="background: none; border: none; font-size: 11px; color: #2563EB; cursor: pointer; font-weight: 700;">
                        Toggle
                    </button>
                </div>

                <div style="display: flex; flex-direction: column; gap: 6px;">
                    @foreach($modulePerms as $perm)
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 12px; color: #334155; cursor: pointer; padding: 3px 6px; border-radius: 4px; transition: background 0.15s;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                            <input type="checkbox" name="permissions[]" value="{{ $perm->id }}" class="perm-checkbox perm-mod-{{ Str::slug($module) }}" style="width: 14px; height: 14px;">
                            <span>{{ $perm->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <div style="display: flex; justify-content: flex-end; gap: 8px;">
        <a href="{{ route('admin.roles.index') }}" class="btn-admin-secondary" style="font-size: 12px; padding: 6px 14px;">Cancel</a>
        <button type="submit" class="btn-orange-search" style="font-size: 12px; padding: 6px 18px;">
            <i class="fa-solid fa-check"></i> Save Role
        </button>
    </div>
</form>
@endsection

@push('scripts')
<script>
    function toggleAllPermissions(checked) {
        document.querySelectorAll('.perm-checkbox').forEach(cb => cb.checked = checked);
    }

    function toggleModuleCheckboxes(modSlug) {
        const cbs = document.querySelectorAll(`.perm-mod-${modSlug}`);
        const someUnchecked = Array.from(cbs).some(cb => !cb.checked);
        cbs.forEach(cb => cb.checked = someUnchecked);
    }
</script>
@endpush
