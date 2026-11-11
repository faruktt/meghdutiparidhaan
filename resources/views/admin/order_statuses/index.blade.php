@extends('layouts.admin')

@section('title', 'Order Statuses')
@section('breadcrumb', 'Order Statuses')

@section('content')
<!-- Top Stat Cards -->
<div class="stat-cards-grid">
    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-purple">
            <i class="fa-solid fa-list-check"></i>
        </div>
        <div>
            <div class="stat-card-sub">Total Statuses</div>
            <div class="stat-card-val">{{ $statuses->count() }}</div>
        </div>
    </div>

    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-green">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div>
            <div class="stat-card-sub">Active Statuses</div>
            <div class="stat-card-val">{{ $statuses->where('is_active', true)->count() }}</div>
        </div>
    </div>

    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-orange">
            <i class="fa-solid fa-cart-shopping"></i>
        </div>
        <div>
            <div class="stat-card-sub">Tracked Orders</div>
            <div class="stat-card-val">{{ \App\Models\Order::count() }}</div>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 340px 1fr; gap: 18px; align-items: start;">
    <!-- Add Status Compact Form -->
    <div class="card" style="padding: 16px; border-radius: 10px;">
        <h3 class="card-title" style="margin-bottom: 12px; font-size: 13.5px; font-weight: 700; display: flex; align-items: center; gap: 6px; border-bottom: 1px solid #E2E8F0; padding-bottom: 8px;">
            <i class="fa-solid fa-plus-circle" style="color: var(--admin-primary);"></i> Create Order Status
        </h3>

        <form action="{{ route('admin.order-statuses.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 10px;">
                <label style="display: block; font-size: 11px; font-weight: 700; color: #374151; margin-bottom: 3px;">Status Name <span style="color: red;">*</span></label>
                <input type="text" name="name" id="newStatusName" class="form-control" style="font-size: 12px; padding: 6px 10px;" placeholder="e.g. Packaging, Ready to Ship" required oninput="updateLivePreview()">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 10px;">
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; color: #374151; margin-bottom: 3px;">Badge Text</label>
                    <div style="display: flex; align-items: center; gap: 4px;">
                        <input type="color" name="color" id="newColor" value="#2563EB" style="width: 32px; height: 30px; border: 1px solid #CBD5E1; border-radius: 4px; cursor: pointer; padding: 1px;" onchange="updateLivePreview()">
                        <input type="text" id="newColorText" value="#2563EB" class="form-control" style="font-size: 11px; padding: 4px 6px;" oninput="document.getElementById('newColor').value=this.value; updateLivePreview();">
                    </div>
                </div>
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; color: #374151; margin-bottom: 3px;">Badge Background</label>
                    <div style="display: flex; align-items: center; gap: 4px;">
                        <input type="color" name="bg_color" id="newBgColor" value="#DBEAFE" style="width: 32px; height: 30px; border: 1px solid #CBD5E1; border-radius: 4px; cursor: pointer; padding: 1px;" onchange="updateLivePreview()">
                        <input type="text" id="newBgColorText" value="#DBEAFE" class="form-control" style="font-size: 11px; padding: 4px 6px;" oninput="document.getElementById('newBgColor').value=this.value; updateLivePreview();">
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 10px;">
                <label style="display: block; font-size: 11px; font-weight: 700; color: #374151; margin-bottom: 3px;">Table Row Color</label>
                <div style="display: flex; align-items: center; gap: 6px;">
                    <input type="color" name="row_bg" id="newRowBg" value="#FFFFFF" style="width: 34px; height: 30px; border: 1px solid #CBD5E1; border-radius: 4px; cursor: pointer; padding: 1px;" onchange="updateLivePreview()">
                    <input type="text" id="newRowBgText" value="#FFFFFF" class="form-control" style="font-size: 11px; padding: 4px 8px;" oninput="document.getElementById('newRowBg').value=this.value; updateLivePreview();">
                </div>
            </div>

            <!-- Live Preview Box -->
            <div style="margin-bottom: 10px; padding: 8px 10px; border-radius: 6px; border: 1px dashed #CBD5E1; background: #FFFFFF;" id="livePreviewContainer">
                <div style="font-size: 10px; font-weight: 700; color: #64748B; margin-bottom: 4px;">LIVE PREVIEW:</div>
                <div id="previewRowSnippet" style="padding: 6px 10px; border-radius: 4px; border: 1px solid #E5E7EB; background-color: #FFFFFF; display: flex; align-items: center; justify-content: space-between;">
                    <span style="font-size: 11px; font-weight: 700; color: #111827;">ORD-EXAMPLE</span>
                    <span id="previewBadgeSnippet" class="badge-status" style="background: #DBEAFE; color: #2563EB; font-weight: 700; font-size: 10.5px;">
                        Status Name <i class="fa-solid fa-chevron-down" style="font-size: 7.5px; margin-left: 2px;"></i>
                    </span>
                </div>
            </div>

            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 11px; font-weight: 700; color: #374151; margin-bottom: 3px;">Sort Order</label>
                <input type="number" name="sort_order" class="form-control" style="font-size: 12px; padding: 5px 8px;" value="{{ $statuses->count() + 1 }}">
            </div>

            <button type="submit" class="btn-orange-add" style="width: 100%; justify-content: center; height: 34px;">
                <i class="fa-solid fa-plus"></i> Save Status
            </button>
        </form>
    </div>

    <!-- Statuses List Table (Exact Sohoj Excel-like Layout) -->
    <div class="table-card-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 45px; text-align: center;">#</th>
                    <th>Status & Slug</th>
                    <th>Badge Preview</th>
                    <th>Row Highlight</th>
                    <th style="text-align: center; width: 90px;">Orders</th>
                    <th style="text-align: right; width: 85px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($statuses as $index => $st)
                    <tr style="background-color: {{ $st->row_bg }};">
                        <td style="text-align: center; color: #9CA3AF; font-weight: 600;">
                            {{ $st->sort_order ?? ($index + 1) }}
                        </td>
                        <td>
                            <strong style="color: #111827; font-size: 12px;">{{ $st->name }}</strong>
                            <div style="font-size: 10px; color: #9CA3AF; font-family: monospace;">{{ $st->slug }}</div>
                        </td>
                        <td>
                            <span class="badge-status" style="background: {{ $st->bg_color }}; color: {{ $st->color }}; font-weight: 700; font-size: 10.5px;">
                                {{ $st->name }} <i class="fa-solid fa-chevron-down" style="font-size: 7.5px; margin-left: 2px;"></i>
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <div style="width: 18px; height: 18px; border-radius: 3px; border: 1px solid #CBD5E1; background-color: {{ $st->row_bg }};"></div>
                                <code style="font-size: 10px; color: #64748B;">{{ $st->row_bg }}</code>
                            </div>
                        </td>
                        <td style="text-align: center;">
                            <a href="{{ route('admin.orders.index', ['status' => $st->slug]) }}" style="background: #FFFFFF; border: 1px solid #E5E7EB; color: #1F2937; padding: 2px 7px; border-radius: 99px; font-weight: 700; font-size: 10.5px; text-decoration: none;">
                                {{ $st->orders_count }}
                            </a>
                        </td>
                        <td style="text-align: right;">
                            <div class="action-btns-wrap" style="justify-content: flex-end;">
                                <button type="button" class="btn-tbl-action btn-tbl-edit" title="Edit" onclick="openEditModal({{ json_encode($st) }})">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>

                                @if(!$st->is_default && $st->orders_count == 0)
                                    <form action="{{ route('admin.order-statuses.destroy', $st->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this status?');">
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
                        <td colspan="6" style="text-align: center; color: #9CA3AF; padding: 30px;">
                            No order statuses found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Status Modal -->
<div id="editStatusModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(2px);">
    <div style="background: white; border-radius: 10px; width: 420px; max-width: 92%; padding: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; border-bottom: 1px solid #E5E7EB; padding-bottom: 10px;">
            <h3 style="font-size: 14px; font-weight: 800; margin: 0; color: #111827; display: flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-pen-to-square" style="color: var(--admin-primary);"></i> Edit Order Status
            </h3>
            <button type="button" onclick="closeEditModal()" style="background: none; border: none; font-size: 17px; cursor: pointer; color: #9CA3AF;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="editStatusForm" method="POST">
            @csrf
            @method('PUT')
            <div style="margin-bottom: 10px;">
                <label style="display: block; font-size: 11px; font-weight: 700; color: #374151; margin-bottom: 3px;">Status Name <span style="color: red;">*</span></label>
                <input type="text" name="name" id="editStatusName" class="form-control" style="font-size: 12px; padding: 6px 10px;" required oninput="updateEditPreview()">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 10px;">
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; color: #374151; margin-bottom: 3px;">Badge Text</label>
                    <div style="display: flex; align-items: center; gap: 4px;">
                        <input type="color" name="color" id="editColor" style="width: 32px; height: 30px; border: 1px solid #CBD5E1; border-radius: 4px; cursor: pointer; padding: 1px;" onchange="updateEditPreview()">
                        <input type="text" id="editColorText" class="form-control" style="font-size: 11px; padding: 4px 6px;" oninput="document.getElementById('editColor').value=this.value; updateEditPreview();">
                    </div>
                </div>
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; color: #374151; margin-bottom: 3px;">Badge Background</label>
                    <div style="display: flex; align-items: center; gap: 4px;">
                        <input type="color" name="bg_color" id="editBgColor" style="width: 32px; height: 30px; border: 1px solid #CBD5E1; border-radius: 4px; cursor: pointer; padding: 1px;" onchange="updateEditPreview()">
                        <input type="text" id="editBgColorText" class="form-control" style="font-size: 11px; padding: 4px 6px;" oninput="document.getElementById('editBgColor').value=this.value; updateEditPreview();">
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 10px;">
                <label style="display: block; font-size: 11px; font-weight: 700; color: #374151; margin-bottom: 3px;">Table Row Color</label>
                <div style="display: flex; align-items: center; gap: 6px;">
                    <input type="color" name="row_bg" id="editRowBg" style="width: 34px; height: 30px; border: 1px solid #CBD5E1; border-radius: 4px; cursor: pointer; padding: 1px;" onchange="updateEditPreview()">
                    <input type="text" id="editRowBgText" class="form-control" style="font-size: 11px; padding: 4px 8px;" oninput="document.getElementById('editRowBg').value=this.value; updateEditPreview();">
                </div>
            </div>

            <div style="margin-bottom: 10px; padding: 8px 10px; border-radius: 6px; border: 1px dashed #CBD5E1; background: #FFFFFF;">
                <div style="font-size: 10px; font-weight: 700; color: #64748B; margin-bottom: 4px;">PREVIEW:</div>
                <div id="editPreviewRow" style="padding: 6px 10px; border-radius: 4px; border: 1px solid #E5E7EB; display: flex; align-items: center; justify-content: space-between;">
                    <span style="font-size: 11px; font-weight: 700; color: #111827;">ORD-EXAMPLE</span>
                    <span id="editPreviewBadge" class="badge-status" style="font-weight: 700; font-size: 10.5px;">Status Preview</span>
                </div>
            </div>

            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 11px; font-weight: 700; color: #374151; margin-bottom: 3px;">Sort Order</label>
                <input type="number" name="sort_order" id="editSortOrder" class="form-control" style="font-size: 12px; padding: 5px 8px;">
            </div>

            <div style="display: flex; gap: 8px; justify-content: flex-end;">
                <button type="button" onclick="closeEditModal()" class="btn-admin-secondary" style="font-size: 12px; padding: 5px 12px;">Cancel</button>
                <button type="submit" class="btn-orange-search" style="font-size: 12px; padding: 6px 16px;">Update Status</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function updateLivePreview() {
        const name = document.getElementById('newStatusName').value || 'Status Name';
        const color = document.getElementById('newColor').value;
        const bgColor = document.getElementById('newBgColor').value;
        const rowBg = document.getElementById('newRowBg').value;

        document.getElementById('newColorText').value = color;
        document.getElementById('newBgColorText').value = bgColor;
        document.getElementById('newRowBgText').value = rowBg;

        const badge = document.getElementById('previewBadgeSnippet');
        badge.innerHTML = `${name} <i class="fa-solid fa-chevron-down" style="font-size: 7.5px; margin-left: 2px;"></i>`;
        badge.style.color = color;
        badge.style.backgroundColor = bgColor;

        const row = document.getElementById('previewRowSnippet');
        row.style.backgroundColor = rowBg;
    }

    function openEditModal(status) {
        document.getElementById('editStatusForm').action = `/admin/order-statuses/${status.id}`;
        document.getElementById('editStatusName').value = status.name;
        document.getElementById('editColor').value = status.color;
        document.getElementById('editColorText').value = status.color;
        document.getElementById('editBgColor').value = status.bg_color;
        document.getElementById('editBgColorText').value = status.bg_color;
        document.getElementById('editRowBg').value = status.row_bg;
        document.getElementById('editRowBgText').value = status.row_bg;
        document.getElementById('editSortOrder').value = status.sort_order;

        updateEditPreview();
        const modal = document.getElementById('editStatusModal');
        modal.style.display = 'flex';
    }

    function closeEditModal() {
        document.getElementById('editStatusModal').style.display = 'none';
    }

    function updateEditPreview() {
        const name = document.getElementById('editStatusName').value || 'Status';
        const color = document.getElementById('editColor').value;
        const bgColor = document.getElementById('editBgColor').value;
        const rowBg = document.getElementById('editRowBg').value;

        document.getElementById('editColorText').value = color;
        document.getElementById('editBgColorText').value = bgColor;
        document.getElementById('editRowBgText').value = rowBg;

        const badge = document.getElementById('editPreviewBadge');
        badge.innerText = name;
        badge.style.color = color;
        badge.style.backgroundColor = bgColor;

        document.getElementById('editPreviewRow').style.backgroundColor = rowBg;
    }
</script>
@endpush
