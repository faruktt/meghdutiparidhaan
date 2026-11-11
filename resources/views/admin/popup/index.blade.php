@extends('layouts.admin')

@section('title', 'Popup Banner')
@section('breadcrumb', 'Popup Banner')

@section('content')
<div class="card-header-flex" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <div>
        <h1 style="font-size: 20px; font-weight: 800; color: var(--text-dark); margin: 0;">Popup Banner Settings</h1>
        <p style="font-size: 12.5px; color: #64748B; margin: 2px 0 0;">Configure promotional popup modal for website visitors.</p>
    </div>
    <div>
        <span class="badge-status {{ $popupActive && $popupImageUrl ? 'badge-success' : 'badge-neutral' }}" style="font-size: 12px; padding: 6px 14px;">
            <i class="fa-solid {{ $popupActive && $popupImageUrl ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i>
            {{ $popupActive && $popupImageUrl ? 'পপআপ চালু আছে (Active)' : 'পপআপ বন্ধ আছে (Inactive)' }}
        </span>
    </div>
</div>

<form action="{{ route('admin.popup.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div style="display: grid; grid-template-columns: 1.6fr 1.2fr; gap: 24px;">
        
        <!-- Left: Form Fields -->
        <div>
            <!-- Status Toggle Card -->
            <div class="card" style="border: 1px solid {{ $popupActive ? '#BBF7D0' : '#E2E8F0' }}; background: {{ $popupActive ? '#F0FDF4' : '#F8FAFC' }}; margin-bottom: 20px;">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <h3 style="font-size: 15px; font-weight: 800; color: {{ $popupActive ? '#166534' : '#334155' }}; margin: 0;">
                            <i class="fa-solid fa-power-off"></i> পপআপ ব্যানার স্ট্যাটাস (Enable Popup)
                        </h3>
                        <p style="font-size: 12px; color: {{ $popupActive ? '#15803D' : '#64748B' }}; margin: 4px 0 0;">
                            চালু থাকলে ওয়েবসাইটে ভিজিটর আসলে এই পপআপ ব্যানারটি শো করবে।
                        </p>
                    </div>
                    <label style="position: relative; display: inline-block; width: 50px; height: 26px; cursor: pointer;">
                        <input type="checkbox" name="popup_active" value="1" {{ $popupActive ? 'checked' : '' }} style="opacity: 0; width: 0; height: 0;" id="popupToggle" onchange="togglePopupSwitch(this)">
                        <span id="sliderBg" style="position: absolute; cursor: pointer; inset: 0; background-color: {{ $popupActive ? '#16A34A' : '#CBD5E1' }}; transition: .3s; border-radius: 34px;">
                            <span id="sliderDot" style="position: absolute; height: 20px; width: 20px; left: {{ $popupActive ? '26px' : '3px' }}; bottom: 3px; background-color: white; transition: .3s; border-radius: 50%;"></span>
                        </span>
                    </label>
                </div>
            </div>

            <!-- Upload & Details Card -->
            <div class="card">
                <h3 class="card-title" style="margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-image" style="color: var(--admin-primary);"></i> ব্যানার ছবি ও রিডাইরেক্ট লিংক
                </h3>

                <!-- Image Upload Input -->
                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px;">
                        পপআপ ব্যানার ছবি (Upload Banner Image) <span style="color: #EF4444;">*</span>
                    </label>
                    <input type="file" name="image" id="popupImageInput" class="form-control" accept="image/*" onchange="previewPopupImage(this)">
                    <span style="font-size: 11.5px; color: #64748B; margin-top: 4px; display: block;">
                        সুপারিশকৃত সাইজ: <strong>600x600 px</strong> বা <strong>600x800 px</strong> (JPG, PNG, WebP — সর্বোচ্চ 4MB)
                    </span>
                </div>

                <!-- Target Link -->
                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px;">
                        ক্লিক রিডাইরেক্ট লিংক (Target URL / Link) <span style="color: #EF4444;">*</span>
                    </label>
                    <div style="position: relative;">
                        <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94A3B8; font-size: 13px;">
                            <i class="fa-solid fa-link"></i>
                        </span>
                        <input type="url" name="link" value="{{ old('link', $popupLink) }}" class="form-control" placeholder="https://meghdutiparidhaan.com/shop?category=jamdani-saree" style="padding-left: 34px;">
                    </div>
                    <span style="font-size: 11.5px; color: #64748B; margin-top: 4px; display: block;">
                        পপআপ ব্যানারে ক্লিক করলে ভিজিটরকে এই লিংকে নিয়ে যাওয়া হবে (যেমন অফার পেইজ, নির্দিষ্ট ক্যাটাগরি বা প্রোডাক্ট লিংক)।
                    </span>
                </div>

                <!-- Banner Title / Alt Text -->
                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px;">
                        ব্যানার টাইটেল / বিবরণ (Title / Heading) <small style="color: #94A3B8;">(ঐচ্ছিক)</small>
                    </label>
                    <input type="text" name="title" value="{{ old('title', $popupTitle) }}" class="form-control" placeholder="Special Discount / Exclusive Offer">
                </div>

                <!-- Delay in Seconds -->
                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px;">
                        কত সেকেন্ড পর পপআপ ওপেন হবে (Display Delay)
                    </label>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <input type="number" name="delay" value="{{ old('delay', $popupDelay) }}" min="0" max="30" class="form-control" style="width: 100px;">
                        <span style="font-size: 13px; color: #64748B;">সেকেন্ড (Default: 1 সেকেন্ড)</span>
                    </div>
                </div>

                <button type="submit" class="btn-orange-add" style="height: 42px; font-size: 14px; padding: 0 28px;">
                    <i class="fa-solid fa-cloud-arrow-up"></i> সেটিংস সংরক্ষণ করুন (Save Settings)
                </button>
            </div>
        </div>

        <!-- Right: Current Image & Live Preview -->
        <div>
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                    <h3 class="card-title" style="margin: 0; font-size: 14px;">
                        <i class="fa-solid fa-eye" style="color: #2563EB;"></i> পপআপ প্রিভিউ (Live Preview)
                    </h3>
                    @if($popupImageUrl)
                        <button type="button" onclick="submitRemoveImage()" style="background: none; border: none; color: #EF4444; font-size: 12px; font-weight: 700; cursor: pointer;">
                            <i class="fa-regular fa-trash-can"></i> ছবি মুছুন
                        </button>
                    @endif
                </div>

                <div style="background: #111827; border-radius: 8px; padding: 20px 14px; text-align: center; position: relative;">
                    <!-- Mock Browser Top -->
                    <div style="display: flex; gap: 4px; margin-bottom: 12px; opacity: 0.6;">
                        <div style="width: 8px; height: 8px; border-radius: 50%; background: #EF4444;"></div>
                        <div style="width: 8px; height: 8px; border-radius: 50%; background: #F59E0B;"></div>
                        <div style="width: 8px; height: 8px; border-radius: 50%; background: #10B981;"></div>
                    </div>

                    <!-- Modal Box Simulation -->
                    <div id="previewModalBox" style="background: #FFFFFF; border-radius: 8px; overflow: hidden; position: relative; box-shadow: 0 20px 30px rgba(0,0,0,0.5); max-width: 320px; margin: 0 auto;">
                        <button type="button" style="position: absolute; top: 8px; right: 8px; width: 26px; height: 26px; border-radius: 50%; background: rgba(0,0,0,0.6); color: white; border: none; font-size: 14px; display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 10;">
                            &times;
                        </button>

                        <div id="imagePlaceholder" style="{{ $popupImageUrl ? 'display: none;' : '' }} padding: 50px 20px; color: #94A3B8;">
                            <i class="fa-solid fa-image" style="font-size: 40px; margin-bottom: 8px; opacity: 0.4;"></i>
                            <div style="font-size: 12px; font-weight: 600;">কোন ছবি আপলোড করা হয়নি</div>
                        </div>

                        <img id="imagePreviewTarget" src="{{ $popupImageUrl ?: '' }}" alt="Popup Preview" style="{{ $popupImageUrl ? '' : 'display: none;' }} width: 100%; height: auto; max-height: 380px; object-fit: cover; display: {{ $popupImageUrl ? 'block' : 'none' }};">

                        @if($popupLink)
                            <div style="padding: 8px; background: #F8FAFC; border-top: 1px solid #E2E8F0; font-size: 11px; color: #2563EB; word-break: break-all;">
                                <i class="fa-solid fa-link"></i> {{ \Illuminate\Support\Str::limit($popupLink, 40) }}
                            </div>
                        @endif
                    </div>
                </div>

                <div style="margin-top: 14px; padding: 12px; background: #EFF6FF; border-radius: 6px; font-size: 12px; color: #1E40AF; line-height: 1.5;">
                    <i class="fa-solid fa-circle-info" style="margin-right: 4px;"></i> 
                    ভিজিটর যখন ওয়েবসাইটে ঢুকবে, ব্যানারটি প্রদর্শিত হবে এবং ব্যানারে ক্লিক করলে সাথে সাথে আপনার দেওয়া নির্দিষ্ট লিংকে নিয়ে যাবে।
                </div>
            </div>
        </div>

    </div>
</form>

@if($popupImageUrl)
    <form id="removeImageForm" action="{{ route('admin.popup.remove-image') }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endif

@endsection

@push('scripts')
<script>
    function togglePopupSwitch(cb) {
        const bg = document.getElementById('sliderBg');
        const dot = document.getElementById('sliderDot');
        if (cb.checked) {
            bg.style.backgroundColor = '#16A34A';
            dot.style.left = '26px';
        } else {
            bg.style.backgroundColor = '#CBD5E1';
            dot.style.left = '3px';
        }
    }

    function previewPopupImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const target = document.getElementById('imagePreviewTarget');
                const placeholder = document.getElementById('imagePlaceholder');
                target.src = e.target.result;
                target.style.display = 'block';
                if (placeholder) placeholder.style.display = 'none';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function submitRemoveImage() {
        if (confirm('আপনি কি নিশ্চিত যে পপআপ ব্যানার ছবিটি মুছে ফেলতে চান?')) {
            document.getElementById('removeImageForm').submit();
        }
    }
</script>
@endpush

