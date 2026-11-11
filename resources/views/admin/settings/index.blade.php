@extends('layouts.admin')

@section('title', 'Settings')
@section('breadcrumb', 'Settings')

@section('content')
<div class="card-header-flex">
    <div>
        <h1 style="font-size: 20px; font-weight: 800; color: var(--text-dark);">Settings</h1>
    </div>
</div>

<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
        <!-- Main Configuration -->
        <div>
            <!-- Coming Soon Mode Setting -->
            <div class="card" style="border: 1px solid #FED7AA; background: #FFF7ED;">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <h3 style="font-size: 15px; font-weight: 800; color: #C2410C;">
                            <i class="fa-solid fa-hourglass-start"></i> Coming Soon Mode
                        </h3>
                        <p style="font-size: 12px; color: #9A3412; margin-top: 2px;">
                            Display the Coming Soon landing page on the homepage.
                        </p>
                    </div>
                    <label style="position: relative; display: inline-block; width: 50px; height: 26px;">
                        <input type="checkbox" name="coming_soon_mode" value="1" {{ ($settings['coming_soon_mode'] ?? '0') == '1' ? 'checked' : '' }} style="opacity: 0; width: 0; height: 0;" id="comingSoonToggle" onchange="toggleComingSoonBadge(this)">
                        <span id="sliderBg" style="position: absolute; cursor: pointer; inset: 0; background-color: {{ ($settings['coming_soon_mode'] ?? '0') == '1' ? '#EA580C' : '#CBD5E1' }}; transition: .3s; border-radius: 34px;">
                            <span id="sliderDot" style="position: absolute; height: 20px; width: 20px; left: {{ ($settings['coming_soon_mode'] ?? '0') == '1' ? '26px' : '3px' }}; bottom: 3px; background-color: white; transition: .3s; border-radius: 50%;"></span>
                        </span>
                    </label>
                </div>
            </div>

            <!-- General Brand Information -->
            <div class="card">
                <h3 class="card-title" style="margin-bottom: 16px;">General Brand Profile</h3>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px;">Site Name</label>
                        <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'Meghduti Paridhaan' }}" class="form-control" required>
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px;">Brand Tagline</label>
                        <input type="text" name="site_tagline" value="{{ $settings['site_tagline'] ?? 'Own The Fashion Wave' }}" class="form-control">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px;">Hotline Number</label>
                        <input type="text" name="phone" value="{{ $settings['phone'] ?? '+8809613000000' }}" class="form-control">
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px;">Customer Support Email</label>
                        <input type="email" name="email" value="{{ $settings['email'] ?? 'support@meghdutiparidhaan.com' }}" class="form-control">
                    </div>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px;">Office / Showroom Address</label>
                    <input type="text" name="address" value="{{ $settings['address'] ?? 'Dhanmondi, Dhaka, Bangladesh' }}" class="form-control">
                </div>
            </div>

            <!-- Pixels & Marketing Tracking -->
            <div class="card">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; border-bottom: 1px solid #E2E8F0; padding-bottom: 12px;">
                    <div>
                        <h3 class="card-title" style="margin: 0; font-size: 15px; font-weight: 800; color: #1E293B;">
                            <i class="fa-solid fa-chart-line" style="color: #2563EB;"></i> Marketing Tracking, Facebook Pixel & GTM
                        </h3>
                        <p style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">
                            Configure Facebook Pixel, Google Tag Manager (GTM) Container, and dataLayer eCommerce tracking.
                        </p>
                    </div>
                    <span style="font-size: 11px; font-weight: 700; background: #EFF6FF; color: #1D4ED8; padding: 4px 10px; border-radius: 20px; border: 1px solid #BFDBFE;">
                        GA4 & Meta DataLayer Ready
                    </span>
                </div>

                <!-- Google Tag Manager (GTM) Box -->
                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 16px; margin-bottom: 16px;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
                        <i class="fa-brands fa-google" style="color: #EA4335; font-size: 16px;"></i>
                        <strong style="font-size: 13.5px; color: #0F172A;">Google Tag Manager (GTM)</strong>
                    </div>

                    <div style="margin-bottom: 12px;">
                        <label style="display: block; font-size: 12.5px; font-weight: 700; margin-bottom: 5px;">
                            GTM Container ID
                            <span style="color: #64748B; font-weight: normal; font-size: 11.5px;">(Recommended: e.g. GTM-XXXXXXX)</span>
                        </label>
                        <input type="text" name="gtm_id" value="{{ $settings['gtm_id'] ?? '' }}" class="form-control" placeholder="GTM-XXXXXXX" style="font-family: monospace; font-weight: 600;">
                        <small style="color: #64748B; font-size: 11px; margin-top: 4px; display: block;">
                            Entering your GTM Container ID automatically sets up the head script, body noscript, and complete eCommerce dataLayer.
                        </small>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 4px;">
                                Custom GTM Head Code
                                <small style="color: #94A3B8; font-weight: normal;">(Optional raw &lt;script&gt;)</small>
                            </label>
                            <textarea name="gtm_head_code" class="form-control" rows="3" placeholder="<!-- Paste full GTM head code here if not using Container ID -->" style="font-family: monospace; font-size: 11px;">{{ $settings['gtm_head_code'] ?? '' }}</textarea>
                        </div>
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 4px;">
                                Custom GTM Body Code
                                <small style="color: #94A3B8; font-weight: normal;">(Optional raw &lt;noscript&gt;)</small>
                            </label>
                            <textarea name="gtm_body_code" class="form-control" rows="3" placeholder="<!-- Paste full GTM body (noscript) code here -->" style="font-family: monospace; font-size: 11px;">{{ $settings['gtm_body_code'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Facebook / Meta Pixel Box -->
                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 16px; margin-bottom: 16px;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
                        <i class="fa-brands fa-facebook" style="color: #1877F2; font-size: 16px;"></i>
                        <strong style="font-size: 13.5px; color: #0F172A;">Facebook (Meta) Pixel</strong>
                    </div>

                    <div style="margin-bottom: 12px;">
                        <label style="display: block; font-size: 12.5px; font-weight: 700; margin-bottom: 5px;">
                            Facebook Pixel ID
                            <span style="color: #64748B; font-weight: normal; font-size: 11.5px;">(Numeric ID e.g. 123456789012345)</span>
                        </label>
                        <input type="text" name="fb_pixel_id" value="{{ $settings['fb_pixel_id'] ?? '' }}" class="form-control" placeholder="123456789012345" style="font-family: monospace; font-weight: 600;">
                        <small style="color: #64748B; font-size: 11px; margin-top: 4px; display: block;">
                            Automatically fires PageView, ViewContent, AddToCart, InitiateCheckout, and Purchase events.
                        </small>
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 4px;">
                            Custom Facebook Pixel Code
                            <small style="color: #94A3B8; font-weight: normal;">(Optional raw &lt;script&gt;)</small>
                        </label>
                        <textarea name="fb_pixel_code" class="form-control" rows="3" placeholder="<!-- Paste full Facebook Pixel script here if not using Pixel ID -->" style="font-family: monospace; font-size: 11px;">{{ $settings['fb_pixel_code'] ?? '' }}</textarea>
                    </div>
                </div>

                <!-- Google Analytics & TikTok -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div>
                        <label style="display: block; font-size: 12.5px; font-weight: 700; margin-bottom: 5px;">Google Analytics (GA4) ID</label>
                        <input type="text" name="google_analytics_id" value="{{ $settings['google_analytics_id'] ?? '' }}" class="form-control" placeholder="G-XXXXXXXXXX" style="font-family: monospace;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12.5px; font-weight: 700; margin-bottom: 5px;">TikTok Pixel ID</label>
                        <input type="text" name="tiktok_pixel_id" value="{{ $settings['tiktok_pixel_id'] ?? '' }}" class="form-control" placeholder="C1234567890" style="font-family: monospace;">
                    </div>
                </div>
            </div>

            <!-- Social Links -->
            <div class="card">
                <h3 class="card-title" style="margin-bottom: 16px;">Social Media & WhatsApp</h3>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px;">Facebook Page URL</label>
                        <input type="text" name="facebook_url" value="{{ $settings['facebook_url'] ?? '#' }}" class="form-control">
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px;">Instagram URL</label>
                        <input type="text" name="instagram_url" value="{{ $settings['instagram_url'] ?? '#' }}" class="form-control">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px;">YouTube Channel URL</label>
                        <input type="text" name="youtube_url" value="{{ $settings['youtube_url'] ?? '#' }}" class="form-control">
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px;">Facebook Messenger Link (Optional)</label>
                        <input type="text" name="messenger_url" value="{{ $settings['messenger_url'] ?? ($settings['facebook_messenger_url'] ?? '') }}" class="form-control" placeholder="https://m.me/yourpage">
                    </div>
                </div>

                <!-- Floating WhatsApp Chat Configuration -->
                <div style="margin-top: 16px; padding: 14px 16px; background: #F0FDF4; border: 1.5px solid #86EFAC; border-radius: 8px;">
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 13.5px; font-weight: 800; margin-bottom: 6px; color: #166534;">
                        <i class="fa-brands fa-whatsapp" style="color: #25D366; font-size: 20px;"></i> Floating WhatsApp Chat Number
                    </label>
                    <input type="text" name="whatsapp_number" value="{{ $settings['whatsapp_number'] ?? ($settings['phone'] ?? '01983380065') }}" class="form-control" placeholder="017xxxxxxxx or +88017xxxxxxxx" style="background: #FFFFFF; font-weight: 600;">
                    <small style="color: #15803D; font-size: 12px; display: block; margin-top: 6px; line-height: 1.4;">
                        <i class="fa-solid fa-circle-info"></i> Customers clicking the floating chat button on the website will directly start a WhatsApp conversation with this number.
                    </small>
                </div>
            </div>
        </div>

        <!-- Sidebar Logos & Save -->
        <div>
            <!-- Logo Upload -->
            <div class="card">
                <h3 class="card-title" style="margin-bottom: 12px;">Store Logo</h3>
                <p style="font-size: 12px; color: var(--text-muted); margin-bottom: 12px;">Upload transparent PNG, SVG, or JPEG logo</p>
                @php
                    $activeLogo = !empty($settings['site_logo']) ? $settings['site_logo'] : (!empty($settings['logo']) ? $settings['logo'] : null);
                @endphp
                @if($activeLogo)
                    <div style="margin-bottom: 12px; padding: 10px; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; text-align: center;">
                        <img src="{{ \Illuminate\Support\Str::startsWith($activeLogo, ['http://', 'https://']) ? $activeLogo : asset('images/' . $activeLogo) }}" style="max-height: 52px; max-width: 100%; object-fit: contain;" alt="Logo">
                    </div>
                @endif
                <input type="file" name="site_logo" accept="image/*" class="form-control">
            </div>

            <!-- Favicon Upload -->
            <div class="card">
                <h3 class="card-title" style="margin-bottom: 12px;">Store Favicon</h3>
                <p style="font-size: 12px; color: var(--text-muted); margin-bottom: 12px;">Square 32x32 or 64x64 PNG icon</p>
                @php
                    $activeFavicon = !empty($settings['site_favicon']) ? $settings['site_favicon'] : (!empty($settings['favicon']) ? $settings['favicon'] : null);
                @endphp
                @if($activeFavicon)
                    <div style="margin-bottom: 12px; padding: 6px; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px; display: inline-block;">
                        <img src="{{ \Illuminate\Support\Str::startsWith($activeFavicon, ['http://', 'https://']) ? $activeFavicon : asset('images/' . $activeFavicon) }}" style="width: 32px; height: 32px; object-fit: contain; display: block;" alt="Favicon">
                    </div>
                @endif
                <input type="file" name="site_favicon" accept="image/*" class="form-control">
            </div>

            <button type="submit" class="btn-admin-primary" style="width: 100%; justify-content: center; padding: 15px; font-size: 16px;">
                <i class="fa-solid fa-floppy-disk"></i> Save All Settings
            </button>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    function toggleComingSoonBadge(checkbox) {
        const bg = document.getElementById('sliderBg');
        const dot = document.getElementById('sliderDot');
        if (checkbox.checked) {
            bg.style.backgroundColor = '#EA580C';
            dot.style.left = '26px';
        } else {
            bg.style.backgroundColor = '#CBD5E1';
            dot.style.left = '3px';
        }
    }
</script>
@endpush
