@extends('layouts.admin')

@section('title', 'Pages')
@section('breadcrumb', 'Pages')

@section('content')
<!-- Top Stat Cards (Order Style) -->
<div class="stat-cards-grid">
    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-blue">
            <i class="fa-solid fa-file-lines"></i>
        </div>
        <div>
            <div class="stat-card-sub">Total Pages</div>
            <div class="stat-card-val">{{ $pages->count() }}</div>
        </div>
    </div>

    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-green">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div>
            <div class="stat-card-sub">Active in Footer</div>
            <div class="stat-card-val">{{ $pages->where('is_active', true)->count() }}</div>
        </div>
    </div>
</div>

<!-- Search & Action Bar (Order Style) -->
<div class="filter-card-bar">
    <div class="filter-left-group">
        <div class="search-input-wrap">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="pageSearchInput" class="filter-search-input" placeholder="Search pages..." oninput="filterPageRows()">
        </div>
    </div>

    <div style="display: flex; gap: 8px;">
        <a href="{{ route('admin.pages.create') }}" class="btn-orange-add">
            <i class="fa-solid fa-plus"></i> Create New Page
        </a>
    </div>
</div>

<!-- Pages Table (Order Table Style) -->
<div class="table-card-container">
    <table class="admin-table" id="pagesTable">
        <thead>
            <tr>
                <th style="width: 45px; text-align: center;">#</th>
                <th>Page Title</th>
                <th style="width: 170px;">Slug / URL</th>
                <th>Description Preview</th>
                <th style="text-align: center; width: 85px;">Sort Order</th>
                <th style="text-align: center; width: 90px;">Status</th>
                <th style="text-align: right; width: 110px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pages as $index => $page)
                <tr>
                    <td style="text-align: center; color: #9CA3AF; font-weight: 600;">
                        {{ $page->sort_order ?? ($index + 1) }}
                    </td>
                    <td>
                        <strong style="color: #111827; font-size: 12.5px;">{{ $page->title }}</strong>
                    </td>
                    <td>
                        <span class="badge" style="background: #F1F5F9; color: #334155; font-family: monospace; font-size: 10.5px;">
                            /page/{{ $page->slug }}
                        </span>
                    </td>
                    <td style="color: #4B5563; font-size: 11.5px;">
                        {{ Str::limit(strip_tags($page->description), 50) }}
                    </td>
                    <td style="text-align: center; font-weight: 700; color: #1E293B;">
                        {{ $page->sort_order }}
                    </td>
                    <td style="text-align: center;">
                        <button type="button" onclick="togglePageStatus({{ $page->id }})" id="statusBtn_{{ $page->id }}" class="badge-status {{ $page->is_active ? 'badge-success' : 'badge-secondary' }}" style="border: none; cursor: pointer; padding: 2px 8px; font-size: 10.5px;">
                            {{ $page->is_active ? 'Active' : 'Inactive' }}
                        </button>
                    </td>
                    <td style="text-align: right;">
                        <div class="action-btns-wrap" style="justify-content: flex-end;">
                            <a href="{{ route('page.show', $page->slug) }}" target="_blank" class="btn-tbl-action" title="View in Frontend" style="color: #2563EB;">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                            <a href="{{ route('admin.pages.edit', $page) }}" class="btn-tbl-action btn-tbl-edit" title="Edit Page">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this page?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-tbl-action btn-tbl-delete" title="Delete">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #9CA3AF; padding: 30px;">No pages found. Click "Create New Page" to add one.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
    function filterPageRows() {
        const q = (document.getElementById('pageSearchInput').value || '').toLowerCase();
        const rows = document.querySelectorAll('#pagesTable tbody tr');
        rows.forEach(r => {
            const text = r.innerText.toLowerCase();
            r.style.display = text.includes(q) ? '' : 'none';
        });
    }

    function togglePageStatus(id) {
        fetch(`/admin/pages/${id}/toggle-status`, {
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
                const btn = document.getElementById(`statusBtn_${id}`);
                if (data.is_active) {
                    btn.innerText = 'Active';
                    btn.className = 'badge-status badge-success';
                } else {
                    btn.innerText = 'Inactive';
                    btn.className = 'badge-status badge-secondary';
                }
            }
        });
    }
</script>
@endpush
