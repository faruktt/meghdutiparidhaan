<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class PopupBannerController extends Controller
{
    public function index()
    {
        $popupActive = Setting::get('popup_active', '0') == '1';
        $popupImage  = Setting::get('popup_image');
        $popupLink   = Setting::get('popup_link', '');
        $popupTitle  = Setting::get('popup_title', '');
        $popupDelay  = (int) Setting::get('popup_delay', 1);

        $popupImageUrl = null;
        if ($popupImage) {
            if (\Illuminate\Support\Str::startsWith($popupImage, ['http://', 'https://'])) {
                $popupImageUrl = $popupImage;
            } elseif (\Illuminate\Support\Str::startsWith($popupImage, 'images/')) {
                $popupImageUrl = asset($popupImage);
            } else {
                $popupImageUrl = asset('images/popup/' . $popupImage);
            }
        }

        return view('admin.popup.index', compact(
            'popupActive',
            'popupImage',
            'popupImageUrl',
            'popupLink',
            'popupTitle',
            'popupDelay'
        ));
    }

    public function update(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|max:4096',
            'link'  => 'nullable|string|max:500',
            'title' => 'nullable|string|max:255',
            'delay' => 'nullable|integer|min:0|max:60',
        ]);

        Setting::set('popup_active', $request->has('popup_active') ? '1' : '0');
        Setting::set('popup_link', $request->input('link', ''));
        Setting::set('popup_title', $request->input('title', ''));
        Setting::set('popup_delay', $request->input('delay', 1));

        if ($request->hasFile('image')) {
            $dir = public_path('images/popup');
            if (!file_exists($dir)) {
                mkdir($dir, 0755, true);
            }

            // Remove old image if exists
            $oldImage = Setting::get('popup_image');
            if ($oldImage && file_exists(public_path('images/popup/' . $oldImage))) {
                @unlink(public_path('images/popup/' . $oldImage));
            }

            $file = $request->file('image');
            $filename = 'popup_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $filename);

            Setting::set('popup_image', $filename);
        }

        return back()->with('success', 'Popup Banner settings updated successfully!');
    }

    public function removeImage()
    {
        $oldImage = Setting::get('popup_image');
        if ($oldImage && file_exists(public_path('images/popup/' . $oldImage))) {
            @unlink(public_path('images/popup/' . $oldImage));
        }
        Setting::set('popup_image', null);
        return back()->with('success', 'Popup banner image removed successfully!');
    }
}

