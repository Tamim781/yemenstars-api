<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;

class FishProfileController extends Controller
{
    /**
     * ضمان وجود مجلدات كاش Blade لتفادي خطأ Please provide a valid cache path
     */
    private function ensureViewCacheDirs()
    {
        $dirs = [
            storage_path('framework'),
            storage_path('framework/views'),
            storage_path('framework/cache'),
            storage_path('framework/sessions'),
            base_path('bootstrap/cache'),
        ];
        foreach ($dirs as $dir) {
            if (!is_dir($dir)) {
                @mkdir($dir, 0777, true);
            }
        }
    }

    /**
     * جلب صورة حقيقية مطابقة لنوع السمك والطبخة التهامية/اليمنية في حال عدم رفع صورة خاصة
     */
    public static function getAuthenticFishImage($name)
    {
        $name = mb_strtolower($name, 'UTF-8');
        if (str_contains($name, 'جمبري') || str_contains($name, 'روبيان')) {
            return 'https://images.unsplash.com/photo-1565680018434-b513d5e5fd47?w=800&q=85'; // روبيان وجمبري ذهبي طازج
        }
        if (str_contains($name, 'شروخ') || str_contains($name, 'استاكوزا')) {
            return 'https://images.unsplash.com/photo-1559742811-822873691df8?w=800&q=85'; // شروخ واستكوزا بحرية
        }
        if (str_contains($name, 'سخلة')) {
            return 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=800&q=85'; // سمك سخلة طازج
        }
        if (str_contains($name, 'ديرك') || str_contains($name, 'كنعد')) {
            return 'https://images.unsplash.com/photo-1534939561126-855b8675edd7?w=800&q=85'; // سمك ديرك فاخر
        }
        if (str_contains($name, 'صانونة')) {
            return 'https://images.unsplash.com/photo-1544025162-d76694265947?w=800&q=85'; // إيدام وصانونة سمك مسبكة
        }
        if (str_contains($name, 'مشوي') || str_contains($name, 'موفي')) {
            return 'https://images.unsplash.com/photo-1580476262798-bddd9f4b7369?w=800&q=85'; // سمك مشوي في التنور وعلى الفحم
        }
        if (str_contains($name, 'بروست') || str_contains($name, 'مقلي')) {
            return 'https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?w=800&q=85'; // بروست سمك مقرمش
        }
        if (str_contains($name, 'ثمد') || str_contains($name, 'تونة')) {
            return 'https://images.unsplash.com/photo-1501595091296-3aa970afb3ff?w=800&q=85'; // جزل ثمد وتونة طازجة
        }
        if (str_contains($name, 'باغة')) {
            return 'https://images.unsplash.com/photo-1534939561126-855b8675edd7?w=800&q=85'; // سمك باغة بلدي
        }
        if (str_contains($name, 'بياض')) {
            return 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=800&q=85'; // سمك بياض ناصع
        }
        return 'https://images.unsplash.com/photo-1534939561126-855b8675edd7?w=800&q=85';
    }

    /**
     * العرض عبر صفحة الويب عند مسح كود الأكريليك بكاميرا الجوال العادية
     */
    public function showWeb($id)
    {
        try {
            $this->ensureViewCacheDirs();

            $product = Product::with('category')->find($id);

            // الرقم المعتمد دائماً للواتساب والطلبات بحسب طلب الإدارة
            $phone = '967771000272';
            $whatsappUrl = 'https://wa.me/967771000272';
            $restaurantName = 'مطعم نجوم اليمن للأسماك';

            if (!$product) {
                // إذا لم يوجد الصنف بالـ ID، نعرض قالباً افتراضياً أنيقاً يرحب بالعميل
                $fallbackProduct = (object) [
                    'id' => $id,
                    'name' => 'صنف بحري طازج',
                    'price' => 6000,
                    'description' => 'سمك بلدي طازج يومياً من البحر مباشرة لطاولتك.',
                    'benefits' => 'غني بأحماض أوميغا 3 المفيدة لصحة القلب والنشاط الذهني ومصدر بروتين نقي.',
                    'cooking_recommendations' => 'ينصح به: موفي بلدي في التنور مع تتبيلتنا الخاصة، أو مشوي على الفحم.',
                    'meat_texture' => 'لحم طري لذيذ متماسك',
                    'image_url' => self::getAuthenticFishImage('صنف بحري'),
                    'category' => (object) ['name' => 'أسماك وبسطة نجوم اليمن'],
                ];

                return view('fish_profile', [
                    'product' => $fallbackProduct,
                    'displayImage' => self::getAuthenticFishImage('صنف بحري'),
                    'restaurantName' => $restaurantName,
                    'phone' => $phone,
                    'whatsappUrl' => $whatsappUrl,
                ]);
            }

            try {
                $product->increment('qr_scans_count');
            } catch (\Throwable $e) {}

            // تحديد الصورة المعروضة بدقة:
            // 1. إذا كان هناك رابط صورة صالح مرفوع على السيرفر، نعرضه
            // 2. إذا كانت الصورة مسار محلي على الجوال أو غير مرفوعة، نعرض الصورة الحقيقية المتخصصة لهذا الصنف
            $img = $product->image_url;
            if (!$img || str_starts_with($img, '/data/') || str_starts_with($img, 'file:') || str_ends_with($img, 'default-fish.jpg')) {
                $displayImage = self::getAuthenticFishImage($product->name);
            } else {
                $displayImage = $img;
            }

            return view('fish_profile', compact('product', 'displayImage', 'restaurantName', 'phone', 'whatsappUrl'));
        } catch (\Throwable $e) {
            return response('خطأ في تحميل صفحة السمك: ' . $e->getMessage() . ' في السطر ' . $e->getLine(), 500);
        }
    }

    /**
     * تسجيل المسح عبر تطبيق الموبايل (Flutter App)
     */
    public function scanApi($id)
    {
        try {
            $product = Product::with('category')->find($id);

            if (!$product) {
                return response()->json(['success' => false, 'message' => 'المنتج غير موجود'], 404);
            }

            try {
                $product->increment('qr_scans_count');
            } catch (\Throwable $e) {}

            return response()->json([
                'success' => true,
                'data' => $product,
                'qr_scans_count' => $product->qr_scans_count ?? 1,
            ]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * صفحة توليد وتصدير كارت الأكريليك للطباعة (High-Res Printable Card)
     */
    public function acrylicCard($id)
    {
        try {
            $this->ensureViewCacheDirs();

            $product = Product::with('category')->find($id);
            if (!$product) {
                $product = (object) [
                    'id' => $id,
                    'name' => request('name', 'سمك طازج'),
                    'price' => request('price', 6000),
                ];
            }
            $restaurantName = 'مطعم نجوم اليمن للأسماك';

            // رابط الكود الدائم على السيرفر
            $qrTargetUrl = url("/fish/{$product->id}");
            $qrImageUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=500x500&margin=10&data=' . urlencode($qrTargetUrl);

            return view('admin.acrylic_card', compact('product', 'restaurantName', 'qrTargetUrl', 'qrImageUrl'));
        } catch (\Throwable $e) {
            return response('Error rendering acrylic card: ' . $e->getMessage(), 500);
        }
    }

    /**
     * تقرير إحصائيات وفلترة باركودات البسطة
     */
    public function analyticsWeb(Request $request)
    {
        try {
            $this->ensureViewCacheDirs();

            $query = Product::with('category');

            $sortBy = $request->get('sort', 'scans_desc');
            if ($sortBy === 'scans_desc') {
                $query->orderBy('qr_scans_count', 'desc');
            } elseif ($sortBy === 'orders_desc') {
                $query->orderBy('qr_orders_count', 'desc');
            } elseif ($sortBy === 'price_desc') {
                $query->orderBy('price', 'desc');
            } else {
                $query->latest();
            }

            $products = $query->paginate(20);
            $totalScans = 0;
            $totalQrOrders = 0;
            try {
                $totalScans = Product::sum('qr_scans_count');
                $totalQrOrders = Product::sum('qr_orders_count');
            } catch (\Throwable $e) {}
            $avgConversion = $totalScans > 0 ? round(($totalQrOrders / $totalScans) * 100, 1) : 0;

            return view('admin.qr_analytics', compact('products', 'totalScans', 'totalQrOrders', 'avgConversion', 'sortBy'));
        } catch (\Throwable $e) {
            return response('Error loading analytics: ' . $e->getMessage(), 500);
        }
    }
}
