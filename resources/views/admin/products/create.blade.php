@extends('layouts.admin')

@section('title', 'Add New Product')
@section('breadcrumb', 'Add Product')

@section('content')
<div class="card-header-flex">
    <div>
        <a href="{{ route('admin.products.index') }}" style="font-size: 13px; color: var(--text-muted); text-decoration: none;">
            <i class="fa-solid fa-arrow-left"></i> Back to Products
        </a>
        <h1 style="font-size: 20px; font-weight: 800; color: #111827; margin-top: 4px;">Add New Product</h1>
    </div>
</div>

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; @media(max-width: 900px){grid-template-columns: 1fr;}">
        <!-- Main Details -->
        <div>
            <div class="card">
                <h3 class="card-title" style="margin-bottom: 16px;">Product Information</h3>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 12.5px; font-weight: 700; margin-bottom: 6px;">Product Name <span style="color: red;">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="e.g. Classic White Cuban Collar Shirt" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-size: 12.5px; font-weight: 700; margin-bottom: 6px;">SKU / Code</label>
                        <input type="text" name="sku" value="{{ old('sku') }}" class="form-control" placeholder="Auto SKU if empty">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12.5px; font-weight: 700; margin-bottom: 6px;">Main Category (মেইন)</label>
                        <select name="category_id" id="categorySelect" class="form-control" onchange="filterSubcategories()">
                            <option value="">Select Category</option>
                            @foreach($parentCategories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 12.5px; font-weight: 700; margin-bottom: 6px;">Subcategory (সাবক্যাটাগরি)</label>
                        <select name="subcategory_id" id="subcategorySelect" class="form-control">
                            <option value="">Select Subcategory (ঐচ্ছিক)</option>
                            @foreach($subcategories as $sub)
                                <option value="{{ $sub->id }}" data-parent="{{ $sub->parent_id }}" {{ old('subcategory_id') == $sub->id ? 'selected' : '' }}>
                                    {{ $sub->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-size: 12.5px; font-weight: 700; margin-bottom: 6px;">Regular Price (৳) <span style="color: red;">*</span></label>
                        <input type="number" step="0.01" name="regular_price" value="{{ old('regular_price') }}" class="form-control" placeholder="1500" required>
                    </div>
                    <div>
                        <label style="display: block; font-size: 12.5px; font-weight: 700; margin-bottom: 6px;">Sale / Offer Price (৳)</label>
                        <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price') }}" class="form-control" placeholder="1200">
                    </div>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 12.5px; font-weight: 700; margin-bottom: 6px;">Short Highlight</label>
                    <input type="text" name="short_description" value="{{ old('short_description') }}" class="form-control" placeholder="Brief 1-line feature">
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 12.5px; font-weight: 700; margin-bottom: 6px;">Detailed Description</label>
                    <textarea name="description" rows="5" class="form-control" placeholder="Describe the fit, styling tips, material..."></textarea>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 12.5px; font-weight: 700; margin-bottom: 6px;">Fabric & Care Instructions</label>
                    <input type="text" name="more_information" class="form-control" placeholder="Fabric: 100% Cotton | Wash in cold water">
                </div>
            </div>

            <!-- Product Variants & Inventory -->
            <div class="card">
                <div class="card-header-flex">
                    <div>
                        <h3 class="card-title">Sizes & Color Variants (সাইজ ও কালার)</h3>
                        <p style="font-size: 12px; color: var(--text-muted); margin: 2px 0 0;">
                            প্রোডাক্টের বিভিন্ন সাইজ (S, M, L, XL), কালার এবং তাদের স্টক নির্ধারণ করুন।
                        </p>
                    </div>
                    <button type="button" class="btn-orange-add" onclick="addVariantRow()" style="font-size: 12px; padding: 6px 12px; height: 32px;">
                        <i class="fa-solid fa-plus"></i> Add Variant
                    </button>
                </div>

                <div id="variantsContainer" style="display: flex; flex-direction: column; gap: 10px; margin-top: 14px;">
                    <div class="variant-row" style="display: grid; grid-template-columns: 1fr 1fr 0.8fr 0.8fr 1.4fr 34px; gap: 8px; align-items: center; background: #F8FAFC; padding: 10px; border-radius: 6px; border: 1px solid #E2E8F0;">
                        <div>
                            <label style="font-size: 10.5px; font-weight: 700; color: #64748B; display: block; margin-bottom: 2px;">SIZE</label>
                            <select name="variants[0][size_id]" class="form-control" style="background: white;">
                                <option value="">Select Size</option>
                                @foreach($sizes as $sz)
                                    <option value="{{ $sz->id }}">{{ $sz->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label style="font-size: 10.5px; font-weight: 700; color: #64748B; display: block; margin-bottom: 2px;">COLOR</label>
                            <select name="variants[0][color_id]" class="form-control" style="background: white;">
                                <option value="">Select Color</option>
                                @foreach($colors as $cl)
                                    <option value="{{ $cl->id }}">{{ $cl->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label style="font-size: 10.5px; font-weight: 700; color: #64748B; display: block; margin-bottom: 2px;">STOCK</label>
                            <input type="number" name="variants[0][stock]" class="form-control" placeholder="Qty" value="20" min="0" style="background: white;">
                        </div>
                        <div>
                            <label style="font-size: 10.5px; font-weight: 700; color: #64748B; display: block; margin-bottom: 2px;">+/- PRICE</label>
                            <input type="number" step="0.01" name="variants[0][price_adjustment]" class="form-control" placeholder="0" value="0" style="background: white;">
                        </div>
                        <div>
                            <label style="font-size: 10.5px; font-weight: 700; color: #64748B; display: block; margin-bottom: 2px;">IMAGE</label>
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <img src="" alt="" style="width: 32px; height: 32px; object-fit: cover; border-radius: 4px; border: 1px solid #CBD5E1; display: none; flex-shrink: 0;" class="var-preview-img">
                                <input type="file" name="variants[0][image]" accept="image/*" class="form-control" style="background: white; padding: 4px; font-size: 11px; height: 32px;" onchange="previewVariantImg(this)">
                            </div>
                        </div>
                        <div style="padding-top: 14px; text-align: center;">
                            <button type="button" onclick="this.closest('.variant-row').remove()" style="background: #FEE2E2; border: none; color: #DC2626; width: 28px; height: 28px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 12px;" title="Remove">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Options: Images & Status -->
        <div>
            <!-- Main Thumbnail -->
            <div class="card">
                <h3 class="card-title" style="margin-bottom: 8px;">Main Thumbnail <span style="color: red;">*</span></h3>
                <p style="font-size: 11.5px; color: var(--text-muted); margin-bottom: 12px;">প্রোডাক্টের মূল ছবি</p>
                <input type="file" name="thumbnail" accept="image/*" class="form-control" required onchange="previewThumbnail(this)">

                <div id="thumbPreviewBox" style="display: none; margin-top: 12px; border-radius: 6px; overflow: hidden; border: 1px dashed #CBD5E1; background: #000; text-align: center;">
                    <img id="thumbPreviewImg" src="" style="width: 100%; height: 180px; object-fit: cover; display: block;" alt="Thumbnail Preview">
                </div>
            </div>

            <!-- Gallery Images -->
            <div class="card">
                <h3 class="card-title" style="margin-bottom: 8px;">Gallery Images (গ্যালারি ছবি)</h3>
                <p style="font-size: 11.5px; color: var(--text-muted); margin-bottom: 12px;">একসাথে একাধিক ছবি সিলেক্ট করুন</p>
                <input type="file" name="images[]" id="galleryInput" accept="image/*" multiple class="form-control" onchange="previewGallery(this)">

                <!-- Live Gallery Preview Container -->
                <div id="galleryPreviewBox" style="display: none; margin-top: 14px;">
                    <div style="font-size: 11.5px; font-weight: 700; color: #475569; margin-bottom: 8px;" id="galleryCountText">Selected Photos (0):</div>
                    <div id="galleryPreviewGrid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px;"></div>
                </div>
            </div>

            <!-- Publish Options -->
            <div class="card">
                <h3 class="card-title" style="margin-bottom: 14px;">Publish Status</h3>
                
                <div style="margin-bottom: 12px; padding: 10px; background: #F8FAFC; border-radius: 6px; border: 1px solid #E2E8F0;">
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; cursor: pointer; margin: 0;">
                        <input type="checkbox" name="is_active" value="1" checked style="accent-color: var(--admin-primary); width: 16px; height: 16px;"> Active in Store
                    </label>
                </div>

                <div style="margin-bottom: 12px; padding: 10px; background: #F8FAFC; border-radius: 6px; border: 1px solid #E2E8F0;">
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; cursor: pointer; margin: 0;">
                        <input type="checkbox" name="is_featured" value="1" style="accent-color: var(--admin-primary); width: 16px; height: 16px;"> Show on Featured List
                    </label>
                </div>

                <!-- Frontend Sections Selection -->
                <div style="margin-bottom: 12px; padding: 12px; background: #F0FDF4; border-radius: 6px; border: 1px solid #BBF7D0;">
                    <div style="font-size: 11px; font-weight: 800; color: #166534; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px;">
                        <i class="fa-solid fa-layer-group"></i> Frontend Sections
                    </div>
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 700; color: #047857; cursor: pointer; margin-bottom: 8px;">
                        <input type="checkbox" name="is_new_arrival" value="1" {{ old('is_new_arrival') ? 'checked' : '' }} style="accent-color: #059669; width: 17px; height: 17px;">
                        <span><i class="fa-solid fa-sparkles" style="color: #059669; margin-right: 4px;"></i> New Arrival Section</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 700; color: #B45309; cursor: pointer; margin: 0;">
                        <input type="checkbox" name="is_top_selling" value="1" {{ old('is_top_selling') ? 'checked' : '' }} style="accent-color: #D97706; width: 17px; height: 17px;">
                        <span><i class="fa-solid fa-fire" style="color: #D97706; margin-right: 4px;"></i> Top Selling Section</span>
                    </label>
                </div>

                <button type="submit" class="btn-orange-add" style="width: 100%; justify-content: center; height: 42px; font-size: 14px;">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Save & Publish Product
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    // Subcategory dynamic filtering based on parent category
    function filterSubcategories() {
        const catId = document.getElementById('categorySelect').value;
        const subSelect = document.getElementById('subcategorySelect');
        const options = subSelect.querySelectorAll('option');

        let matchedCount = 0;
        options.forEach(opt => {
            if (!opt.value) {
                opt.style.display = '';
                return;
            }
            const parent = opt.getAttribute('data-parent');
            if (!catId || parent === catId) {
                opt.style.display = '';
                matchedCount++;
            } else {
                opt.style.display = 'none';
            }
        });

        // Reset if current selection is now hidden
        const selectedOpt = subSelect.options[subSelect.selectedIndex];
        if (selectedOpt && selectedOpt.style.display === 'none') {
            subSelect.value = '';
        }
    }

    // Initialize subcategories filter on load
    document.addEventListener('DOMContentLoaded', filterSubcategories);

    // Live Thumbnail preview
    function previewThumbnail(input) {
        const box = document.getElementById('thumbPreviewBox');
        const img = document.getElementById('thumbPreviewImg');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                box.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            box.style.display = 'none';
        }
    }

    // Live Gallery images preview
    function previewGallery(input) {
        const box = document.getElementById('galleryPreviewBox');
        const grid = document.getElementById('galleryPreviewGrid');
        const countText = document.getElementById('galleryCountText');
        grid.innerHTML = '';

        if (input.files && input.files.length > 0) {
            box.style.display = 'block';
            countText.innerText = `Selected Photos (${input.files.length}):`;

            Array.from(input.files).forEach((file, idx) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const item = document.createElement('div');
                    item.style.cssText = 'position: relative; height: 75px; border-radius: 6px; overflow: hidden; border: 1px solid #CBD5E1; background: #000;';
                    item.innerHTML = `<img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover; display: block;" alt="Gallery ${idx + 1}">`;
                    grid.appendChild(item);
                };
                reader.readAsDataURL(file);
            });
        } else {
            box.style.display = 'none';
        }
    }

    function previewVariantImg(input) {
        if (input.files && input.files[0]) {
            const preview = input.closest('div').querySelector('.var-preview-img');
            if (preview) {
                preview.src = URL.createObjectURL(input.files[0]);
                preview.style.display = 'block';
            }
        }
    }

    // Add Variant Row
    let variantIndex = 1;
    function addVariantRow() {
        const container = document.getElementById('variantsContainer');
        const row = document.createElement('div');
        row.className = 'variant-row';
        row.style.cssText = 'display: grid; grid-template-columns: 1fr 1fr 0.8fr 0.8fr 1.4fr 34px; gap: 8px; align-items: center; background: #F8FAFC; padding: 10px; border-radius: 6px; border: 1px solid #E2E8F0;';
        row.innerHTML = `
            <div>
                <label style="font-size: 10.5px; font-weight: 700; color: #64748B; display: block; margin-bottom: 2px;">SIZE</label>
                <select name="variants[${variantIndex}][size_id]" class="form-control" style="background: white;">
                    <option value="">Select Size</option>
                    @foreach($sizes as $sz)
                        <option value="{{ $sz->id }}">{{ $sz->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="font-size: 10.5px; font-weight: 700; color: #64748B; display: block; margin-bottom: 2px;">COLOR</label>
                <select name="variants[${variantIndex}][color_id]" class="form-control" style="background: white;">
                    <option value="">Select Color</option>
                    @foreach($colors as $cl)
                        <option value="{{ $cl->id }}">{{ $cl->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="font-size: 10.5px; font-weight: 700; color: #64748B; display: block; margin-bottom: 2px;">STOCK</label>
                <input type="number" name="variants[${variantIndex}][stock]" class="form-control" placeholder="Qty" value="20" min="0" style="background: white;">
            </div>
            <div>
                <label style="font-size: 10.5px; font-weight: 700; color: #64748B; display: block; margin-bottom: 2px;">+/- PRICE</label>
                <input type="number" step="0.01" name="variants[${variantIndex}][price_adjustment]" class="form-control" placeholder="0" value="0" style="background: white;">
            </div>
            <div>
                <label style="font-size: 10.5px; font-weight: 700; color: #64748B; display: block; margin-bottom: 2px;">IMAGE</label>
                <div style="display: flex; align-items: center; gap: 6px;">
                    <img src="" alt="" style="width: 32px; height: 32px; object-fit: cover; border-radius: 4px; border: 1px solid #CBD5E1; display: none; flex-shrink: 0;" class="var-preview-img">
                    <input type="file" name="variants[${variantIndex}][image]" accept="image/*" class="form-control" style="background: white; padding: 4px; font-size: 11px; height: 32px;" onchange="previewVariantImg(this)">
                </div>
            </div>
            <div style="padding-top: 14px; text-align: center;">
                <button type="button" onclick="this.closest('.variant-row').remove()" style="background: #FEE2E2; border: none; color: #DC2626; width: 28px; height: 28px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 12px;" title="Remove">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        `;
        container.appendChild(row);
        variantIndex++;
    }
</script>
@endpush
