<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::orderBy('sort_order')->latest('id')->get();
        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:pages,slug',
            'description' => 'required|string',
            'sort_order'  => 'nullable|integer',
        ]);

        $page = Page::create([
            'title'       => $request->title,
            'slug'        => $request->slug ? Page::generateUniqueSlug($request->slug) : Page::generateUniqueSlug($request->title),
            'description' => $request->description,
            'sort_order'  => $request->sort_order ?? (Page::count() + 1),
            'is_active'   => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.pages.index')->with('success', 'Page "' . $page->title . '" created successfully!');
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:pages,slug,' . $page->id,
            'description' => 'required|string',
            'sort_order'  => 'nullable|integer',
        ]);

        $slug = $request->slug ? Page::generateUniqueSlug($request->slug, $page->id) : Page::generateUniqueSlug($request->title, $page->id);

        $page->update([
            'title'       => $request->title,
            'slug'        => $slug,
            'description' => $request->description,
            'sort_order'  => $request->sort_order ?? 0,
            'is_active'   => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.pages.index')->with('success', 'Page "' . $page->title . '" updated successfully!');
    }

    public function destroy(Page $page)
    {
        $title = $page->title;
        $page->delete();
        return redirect()->route('admin.pages.index')->with('success', 'Page "' . $title . '" deleted successfully!');
    }

    public function toggleStatus(Page $page)
    {
        $page->is_active = !$page->is_active;
        $page->save();

        return response()->json([
            'success'   => true,
            'is_active' => $page->is_active,
            'message'   => 'Page status updated successfully.'
        ]);
    }
}
