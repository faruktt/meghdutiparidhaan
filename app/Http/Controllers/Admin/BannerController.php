<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('position')->orderBy('sort_order')->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image'      => 'required|image|max:3072',
            'position'   => 'required|in:hero,promo,category',
            'title'      => 'nullable|string|max:255',
            'subtitle'   => 'nullable|string|max:255',
            'link'       => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        $bannerPath = public_path('images/banners');
        if (!file_exists($bannerPath)) {
            mkdir($bannerPath, 0755, true);
        }

        $file = $request->file('image');
        $imageName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move($bannerPath, $imageName);

        Banner::create([
            'title'      => $request->title,
            'subtitle'   => $request->subtitle,
            'image'      => $imageName,
            'link'       => $request->link,
            'position'   => $request->position,
            'sort_order' => $request->sort_order ?? 0,
            'is_active'  => $request->has('is_active') ? $request->boolean('is_active') : true,
        ]);

        return back()->with('success', 'Banner added successfully!');
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'image'      => 'nullable|image|max:3072',
            'position'   => 'required|in:hero,promo,category',
            'title'      => 'nullable|string|max:255',
            'subtitle'   => 'nullable|string|max:255',
            'link'       => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        $data = [
            'title'      => $request->title,
            'subtitle'   => $request->subtitle,
            'link'       => $request->link,
            'position'   => $request->position,
            'sort_order' => $request->sort_order ?? 0,
            'is_active'  => $request->has('is_active') ? $request->boolean('is_active') : false,
        ];

        if ($request->hasFile('image')) {
            $bannerPath = public_path('images/banners');
            if (!file_exists($bannerPath)) {
                mkdir($bannerPath, 0755, true);
            }

            if ($banner->image && file_exists(public_path('images/banners/' . $banner->image))) {
                unlink(public_path('images/banners/' . $banner->image));
            }

            $file = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($bannerPath, $imageName);
            $data['image'] = $imageName;
        }

        $banner->update($data);
        return back()->with('success', 'Banner updated successfully!');
    }

    public function toggleStatus(Banner $banner)
    {
        $banner->update([
            'is_active' => !$banner->is_active,
        ]);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success'   => true,
                'is_active' => $banner->is_active,
                'message'   => 'Banner status updated successfully!',
            ]);
        }

        return back()->with('success', 'Banner status updated successfully!');
    }

    public function destroy(Banner $banner)
    {
        if ($banner->image && file_exists(public_path('images/banners/' . $banner->image))) {
            unlink(public_path('images/banners/' . $banner->image));
        }
        $banner->delete();
        return back()->with('success', 'Banner deleted successfully!');
    }
}
