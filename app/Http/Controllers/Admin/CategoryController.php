<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('sort_order')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image_url'] = $request->file('image')->store('categories', 'public');
        }
        unset($validated['image']);

        Category::create($validated);
        return redirect()->route('admin.categories.index')->with('success', 'تم إضافة القسم بنجاح');
    }

    public function show($id)
    {
        return redirect()->route('admin.categories.edit', $id);
    }

    public function edit($id)
    {
        return view('admin.categ    ories.edit', [
            'category' => Category::findOrFail($id),
        ]);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        if ($request->hasFile('image')) {
            $oldPath = $category->getRawOriginal('image_url');
            if ($oldPath && !str_starts_with($oldPath, 'http')) {
                Storage::disk('public')->delete($oldPath);
            }
            $validated['image_url'] = $request->file('image')->store('categories', 'public');
        }
        unset($validated['image']);

        $category->update($validated);
        return redirect()->route('admin.categories.index')->with('success', 'تم تحديث القسم بنجاح');
    }

    public function destroy($id)
    {
        $category = Category::withCount('products')->findOrFail($id);
        if ($category->products_count > 0) {
            return back()->with('error', 'لا يمكن حذف قسم يحتوي على منتجات. احذف المنتجات أو انقلها أولاً.');
        }

        $path = $category->getRawOriginal('image_url');
        if ($path && !str_starts_with($path, 'http')) {
            Storage::disk('public')->delete($path);
        }
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'تم حذف القسم بنجاح');
    }
}

