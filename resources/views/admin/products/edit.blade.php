@extends('layouts.admin')

@section('title', 'Edit Product: ' . $product->name)
@section('breadcrumb', 'Edit Product')

@section('content')
<div class="card-header-flex">
    <div>
        <a href="{{ route('admin.products.index') }}" style="font-size: 13px; color: var(--text-muted); text-decoration: none;">
            <i class="fa-solid fa-arrow-left"></i> Back to Products
        </a>
        <h1 style="font-size: 20px; font-weight: 800; color: #111827; margin-top: 4px;">Edit Product: {{ $product->name }}</h1>
    </div>
</div>

<form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; @media(max-width: 900px){grid-template-columns: 1fr;}">
        <!-- Main Details -->
        <div>
            <div class="card">
                <h3 class="card-title" style="margin-bottom: 16px;">Product Information</h3>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 12.5px; font-weight: 700; margin-bottom: 6px;">Product Name <span style="color: red;">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-control" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-size: 12.5px; font-weight: 700; margin-bottom: 6px;">SKU / Code</label>
                        <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="form-control">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12.5px; font-weight: 700; margin-bottom: 6px;">Main Category (মেইন)</label>
                        <select name="category_id" id="categorySelect" class="form-control" onchange="filterSubcategories()">
                            <option value="">Select Category</option>
                            @foreach($parentCategories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 12.5px; font-weight: 700; margin-bottom: 6px;">Subcategory (সাবক্যাটাগরি)</label>
                        <select name="subcategory_id" id="subcategorySelect" class="form-control">
                            <option value="">Select Subcategory (ঐচ্ছিক)</option>
                            @foreach($subcategories as $sub)
                                <option value="{{ $sub->id }}" data-parent="{{ $sub->parent_id }}" {{ old('subcategory_id', $product->subcategory_id) == $sub->id ? 'selected' : '' }}>
                                    {{ $sub->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-size: 12.5px; font-weight: 700; margin-bottom: 6px;">Regular Price (৳) <span style="color: red;">*</span></label>
                        <input type="number" step="0.01" name="regular_price" value="{{ old('regular_price', $product->regular_price) }}" class="form-control" required>
                    </div>
                    <div>
                        <label style="display: block; font-size: 12.5px; font-weight: 700; margin-bottom: 6px;">Sale / Offer Price (৳)</label>
                        <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" class="form-control">
                    </div>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 12.5px; font-weight: 700; margin-bottom: 6px;">Short Highlight</label>
                    <input type="text" name="short_description" value="{{ old('short_description', $product->short_description) }}" class="form-control">
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 12.5px; font-weight: 700; margin-bottom: 6px;">Detailed Description</label>
                    <textarea name="description" rows="5" class="form-control">{{ old('description', $product->description) }}</textarea>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 12.5px; font-weight: 700; margin-bottom: 6px;">Fabric & Care Instructions</label>
                    <input type="text" name="more_information" value="{{ old('more_information', $product->more_information) }}" class="form-control">
                </div>
            </div>

            <!-- Product Variants & Inventory (Sizes & Colors) -->
            <div class="card">
                <div class="card-header-flex">
                    <div>
                        <h3 class="card-title">Sizes & Color Variants (সাইজ ও কালার অপশন)</h3>
                        <p style="font-size: 12px; color: var(--text-muted); margin: 2px 0 0;">
                            সাইজ ও কালার ভ্যারিয়েন্ট যোগ বা এডিট করুন এবং স্টক আপডেট করুন।
                        </p>
                    </div>
                    <button type="button" class="btn-orange-add" onclick="addVariantRow()" style="font-size: 12px; padding: 6px 12px; height: 32px;">
                        <i class="fa-solid fa-plus"></i> Add Variant
                    </button>
                </div>

                <div id="variantsContainer" style="display: flex; flex-direction: column; gap: 10px; margin-top: 14px;">
                    @forelse($product->variants as $index => $v)
                        <div class="variant-row" style="display: grid; grid-template-columns: 1fr 1fr 0.8fr 0.8fr 1.4fr 34px; gap: 8px; align-items: center; background: #F8FAFC; padding: 10px; border-radius: 6px; border: 1px solid #E2E8F0;">
                            <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $v->id }}">
                            <div>
                                <label style="font-size: 10.5px; font-weight: 700; color: #64748B; display: block; margin-bottom: 2px;">SIZE</label>
                                <select name="variants[{{ $index }}][size_id]" class="form-control" style="background: white;">
                                    <option value="">Select Size</option>
                                    @foreach($sizes as $sz)
                                        <option value="{{ $sz->id }}" {{ $v->size_id == $sz->id ? 'selected' : '' }}>{{ $sz->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label style="font-size: 10.5px; font-weight: 700; color: #64748B; display: block; margin-bottom: 2px;">COLOR</label>
                                <select name="variants[{{ $index }}][color_id]" class="form-control" style="background: white;">
                                    <option value="">Select Color</option>
                                    @foreach($colors as $cl)
                                        <option value="{{ $cl->id }}" {{ $v->color_id == $cl->id ? 'selected' : '' }}>{{ $cl->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label style="font-size: 10.5px; font-weight: 700; color: #64748B; display: block; margin-bottom: 2px;">STOCK</label>
                                <input type="number" name="variants[{{ $index }}][stock]" class="form-control" placeholder="Qty" value="{{ $v->stock }}" min="0" style="background: white;">
                            </div>
                            <div>
                                <label style="font-size: 10.5px; font-weight: 700; color: #64748B; display: block; margin-bottom: 2px;">+/- PRICE</label>
                                <input type="number" step="0.01" name="variants[{{ $index }}][price_adjustment]" class="form-control" placeholder="0" value="{{ $v->price_adjustment }}" style="background: white;">
                            </div>
                            <div>
                                <label style="font-size: 10.5px; font-weight: 700; color: #64748B; display: block; margin-bottom: 2px;">IMAGE</label>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    @if($v->image_url)
                                        <img src="{{ $v->image_url }}" alt="Variant" style="width: 32px; height: 32px; object-fit: cover; border-radius: 4px; border: 1px solid #CBD5E1; flex-shrink: 0;" class="var-preview-img">
                                    @else
                                        <img src="" alt="" style="width: 32px; height: 32px; object-fit: cover; border-radius: 4px; border: 1px solid #CBD5E1; display: none; flex-shrink: 0;" class="var-preview-img">
                                    @endif
                                    <input type="file" name="variants[{{ $index }}][image]" accept="image/*" class="form-control" style="background: white; padding: 4px; font-size: 11px; height: 32px;" onchange="previewVariantImg(this)">
                                </div>
                            </div>
                            <div style="padding-top: 14px; text-align: center;">
                                <button type="button" onclick="this.closest('.variant-row').remove()" style="background: #FEE2E2; border: none; color: #DC2626; width: 28px; height: 28px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 12px;" title="Remove">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                        </div>
                    @empty
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
                    @endforelse
                </div>
            </div>

            <!-- Existing Gallery Images -->
            <div class="card">
                <h3 class="card-title" style="margin-bottom: 12px;">Existing Gallery Photos (আপলোডকৃত গ্যালারি ছবি)</h3>
                @if($product->images->count() > 0)
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(90px, 1fr)); gap: 12px;">
                        @foreach($product->images as $img)
                            <div id="img-box-{{ $img->id }}" style="position: relative; height: 95px; border-radius: 6px; overflow: hidden; border: 1px solid #CBD5E1; background: #000;">
                                <img src="{{ $img->image_url }}" style="width: 100%; height: 100%; object-fit: cover; display: block;" alt="Gallery Photo" onerror="this.src='https://placehold.co/100x100?text=Error';">
                                <button type="button" onclick="deleteProductImage({{ $img->id }})" style="position: absolute; top: 4px; right: 4px; background: rgba(220, 38, 38, 0.9); color: white; border: none; border-radius: 50%; width: 22px; height: 22px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 11px;" title="Delete this image">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p style="font-size: 12px; color: #94A3B8; margin: 0;">কোন অতিরিক্ত গ্যালারি ছবি আপলোড করা নেই। নিচে থেকে নতুন ছবি যোগ করতে পারেন।</p>
                @endif
            </div>
        </div>

        <!-- Sidebar Options -->
        <div>
            <!-- Main Thumbnail -->
            <div class="card">
                <h3 class="card-title" style="margin-bottom: 8px;">Main Thumbnail</h3>
                @if($product->thumbnail)
                    <div style="margin-bottom: 10px; border-radius: 6px; overflow: hidden; border: 1px solid #E2E8F0; background: #000;">
                        <img src="{{ $product->thumbnail_url }}" id="currentThumbImg" style="width: 100%; height: 180px; object-fit: cover; display: block;" alt="Thumbnail">
                    </div>
                @endif
                <input type="file" name="thumbnail" accept="image/*" class="form-control" onchange="previewThumbnail(this)">
                <small style="color: var(--text-muted); display: block; margin-top: 4px;">নতুন ছবি সিলেক্ট করলে পূর্বেরটি পরিবর্তন হবে</small>
            </div>

            <!-- Add More Gallery Photos -->
            <div class="card">
                <h3 class="card-title" style="margin-bottom: 8px;">Add New Gallery Photos</h3>
                <p style="font-size: 11.5px; color: var(--text-muted); margin-bottom: 12px;">গ্যালারিতে যুক্ত করতে ছবি সিলেক্ট করুন</p>
                <input type="file" name="images[]" accept="image/*" multiple class="form-control" onchange="previewGallery(this)">

                <!-- Live Gallery Preview Container -->
                <div id="galleryPreviewBox" style="display: none; margin-top: 14px;">
                    <div style="font-size: 11.5px; font-weight: 700; color: #475569; margin-bottom: 8px;" id="galleryCountText">Selected Photos:</div>
                    <div id="galleryPreviewGrid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px;"></div>
                </div>
            </div>

            <!-- Publish Options -->
            <div class="card">
                <h3 class="card-title" style="margin-bottom: 14px;">Publish Status</h3>
                
                <div style="margin-bottom: 12px; padding: 10px; background: #F8FAFC; border-radius: 6px; border: 1px solid #E2E8F0;">
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; cursor: pointer; margin: 0;">
                        <input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }} style="accent-color: var(--admin-primary); width: 16px; height: 16px;"> Active in Store
                    </label>
                </div>

                <div style="margin-bottom: 12px; padding: 10px; background: #F8FAFC; border-radius: 6px; border: 1px solid #E2E8F0;">
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; cursor: pointer; margin: 0;">
                        <input type="checkbox" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }} style="accent-color: var(--admin-primary); width: 16px; height: 16px;"> Show on Featured List
                    </label>
                </div>

                <!-- Frontend Sections Selection -->
                <div style="margin-bottom: 12px; padding: 12px; background: #F0FDF4; border-radius: 6px; border: 1px solid #BBF7D0;">
                    <div style="font-size: 11px; font-weight: 800; color: #166534; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px;">
                        <i class="fa-solid fa-layer-group"></i> Frontend Sections
                    </div>
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 700; color: #047857; cursor: pointer; margin-bottom: 8px;">
                        <input type="checkbox" name="is_new_arrival" value="1" {{ $product->is_new_arrival ? 'checked' : '' }} style="accent-color: #059669; width: 17px; height: 17px;">
                        <span><i class="fa-solid fa-sparkles" style="color: #059669; margin-right: 4px;"></i> New Arrival Section</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 700; color: #B45309; cursor: pointer; margin: 0;">
                        <input type="checkbox" name="is_top_selling" value="1" {{ $product->is_top_selling ? 'checked' : '' }} style="accent-color: #D97706; width: 17px; height: 17px;">
                        <span><i class="fa-solid fa-fire" style="color: #D97706; margin-right: 4px;"></i> Top Selling Section</span>
                    </label>
                </div>

                <button type="submit" class="btn-orange-add" style="width: 100%; justify-content: center; height: 42px; font-size: 14px;">
                    <i class="fa-solid fa-check"></i> Update Product
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

        options.forEach(opt => {
            if (!opt.value) {
                opt.style.display = '';
                return;
            }
            const parent = opt.getAttribute('data-parent');
            if (!catId || parent === catId) {
                opt.style.display = '';
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
        const currentImg = document.getElementById('currentThumbImg');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if (currentImg) {
                    currentImg.src = e.target.result;
                }
            };
            reader.readAsDataURL(input.files[0]);
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
            countText.innerText = `New Selected Photos (${input.files.length}):`;

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

    // Delete existing gallery image via AJAX
    function deleteProductImage(imageId) {
        if (!confirm('Are you sure you want to delete this gallery photo?')) return;
        fetch(`/admin/products/images/${imageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        }).then(r => r.json()).then(res => {
            if (res.success) {
                const box = document.getElementById(`img-box-${imageId}`);
                if (box) box.remove();
            } else {
                alert('Could not delete image: ' + (res.message || 'Error'));
            }
        }).catch(err => {
            alert('Error deleting image.');
        });
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
    let variantIndex = {{ $product->variants->count() + 10 }};
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
