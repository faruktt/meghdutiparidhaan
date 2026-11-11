<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name'    => 'nullable|string|max:255',
            'site_logo'    => 'nullable|image|max:2048',
            'site_favicon' => 'nullable|image|max:1024',
        ]);

        $textFields = [
            'site_name', 'site_tagline', 'phone', 'email', 'address',
            'inside_dhaka_delivery', 'outside_dhaka_delivery', 'currency_symbol',
            'gtm_id', 'gtm_head_code', 'gtm_body_code',
            'fb_pixel_id', 'fb_pixel_code',
            'google_analytics_id', 'tiktok_pixel_id',
            'facebook_url', 'instagram_url', 'youtube_url', 'whatsapp_number',
            'messenger_url', 'facebook_messenger_url',
            'coming_soon_mode', 'coming_soon_message', 'footer_about', 'copyright_text',
        ];

        foreach ($textFields as $field) {
            if ($request->has($field)) {
                Setting::set($field, $request->input($field));
            }
        }

        // Also synchronize aliases for phone, email, address
        if ($request->has('phone')) {
            Setting::set('site_phone', $request->input('phone'));
        }
        if ($request->has('email')) {
            Setting::set('site_email', $request->input('email'));
        }
        if ($request->has('address')) {
            Setting::set('site_address', $request->input('address'));
        }

        // Handle Coming Soon toggle if checkbox is absent when unchecked
        if (!$request->has('coming_soon_mode')) {
            Setting::set('coming_soon_mode', '0');
        }

        // Upload Logo
        if ($request->hasFile('site_logo')) {
            $oldLogo = Setting::get('site_logo') ?: Setting::get('logo');
            if ($oldLogo && file_exists(public_path('images/' . $oldLogo))) {
                @unlink(public_path('images/' . $oldLogo));
            }
            $file = $request->file('site_logo');
            $fileName = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $fileName);
            Setting::set('site_logo', $fileName);
            Setting::set('logo', $fileName);
        }

        // Upload Favicon
        if ($request->hasFile('site_favicon')) {
            $oldFav = Setting::get('site_favicon') ?: Setting::get('favicon');
            if ($oldFav && file_exists(public_path('images/' . $oldFav))) {
                @unlink(public_path('images/' . $oldFav));
            }
            $file = $request->file('site_favicon');
            $fileName = 'favicon_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $fileName);
            Setting::set('site_favicon', $fileName);
            Setting::set('favicon', $fileName);
        }

        return back()->with('success', 'Settings updated successfully!');
    }
}
