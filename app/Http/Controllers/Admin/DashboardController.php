<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Setting;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $ordersCount = Order::count();
        $productsCount = Product::count();
        $categoriesCount = Category::count();
        $totalRevenue = Order::sum('total_amount');
        $orders = Order::with('items')->latest()->take(30)->get();
        $categories = Category::withCount('products')->orderBy('sort_order')->get();
        $settings = Setting::first();
        $offers = Offer::orderBy('sort_order')->orderByDesc('id')->get();

        // فلترة المنتجات حسب القسم المختار
        $selectedCategoryId = $request->get('category_id');
        $activeSection = $request->get('section', 'dashboard');
        if (!in_array($activeSection, ['dashboard', 'products', 'categories', 'orders', 'offers', 'account', 'settings'], true)) {
            $activeSection = 'dashboard';
        }
        $productsQuery = Product::with('category');
        if ($selectedCategoryId) {
            $productsQuery->where('category_id', $selectedCategoryId);
        }
        $products = $productsQuery->get();

        return view('admin.dashboard', compact(
            'ordersCount',
            'productsCount',
            'categoriesCount',
            'totalRevenue',
            'orders',
            'categories',
            'products',
            'settings',
            'offers',
            'selectedCategoryId',
            'activeSection'
        ));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->status = $request->input('status');
        $order->save();

        return back()->with('success', 'تم تحديث حالة الطلب بنجاح');
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sort_order' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_url'] = $request->file('image')->store('categories', 'public');
        }
        unset($validated['image']);

        Category::create($validated);
        return back()->with('success', 'تم إضافة القسم بنجاح');
    }

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'old_price' => 'nullable|numeric',
            'image_url' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'is_available' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_daily_special' => 'nullable|boolean',
        ]);

        $validated['is_available'] = $request->has('is_available');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_daily_special'] = $request->has('is_daily_special');

        if ($request->hasFile('image')) {
            $validated['image_url'] = $request->file('image')->store('products', 'public');
        }
        unset($validated['image']);

        $product = Product::create($validated);
        return redirect()->route('admin.dashboard', ['category_id' => $product->category_id])
            ->with('success', 'تم إضافة الوجبة بنجاح إلى القسم المحدد');
    }

    public function editProduct($id)
    {
        return view('admin.product-edit', [
            'product' => Product::findOrFail($id),
            'categories' => Category::orderBy('sort_order')->get(),
        ]);
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'old_price' => 'nullable|numeric',
            'image_url' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $validated['is_available'] = $request->has('is_available');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_daily_special'] = $request->has('is_daily_special');

        if ($request->hasFile('image')) {
            if ($product->getRawOriginal('image_url') && !str_starts_with($product->getRawOriginal('image_url'), 'http')) {
                Storage::disk('public')->delete($product->getRawOriginal('image_url'));
            }
            $validated['image_url'] = $request->file('image')->store('products', 'public');
        }
        unset($validated['image']);
        $product->update($validated);

        return redirect()->route('admin.dashboard', ['category_id' => $product->category_id])
            ->with('success', 'تم تحديث الوجبة بنجاح');
    }

    public function toggleProductAvailability(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update(['is_available' => $request->boolean('is_available')]);

        return back()->with('success', 'تم تحديث حالة توفر الوجبة');
    }

    public function deleteProduct($id)
    {
        Product::findOrFail($id)->delete();
        return back()->with('success', 'تم حذف الوجبة بنجاح');
    }

    public function editCategory($id)
    {
        return back()->with('category_edit_id', (int) $id);
    }

    public function updateCategory(Request $request, $id)
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

        return back()->with('success', 'تم تحديث القسم بنجاح');
    }

    public function deleteCategory($id)
    {
        $category = Category::withCount('products')->findOrFail($id);
        if ($category->products_count > 0) {
            return back()->with('error', 'لا يمكن حذف القسم لأنه يحتوي على منتجات. احذف المنتجات أو انقلها أولاً.');
        }

        $path = $category->getRawOriginal('image_url');
        if ($path && !str_starts_with($path, 'http')) {
            Storage::disk('public')->delete($path);
        }
        $category->delete();

        return back()->with('success', 'تم حذف القسم بنجاح');
    }

    public function storeOffer(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'image_url' => ['nullable', 'url', 'max:1000'],
            'action_type' => ['nullable', 'string', 'max:50'],
            'action_id' => ['nullable', 'integer'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        if ($request->hasFile('image')) {
            $validated['image_url'] = $request->file('image')->store('offers', 'public');
        }
        unset($validated['image']);
        Offer::create($validated);

        return back()->with('success', 'تم إضافة البنر بنجاح');
    }

    public function updateOffer(Request $request, $id)
    {
        $offer = Offer::findOrFail($id);
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'image_url' => ['nullable', 'url', 'max:1000'],
            'action_type' => ['nullable', 'string', 'max:50'],
            'action_id' => ['nullable', 'integer'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        if ($request->hasFile('image')) {
            $old = $offer->getRawOriginal('image_url');
            if ($old && !str_starts_with($old, 'http')) {
                Storage::disk('public')->delete($old);
            }
            $validated['image_url'] = $request->file('image')->store('offers', 'public');
        }
        unset($validated['image']);
        $offer->update($validated);

        return back()->with('success', 'تم تحديث البنر بنجاح');
    }

    public function deleteOffer($id)
    {
        $offer = Offer::findOrFail($id);
        $path = $offer->getRawOriginal('image_url');
        if ($path && !str_starts_with($path, 'http')) {
            Storage::disk('public')->delete($path);
        }
        $offer->delete();

        return back()->with('success', 'تم حذف البنر بنجاح');
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'restaurant_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'whatsapp_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'working_hours' => ['nullable', 'string', 'max:255'],
        ]);

        $setting = Setting::first() ?? new Setting();
        $setting->fill($request->only([
            'restaurant_name', 'phone', 'whatsapp_url', 'instagram_url', 'facebook_url',
            'address', 'working_hours', 'delivery_fee',
            'jaib_account', 'jawali_account', 'kuraimi_account'
        ]));
        $setting->save();

        return back()->with('success', 'تم تحديث إعدادات المطعم بنجاح');
    }
}
