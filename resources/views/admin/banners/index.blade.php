@extends('layouts.admin')

@section('title', 'Banners & Sliders')
@section('breadcrumb', 'Banners & Sliders')

@section('content')
<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px; align-items: start;">
    <!-- Add Banner Form -->
    <div class="card" style="box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
        <h3 class="card-title" style="margin-bottom: 16px; font-size: 15px; font-weight: 800; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-images" style="color: var(--admin-primary);"></i> Add New Banner
        </h3>
        
        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12.5px; font-weight: 700; margin-bottom: 5px;">Title</label>
                <input type="text" name="title" class="form-control" placeholder="e.g. Elevate Your Style 2026" value="{{ old('title') }}">
            </div>

            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12.5px; font-weight: 700; margin-bottom: 5px;">Subtitle / Tagline</label>
                <input type="text" name="subtitle" class="form-control" placeholder="e.g. 100% Pure Egyptian Cotton" value="{{ old('subtitle') }}">
            </div>

            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12.5px; font-weight: 700; margin-bottom: 5px;">
                    Banner Image <span style="color: red;">*</span>
                    <small style="color: #94A3B8; font-weight: 500;">(Recommended: 1920x800 for Hero, 1000x500 for Promo)</small>
                </label>
                <input type="file" name="image" accept="image/*" class="form-control" required onchange="previewAddBanner(this)">
                
                <div id="addBannerPreviewContainer" style="display: none; margin-top: 8px; border-radius: 6px; overflow: hidden; border: 1px dashed #CBD5E1; max-height: 120px; background: #000;">
                    <img id="addBannerPreviewImg" src="" style="width: 100%; height: 120px; object-fit: cover; display: block;" alt="Preview">
                </div>
            </div>

            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12.5px; font-weight: 700; margin-bottom: 5px;">Target Link</label>
                <input type="text" name="link" class="form-control" placeholder="/shop or /product/example-shirt" value="{{ old('link') }}">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 12.5px; font-weight: 700; margin-bottom: 5px;">Position <span style="color: red;">*</span></label>
                    <select name="position" class="form-control" required>
                        <option value="hero" {{ old('position') == 'hero' ? 'selected' : '' }}>Main Hero Slider</option>
                        <option value="promo" {{ old('position') == 'promo' ? 'selected' : '' }}>Promotional Strip</option>
                        <option value="category" {{ old('position') == 'category' ? 'selected' : '' }}>Category Banner</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 12.5px; font-weight: 700; margin-bottom: 5px;">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" min="0">
                </div>
            </div>

            <div style="margin-bottom: 18px; padding: 10px 12px; background: #F8FAFC; border-radius: 8px; border: 1px solid #E2E8F0;">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 13px; font-weight: 700; margin: 0;">
                    <input type="checkbox" name="is_active" value="1" checked style="width: 17px; height: 17px; accent-color: var(--admin-primary); cursor: pointer;">
                    <span>Active (Display on website)</span>
                </label>
            </div>

            <button type="submit" class="btn-orange-add" style="width: 100%; justify-content: center; padding: 10px;">
                <i class="fa-solid fa-cloud-arrow-up"></i> Upload & Publish Banner
            </button>
        </form>
    </div>

    <!-- Banners Table -->
    <div class="table-card-container">
        <div style="padding: 8px 12px; border-bottom: 1px solid #CBD5E1; display: flex; align-items: center; justify-content: space-between; background: #F8FAFC;">
            <div style="font-size: 12.5px; font-weight: 800; color: #0F172A;">
                Active & Configured Banners ({{ $banners->count() }})
            </div>
            <div style="font-size: 11px; color: #64748B;">
                Click status badge to toggle Active / Inactive
            </div>
        </div>

        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 100px;">Banner</th>
                    <th>Details & Position</th>
                    <th style="text-align: center; width: 70px;">Order</th>
                    <th style="width: 110px;">Status</th>
                    <th style="text-align: right; width: 90px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($banners as $banner)
                    <tr id="banner-row-{{ $banner->id }}">
                        <td>
                            <div style="width: 90px; height: 48px; border-radius: 6px; overflow: hidden; background: #000; border: 1px solid #E2E8F0; position: relative;">
                                <img src="{{ $banner->image_url }}" style="width: 100%; height: 100%; object-fit: cover; display: block;" alt="{{ $banner->title }}" onerror="this.src='https://placehold.co/180x96?text=No+Image';">
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 700; color: #0F172A; font-size: 13px;">
                                {{ $banner->title ?: 'Untitled Banner' }}
                            </div>
                            @if($banner->subtitle)
                                <div style="font-size: 11.5px; color: #64748B; margin-top: 1px;">{{ $banner->subtitle }}</div>
                            @endif
                            <div style="margin-top: 4px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                @if($banner->position === 'hero')
                                    <span class="badge-status badge-info" style="font-size: 10px;">Hero Slider</span>
                                @elseif($banner->position === 'promo')
                                    <span class="badge-status badge-warning" style="font-size: 10px;">Promo Strip</span>
                                @else
                                    <span class="badge-status badge-neutral" style="font-size: 10px;">Category</span>
                                @endif

                                @if($banner->link)
                                    <a href="{{ $banner->link }}" target="_blank" style="font-size: 11px; color: #64748B; text-decoration: none;" title="{{ $banner->link }}">
                                        <i class="fa-solid fa-link" style="font-size: 10px;"></i> {{ Str::limit($banner->link, 24) }}
                                    </a>
                                @endif
                            </div>
                        </td>
                        <td style="text-align: center; font-weight: 700; color: #475569;">
                            {{ $banner->sort_order }}
                        </td>
                        <td>
                            <!-- Interactive Status Toggle Button -->
                            <button type="button" 
                                    class="status-toggle-btn badge-status {{ $banner->is_active ? 'badge-success' : 'badge-neutral' }}" 
                                    style="border: none; cursor: pointer; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px;"
                                    onclick="toggleBannerStatus({{ $banner->id }}, this)"
                                    title="Click to toggle Active / Inactive">
                                <i class="fa-solid {{ $banner->is_active ? 'fa-check' : 'fa-xmark' }}"></i>
                                <span class="status-text">{{ $banner->is_active ? 'Active' : 'Inactive' }}</span>
                            </button>
                        </td>
                        <td style="text-align: right;">
                            <div class="action-btns-wrap">
                                <!-- Edit Button -->
                                <button type="button" 
                                        class="btn-tbl-action btn-tbl-edit" 
                                        title="Edit Banner"
                                        onclick="openEditBannerModal({{ json_encode([
                                            'id' => $banner->id,
                                            'title' => $banner->title ?? '',
                                            'subtitle' => $banner->subtitle ?? '',
                                            'link' => $banner->link ?? '',
                                            'position' => $banner->position ?? 'hero',
                                            'sort_order' => $banner->sort_order ?? 0,
                                            'is_active' => (bool)$banner->is_active,
                                            'image_url' => $banner->image_url,
                                            'update_url' => route('admin.banners.update', $banner->id)
                                        ]) }})">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>

                                <!-- Delete Form -->
                                <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this banner?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-tbl-action btn-tbl-delete" title="Delete">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 48px 16px;">
                            <div style="font-size: 32px; color: #CBD5E1; margin-bottom: 8px;">
                                <i class="fa-regular fa-image"></i>
                            </div>
                            <div style="font-weight: 700; color: #334155; margin-bottom: 4px;">No banners found</div>
                            <p style="font-size: 12px; margin: 0;">Upload your first banner using the form on the left.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Banner Modal -->
<div id="editBannerModal" style="display: none; position: fixed; inset: 0; z-index: 9999; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); align-items: center; justify-content: center; padding: 20px;">
    <div style="background: #FFFFFF; width: 100%; max-width: 540px; border-radius: 14px; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.2); animation: modalPop 0.2s ease-out;">
        <!-- Modal Header -->
        <div style="padding: 16px 20px; border-bottom: 1px solid var(--admin-border); display: flex; align-items: center; justify-content: space-between; background: #F8FAFC;">
            <div style="font-size: 15px; font-weight: 800; color: #0F172A; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-pen-to-square" style="color: var(--admin-primary);"></i> Edit Banner
            </div>
            <button type="button" onclick="closeEditBannerModal()" style="border: none; background: transparent; font-size: 16px; color: #64748B; cursor: pointer; padding: 4px;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Modal Body & Form -->
        <form id="editBannerForm" action="" method="POST" enctype="multipart/form-data" style="padding: 20px;">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12.5px; font-weight: 700; margin-bottom: 5px;">Title</label>
                <input type="text" id="edit_title" name="title" class="form-control" placeholder="Banner Title">
            </div>

            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12.5px; font-weight: 700; margin-bottom: 5px;">Subtitle</label>
                <input type="text" id="edit_subtitle" name="subtitle" class="form-control" placeholder="Subtitle">
            </div>

            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12.5px; font-weight: 700; margin-bottom: 5px;">Link URL</label>
                <input type="text" id="edit_link" name="link" class="form-control" placeholder="/shop">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 12.5px; font-weight: 700; margin-bottom: 5px;">Position</label>
                    <select id="edit_position" name="position" class="form-control" required>
                        <option value="hero">Main Hero Slider</option>
                        <option value="promo">Promotional Strip</option>
                        <option value="category">Category Banner</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 12.5px; font-weight: 700; margin-bottom: 5px;">Sort Order</label>
                    <input type="number" id="edit_sort_order" name="sort_order" class="form-control" min="0">
                </div>
            </div>

            <!-- Current Image & Change Image -->
            <div style="margin-bottom: 14px; padding: 12px; background: #F8FAFC; border-radius: 8px; border: 1px solid #E2E8F0;">
                <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 6px;">Current Image:</label>
                <div style="width: 100%; height: 110px; border-radius: 6px; overflow: hidden; background: #000; margin-bottom: 10px; border: 1px solid #CBD5E1;">
                    <img id="edit_current_img" src="" style="width: 100%; height: 100%; object-fit: cover; display: block;" alt="Current Banner">
                </div>
                
                <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 5px;">
                    Change Image <small style="color: #64748B; font-weight: normal;">(Leave empty to keep current image)</small>
                </label>
                <input type="file" name="image" accept="image/*" class="form-control" onchange="previewEditBanner(this)">
            </div>

            <div style="margin-bottom: 20px; padding: 10px 12px; background: #FFF7ED; border-radius: 8px; border: 1px solid #FFEDD5;">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 13px; font-weight: 700; margin: 0; color: #9A3412;">
                    <input type="checkbox" id="edit_is_active" name="is_active" value="1" style="width: 17px; height: 17px; accent-color: var(--admin-primary); cursor: pointer;">
                    <span>Active (Visible on frontend)</span>
                </label>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeEditBannerModal()" style="padding: 9px 18px; border-radius: 8px; border: 1px solid #CBD5E1; background: #FFFFFF; font-weight: 700; font-size: 12.5px; cursor: pointer; color: #475569;">
                    Cancel
                </button>
                <button type="submit" class="btn-orange-add" style="padding: 9px 22px;">
                    <i class="fa-solid fa-check"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    @keyframes modalPop {
        from { transform: scale(0.95); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
    .status-toggle-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
</style>
@endsection

@push('scripts')
<script>
    // Preview image on upload form
    function previewAddBanner(input) {
        const container = document.getElementById('addBannerPreviewContainer');
        const img = document.getElementById('addBannerPreviewImg');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                container.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            container.style.display = 'none';
        }
    }

    // Preview image on edit form
    function previewEditBanner(input) {
        const img = document.getElementById('edit_current_img');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Open Edit Banner Modal
    function openEditBannerModal(data) {
        const modal = document.getElementById('editBannerModal');
        const form = document.getElementById('editBannerForm');
        
        form.action = data.update_url;
        document.getElementById('edit_title').value = data.title;
        document.getElementById('edit_subtitle').value = data.subtitle;
        document.getElementById('edit_link').value = data.link;
        document.getElementById('edit_position').value = data.position;
        document.getElementById('edit_sort_order').value = data.sort_order;
        document.getElementById('edit_is_active').checked = !!data.is_active;
        document.getElementById('edit_current_img').src = data.image_url;

        modal.style.display = 'flex';
    }

    // Close Edit Banner Modal
    function closeEditBannerModal() {
        document.getElementById('editBannerModal').style.display = 'none';
    }

    // Close on backdrop click
    document.getElementById('editBannerModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditBannerModal();
        }
    });

    // Toggle Banner Active/Inactive status via AJAX
    function toggleBannerStatus(bannerId, btn) {
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
        btn.disabled = true;

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(`/admin/banners/${bannerId}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network error');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                if (data.is_active) {
                    btn.className = 'status-toggle-btn badge-status badge-success';
                    btn.innerHTML = '<i class="fa-solid fa-check"></i> <span class="status-text">Active</span>';
                } else {
                    btn.className = 'status-toggle-btn badge-status badge-neutral';
                    btn.innerHTML = '<i class="fa-solid fa-xmark"></i> <span class="status-text">Inactive</span>';
                }
            } else {
                btn.innerHTML = originalHtml;
                alert('Could not update status');
            }
        })
        .catch(err => {
            console.error(err);
            btn.innerHTML = originalHtml;
            alert('Error toggling status. Please try again.');
        })
        .finally(() => {
            btn.disabled = false;
        });
    }
</script>
@endpush
