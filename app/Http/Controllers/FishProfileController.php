<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;

class FishProfileController extends Controller
{
    /**
     * العرض عبر صفحة الويب عند مسح كود الأكريليك بكاميرا الجوال العادية
     */
    public function showWeb($id)
    {
        try {
            $product = Product::with('category')->find($id);

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
                    'image_url' => null,
                    'category' => (object) ['name' => 'أسماك وبسطة نجوم اليمن'],
                ];

                return view('fish_profile', [
                    'product' => $fallbackProduct,
                    'restaurantName' => 'مطعم نجوم اليمن للأسماك',
                    'phone' => '967775806564',
                    'whatsappUrl' => 'https://wa.me/967775806564',
                ]);
            }

            try {
                $product->increment('qr_scans_count');
            } catch (\Throwable $e) {}

            $restaurantName = 'مطعم نجوم اليمن للأسماك';
            $phone = '967775806564';
            try {
                $setting = Setting::first();
                if ($setting) {
                    $restaurantName = $setting->restaurant_name ?? $restaurantName;
                    $phone = $setting->phone ?? $phone;
                }
            } catch (\Throwable $e) {}

            $whatsappUrl = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $phone);

            return view('fish_profile', compact('product', 'restaurantName', 'phone', 'whatsappUrl'));
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
            $product = Product::with('category')->find($id);
            if (!$product) {
                $product = (object) [
                    'id' => $id,
                    'name' => request('name', 'سمك طازج'),
                    'price' => request('price', 6000),
                ];
            }
            $setting = Setting::first();
            $restaurantName = $setting->restaurant_name ?? 'مطعم نجوم اليمن للأسماك';

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
