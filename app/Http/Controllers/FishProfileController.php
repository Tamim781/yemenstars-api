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
        $product = Product::with('category')->find($id);

        if (!$product) {
            abort(404, 'صنف السمك غير موجود');
        }

        // تسجيل زيادة في عداد الزيارات ومسحات كود البسطة
        $product->increment('qr_scans_count');

        $setting = Setting::first();
        $restaurantName = $setting->restaurant_name ?? 'مطعم نجوم اليمن للأسماك';
        $phone = $setting->phone ?? '967771000272';
        $whatsappUrl = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $phone);

        return view('fish_profile', compact('product', 'restaurantName', 'phone', 'whatsappUrl'));
    }

    /**
     * تسجيل المسح عبر تطبيق الموبايل (Flutter App)
     */
    public function scanApi($id)
    {
        $product = Product::with('category')->find($id);

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'المنتج غير موجود'], 404);
        }

        $product->increment('qr_scans_count');

        return response()->json([
            'success' => true,
            'data' => $product,
            'qr_scans_count' => $product->qr_scans_count,
        ]);
    }

    /**
     * صفحة توليد وتصدير كارت الأكريليك للطباعة (High-Res Printable Card)
     */
    public function acrylicCard($id)
    {
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
    }

    /**
     * تقرير إحصائيات وفلترة باركودات البسطة
     */
    public function analyticsWeb(Request $request)
    {
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
        $totalScans = Product::sum('qr_scans_count');
        $totalQrOrders = Product::sum('qr_orders_count');
        $avgConversion = $totalScans > 0 ? round(($totalQrOrders / $totalScans) * 100, 1) : 0;

        return view('admin.qr_analytics', compact('products', 'totalScans', 'totalQrOrders', 'avgConversion', 'sortBy'));
    }

    /**
     * تقرير إحصائيات الـ API للموبايل والإدارة
     */
    public function analyticsApi()
    {
        $products = Product::select('id', 'name', 'price', 'qr_scans_count', 'qr_orders_count', 'image_url')
            ->orderBy('qr_scans_count', 'desc')
            ->get();

        $totalScans = $products->sum('qr_scans_count');
        $totalQrOrders = $products->sum('qr_orders_count');

        return response()->json([
            'success' => true,
            'summary' => [
                'total_scans' => $totalScans,
                'total_orders' => $totalQrOrders,
                'conversion_rate' => $totalScans > 0 ? round(($totalQrOrders / $totalScans) * 100, 1) : 0,
            ],
            'products' => $products,
        ]);
    }
}
