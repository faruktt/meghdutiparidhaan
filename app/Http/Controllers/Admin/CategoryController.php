<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('parent')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();
        $parents = Category::whereNull('parent_id')->orderBy('name')->get();
        return view('admin.categories.index', compact('categories', 'parents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $slug = Str::slug($request->name);
        $orig = $slug; $i = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $orig . '-' . $i++;
        }

        $imageName = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/categories'), $imageName);
        }

        Category::create([
            'name'         => $request->name,
            'slug'         => $slug,
            'parent_id'    => $request->parent_id ?: null,
            'image'        => $imageName,
            'description'  => $request->description,
            'sort_order'   => $request->sort_order ?? 0,
            'is_active'    => $request->boolean('is_active', true),
            'show_in_menu' => $request->boolean('show_in_menu', true),
        ]);

        return back()->with('success', 'Category created successfully!');
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = [
            'name'         => $request->name,
            'parent_id'    => $request->parent_id ?: null,
            'description'  => $request->description,
            'sort_order'   => $request->sort_order ?? 0,
            'is_active'    => $request->boolean('is_active', true),
            'show_in_menu' => $request->boolean('show_in_menu', true),
        ];

        if ($request->hasFile('image')) {
            if ($category->image && file_exists(public_path('images/categories/' . $category->image))) {
                unlink(public_path('images/categories/' . $category->image));
            }
            $file = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/categories'), $imageName);
            $data['image'] = $imageName;
        }

        $category->update($data);
        return back()->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Cannot delete: category has products.');
        }
        if ($category->image && file_exists(public_path('images/categories/' . $category->image))) {
            unlink(public_path('images/categories/' . $category->image));
        }
        $category->delete();
        return back()->with('success', 'Category deleted!');
    }
}
