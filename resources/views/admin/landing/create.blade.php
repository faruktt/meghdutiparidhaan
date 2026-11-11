@extends('layouts.admin')

@section('title', 'Create Landing Page')

@section('content')
<div class="card-header-flex">
    <div>
        <a href="{{ route('admin.landing.index') }}" style="font-size: 13px; color: var(--text-muted); text-decoration: none;">
            <i class="fa-solid fa-arrow-left"></i> Back to Landing Pages
        </a>
        <h1 style="font-size: 22px; font-weight: 800; color: var(--text-dark); margin-top: 4px;">Create New Landing Page</h1>
    </div>
</div>

<form action="{{ route('admin.landing.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; @media(max-width: 900px){grid-template-columns: 1fr;}">
        <!-- Main Form -->
        <div>
            <div class="card">
                <h3 class="card-title" style="margin-bottom: 16px;">Campaign Details</h3>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px;">Campaign / Landing Page Title <span style="color: red;">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" class="form-control" placeholder="e.g. Special Eid Festive Combo Offer" required>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px;">Custom Slug (URL path)</label>
                    <input type="text" name="slug" value="{{ old('slug') }}" class="form-control" placeholder="e.g. eid-festive-offer (leave blank to auto-generate)">
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px;">Top Urgency Banner Text (Optional)</label>
                    <input type="text" name="urgency_text" value="{{ old('urgency_text', '১০ মিনিটের মধ্যে অর্ডার করলেই পাবেন ফ্রি ডেলিভারি') }}" class="form-control" placeholder="e.g. ১০ মিনিটের মধ্যে অর্ডার করলেই পাবেন ফ্রি ডেলিভারি">
                    <span style="font-size: 11.5px; color: var(--text-muted);">Leave empty if you do not want an urgency countdown banner.</span>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px;">Short Promotional Subtitle / Summary</label>
                    <textarea name="description" rows="2" class="form-control" placeholder="Short description or offer highlights...">{{ old('description') }}</textarea>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px;">Full Landing Page Details & Content</label>
                    <textarea name="details" rows="6" class="form-control" placeholder="Write complete details, product specifications, benefits, usage instructions, terms, etc. (Can write in Bangla or English)">{{ old('details') }}</textarea>
                    <span style="font-size: 11.5px; color: var(--text-muted);">This text will be dynamically displayed on the landing page exactly as written.</span>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px;">Feature Badge 1</label>
                        <input type="text" name="feature_badge_1" value="{{ old('feature_badge_1', 'ফ্রি ডেলিভারি') }}" class="form-control" placeholder="e.g. ফ্রি ডেলিভারি">
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px;">Feature Badge 2</label>
                        <input type="text" name="feature_badge_2" value="{{ old('feature_badge_2', '২৪/৭ সাপোর্ট') }}" class="form-control" placeholder="e.g. ২৪/৭ সাপোর্ট">
                    </div>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px;">Campaign Banner Image</label>
                    <input type="file" name="banner_image" accept="image/*" class="form-control">
                </div>
            </div>

            <!-- Multi-Product Selector -->
            <div class="card">
                <div class="card-header-flex">
                    <h3 class="card-title">Select Campaign Products</h3>
                    <span style="font-size: 12px; color: var(--text-muted);">Choose 1 or more products for customer to order</span>
                </div>

                <input type="text" id="landingProductSearch" class="form-control" style="margin-bottom: 14px;" placeholder="Search products..." oninput="filterLandingProducts()">

                <div style="max-height: 340px; overflow-y: auto; display: flex; flex-direction: column; gap: 8px;" id="landingProductsList">
                    @foreach($products as $product)
                        <label class="landing-product-choice" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; border: 1px solid var(--admin-border); border-radius: 8px; cursor: pointer;" data-name="{{ strtolower($product->name) }}">
                            <input type="checkbox" name="products[]" value="{{ $product->id }}">
                            <img src="{{ $product->thumbnail_url }}" style="width: 44px; height: 48px; object-fit: cover; border-radius: 6px;" alt="">
                            <div style="flex: 1;">
                                <div style="font-weight: 700; font-size: 13.5px;">{{ $product->name }}</div>
                                <div style="font-size: 12px; color: var(--admin-primary); font-weight: 700;">৳{{ number_format($product->current_price) }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sidebar Config -->
        <div>
            <div class="card">
                <h3 class="card-title" style="margin-bottom: 16px;">SEO & Visibility</h3>

                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px;">Meta Title</label>
                    <input type="text" name="meta_title" class="form-control" placeholder="Meta title for Google/Social">
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px;">Meta Description</label>
                    <textarea name="meta_description" rows="3" class="form-control" placeholder="Meta description..."></textarea>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 600; cursor: pointer;">
                        <input type="checkbox" name="is_active" value="1" checked> Active / Live Online
                    </label>
                </div>

                <button type="submit" class="btn-admin-primary" style="width: 100%; justify-content: center; padding: 14px; font-size: 15px;">
                    <i class="fa-solid fa-wand-magic-sparkles"></i> Publish Landing Page
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    function filterLandingProducts() {
        let q = document.getElementById('landingProductSearch').value.toLowerCase();
        document.querySelectorAll('.landing-product-choice').forEach(el => {
            let name = el.getAttribute('data-name');
            if (name.includes(q)) {
                el.style.display = 'flex';
            } else {
                el.style.display = 'none';
            }
        });
    }
</script>
@endpush
