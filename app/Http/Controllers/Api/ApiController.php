<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\Occasion;
use App\Models\Offer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    public function getCategories()
    {
        return response()->json(Category::orderBy('sort_order')->get());
    }

    public function getProducts(Request $request)
    {
        $query = Product::query()->where('is_available', true);
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }
        if ($request->boolean('is_daily_special')) {
            $query->where('is_daily_special', true);
        }
        if ($request->boolean('is_featured')) {
            $query->where('is_featured', true);
        }
        if ($request->filled('search')) {
            $term = $request->string('search')->toString();
            $query->where(function ($builder) use ($term) {
                $builder->where('name', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%");
            });
        }
        return response()->json($query->orderByDesc('created_at')->get());
    }

    public function getOffers()
    {
        return response()->json(Offer::query()
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->orderBy('sort_order')->get());
    }

    public function getPaymentMethods()
    {
        return response()->json(PaymentMethod::where('is_active', true)->orderBy('sort_order')->get());
    }

    public function getSettings()
    {
        $setting = Setting::first();
        if (!$setting) {
            return response()->json([
                'restaurant_name' => 'مطعم نجوم اليمن للأسماك',
                'phone' => '967771000272',
                'whatsapp_url' => 'https://wa.me/967771000272',
                'instagram_url' => 'https://www.instagram.com/yemen_stars_restaurant',
                'facebook_url' => 'https://www.facebook.com/yemen.stars.restaurant',
                'address' => 'صنعاء، اليمن',
                'working_hours' => 'يومياً من 9:00 صباحاً حتى 12:00 منتصف الليل',
            ]);
        }

        $data = $setting->toArray();
        $data['whatsapp_url'] = !empty($data['whatsapp_url']) ? $data['whatsapp_url'] : 'https://wa.me/967771000272';
        $data['instagram_url'] = !empty($data['instagram_url']) ? $data['instagram_url'] : 'https://www.instagram.com/yemen_stars_restaurant';
        $data['facebook_url'] = !empty($data['facebook_url']) ? $data['facebook_url'] : 'https://www.facebook.com/yemen.stars.restaurant';
        $data['phone'] = !empty($data['phone']) ? $data['phone'] : '967771000272';
        $data['address'] = !empty($data['address']) ? $data['address'] : 'صنعاء، اليمن';
        $data['working_hours'] = !empty($data['working_hours']) ? $data['working_hours'] : 'يومياً من 9:00 صباحاً حتى 12:00 منتصف الليل';

        return response()->json($data);
    }

    public function getFavorites(Request $request)
    {
        return response()->json($request->user()->favorites()->with('product')->get()->pluck('product'));
    }

    public function addFavorite(Request $request, Product $product)
    {
        Favorite::firstOrCreate([
            'user_id' => $request->user()->id,
            'product_id' => $product->id,
        ]);
        return response()->json(['message' => 'تمت الإضافة إلى المفضلة']);
    }

    public function removeFavorite(Request $request, Product $product)
    {
        Favorite::where('user_id', $request->user()->id)
            ->where('product_id', $product->id)->delete();
        return response()->json(['message' => 'تمت الإزالة من المفضلة']);
    }

    public function getAddresses(Request $request)
    {
        return response()->json($request->user()->addresses()->orderByDesc('is_default')->get());
    }

    public function createAddress(Request $request)
    {
        $data = $request->validate([
            'label' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'address' => ['required', 'string'],
            'phone' => ['nullable', 'string', 'max:50'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'is_default' => ['boolean'],
        ]);
        $data['user_id'] = $request->user()->id;
        if (!empty($data['is_default'])) {
            $request->user()->addresses()->update(['is_default' => false]);
        }
        return response()->json(Address::create($data), 201);
    }

    public function updateAddress(Request $request, Address $address)
    {
        abort_unless($address->user_id === $request->user()->id, 403);
        $data = $request->validate([
            'label' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'address' => ['required', 'string'],
            'phone' => ['nullable', 'string', 'max:50'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'is_default' => ['boolean'],
        ]);
        if (!empty($data['is_default'])) {
            $request->user()->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        }
        $address->update($data);
        return response()->json($address->fresh());
    }

    public function deleteAddress(Request $request, Address $address)
    {
        abort_unless($address->user_id === $request->user()->id, 403);
        $address->delete();
        return response()->json(['message' => 'تم حذف العنوان']);
    }

    public function createOrder(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:50'],
            'address' => ['required', 'string'],
            'payment_method' => ['required', 'string', 'max:100'],
            'transfer_reference' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        return DB::transaction(function () use ($validated, $request) {
            $totalAmount = 0;
            $products = [];
            foreach ($validated['items'] as $item) {
                $product = Product::where('id', $item['product_id'])
                    ->where('is_available', true)->firstOrFail();
                $products[$product->id] = $product;
                $totalAmount += (float) $product->price * $item['quantity'];
            }

            $order = Order::create([
                'user_id' => $request->user()?->id,
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'address' => $validated['address'],
                'total_amount' => $totalAmount,
                'status' => 'new',
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'pending',
                'transfer_reference' => $validated['transfer_reference'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($validated['items'] as $item) {
                $product = $products[$item['product_id']];
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $item['quantity'],
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'تم إنشاء الطلب بنجاح',
                'order' => $order->load('items'),
            ], 201);
        });
    }

    public function getOrders(Request $request)
    {
        $user = $request->user();
        if ($user) {
            if ($user->isReception()) {
                return response()->json(
                    Order::with(['items', 'claimant'])
                        ->latest()
                        ->limit(100)
                        ->get()
                );
            }

            return response()->json($user->orders()->with(['items', 'claimant'])->latest()->get());
        }

        if ($request->filled('phone')) {
            return response()->json(
                Order::where('customer_phone', $request->string('phone'))
                    ->with(['items'])
                    ->latest()
                    ->get()
            );
        }

        return response()->json(
            Order::with(['items'])
                ->latest()
                ->limit(30)
                ->get()
        );
    }

    public function getOrder(Request $request, Order $order)
    {
        return response()->json($order->load(['items', 'claimant']));
    }

    public function claimOrder(Request $request, Order $order)
    {
        abort_unless($request->user()->isReception(), 403);

        $claimed = DB::transaction(function () use ($request, $order) {
            $locked = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();
            if ($locked->claimed_by !== null) {
                return $locked->load('claimant');
            }

            $locked->forceFill([
                'claimed_by' => $request->user()->id,
                'claimed_at' => now(),
            ])->save();

            return $locked->load('claimant');
        });

        if ((int) $claimed->claimed_by !== (int) $request->user()->id) {
            return response()->json([
                'message' => 'تم استلام الطلب مسبقاً بواسطة موظف آخر',
                'order' => $claimed,
            ], 409);
        }

        return response()->json([
            'message' => 'تم استلام الطلب بنجاح',
            'order' => $claimed,
        ]);
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        abort_unless($request->user()->isReception(), 403);
        $data = $request->validate([
            'status' => ['required', 'in:new,confirmed,preparing,ready,out_for_delivery,delivered,cancelled'],
        ]);
        $order->update(['status' => $data['status']]);
        return response()->json($order->fresh()->load('claimant'));
    }

    public function getOccasions(Request $request)
    {
        return response()->json($request->user()->occasions()->orderBy('occasion_date')->get());
    }

    public function createOccasion(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'occasion_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ]);
        $data['user_id'] = $request->user()->id;
        return response()->json(Occasion::create($data), 201);
    }

    public function updateOccasion(Request $request, Occasion $occasion)
    {
        abort_unless($occasion->user_id === $request->user()->id, 403);
        $occasion->update($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'occasion_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ]));
        return response()->json($occasion->fresh());
    }

    public function deleteOccasion(Request $request, Occasion $occasion)
    {
        abort_unless($occasion->user_id === $request->user()->id, 403);
        $occasion->delete();
        return response()->json(['message' => 'تم حذف المناسبة']);
    }

    public function getAdminStats(Request $request)
    {
        abort_unless($request->user()->isAdmin(), 403);
        return response()->json([
            'orders_count' => Order::count(),
            'products_count' => Product::count(),
            'categories_count' => Category::count(),
            'total_revenue' => Order::sum('total_amount') ?? 0,
            'recent_orders' => Order::with('items')->latest()->take(10)->get(),
        ]);
    }

    public function storeAdminProduct(Request $request)
    {
        abort_unless($request->user()->isAdmin(), 403);
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'old_price' => 'nullable|numeric',
            'image_url' => 'nullable|string',
            'is_available' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_daily_special' => 'nullable|boolean',
        ]);
        $product = Product::create($data);
        return response()->json($product, 201);
    }

    public function updateAdminProduct(Request $request, Product $product)
    {
        abort_unless($request->user()->isAdmin(), 403);
        $data = $request->validate([
            'category_id' => 'sometimes|required|exists:categories,id',
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric',
            'old_price' => 'nullable|numeric',
            'image_url' => 'nullable|string',
            'is_available' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_daily_special' => 'nullable|boolean',
        ]);
        $product->update($data);
        return response()->json($product->fresh());
    }

    public function toggleAdminProductAvailability(Request $request, Product $product)
    {
        abort_unless($request->user()->isAdmin(), 403);
        $product->update(['is_available' => !$product->is_available]);
        return response()->json($product->fresh());
    }

    public function deleteAdminProduct(Request $request, Product $product)
    {
        abort_unless($request->user()->isAdmin(), 403);
        $product->delete();
        return response()->json(['message' => 'تم حذف الوجبة بنجاح']);
    }

    public function storeAdminCategory(Request $request)
    {
        abort_unless($request->user()->isAdmin(), 403);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'sort_order' => 'nullable|integer',
            'image_url' => 'nullable|string',
        ]);
        $category = Category::create($data);
        return response()->json($category, 201);
    }

    public function updateAdminCategory(Request $request, Category $category)
    {
        abort_unless($request->user()->isAdmin(), 403);
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'sort_order' => 'nullable|integer',
            'image_url' => 'nullable|string',
        ]);
        $category->update($data);
        return response()->json($category->fresh());
    }

    public function deleteAdminCategory(Request $request, Category $category)
    {
        abort_unless($request->user()->isAdmin(), 403);
        $category->delete();
        return response()->json(['message' => 'تم حذف القسم بنجاح']);
    }
}
