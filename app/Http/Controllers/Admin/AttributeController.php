<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    // Colors
    public function colors()
    {
        $colors = Color::orderBy('name')->get();
        return view('admin.attributes.colors', compact('colors'));
    }

    public function storeColor(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100|unique:colors,name']);
        Color::create([
            'name'      => $request->name,
            'hex_code'  => $request->hex_code,
            'is_active' => $request->boolean('is_active', true),
        ]);
        return back()->with('success', 'Color added!');
    }

    public function updateColor(Request $request, Color $color)
    {
        $color->update([
            'name'      => $request->name,
            'hex_code'  => $request->hex_code,
            'is_active' => $request->boolean('is_active', true),
        ]);
        return back()->with('success', 'Color updated!');
    }

    public function destroyColor(Color $color)
    {
        $color->delete();
        return back()->with('success', 'Color deleted!');
    }

    // Sizes
    public function sizes()
    {
        $sizes = Size::orderBy('type')->orderBy('sort_order')->get();
        return view('admin.attributes.sizes', compact('sizes'));
    }

    public function storeSize(Request $request)
    {
        $request->validate(['name' => 'required|string|max:50']);
        Size::create([
            'name'       => $request->name,
            'type'       => $request->type ?? 'shirt',
            'sort_order' => $request->sort_order ?? 0,
            'is_active'  => $request->boolean('is_active', true),
        ]);
        return back()->with('success', 'Size added!');
    }

    public function updateSize(Request $request, Size $size)
    {
        $size->update([
            'name'       => $request->name,
            'type'       => $request->type ?? $size->type,
            'sort_order' => $request->sort_order ?? $size->sort_order,
            'is_active'  => $request->boolean('is_active', true),
        ]);
        return back()->with('success', 'Size updated!');
    }

    public function destroySize(Size $size)
    {
        $size->delete();
        return back()->with('success', 'Size deleted!');
    }
}
