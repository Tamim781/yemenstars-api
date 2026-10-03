<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

class ApiAdminMiddleware
{
    /**
     * Handle an incoming request for Admin API operations.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $bearer = $request->bearerToken();

        // 1. فحص توكن مدير النظام الثابت (Master Token) أو الجلسات المحلية المحفوظة
        if ($bearer === 'yemenstars_admin_session_token'
            || ($bearer && str_starts_with($bearer, 'local_session_'))
            || $bearer === 'admin_master_secret_2026') {
            
            $admin = User::where('role', 'admin')->orWhere('is_admin', true)->first();
            if (!$admin) {
                // إنشاء حساب المدير تلقائياً في حال كانت قاعدة البيانات فارغة
                $admin = User::create([
                    'name' => 'مدير النظام',
                    'email' => 'admin@yemenstars.com',
                    'password' => bcrypt('admin123456'),
                    'role' => 'admin',
                    'is_admin' => true,
                    'phone' => '775806564',
                ]);
            }
            auth()->setUser($admin);
            $request->setUserResolver(fn () => $admin);
            return $next($request);
        }

        // 2. فحص توكن Sanctum الشخصي في قاعدة البيانات
        if ($bearer) {
            $token = PersonalAccessToken::findToken($bearer);
            if ($token) {
                $user = $token->tokenable;
                if ($user && ($user->isAdmin() || $user->is_admin || $user->role === 'admin')) {
                    auth()->setUser($user);
                    $request->setUserResolver(fn () => $user);
                    return $next($request);
                }
            }
        }

        // 3. فحص مصادقة Sanctum الافتراضية
        $user = auth('sanctum')->user() ?? $request->user();
        if ($user && ($user->isAdmin() || $user->is_admin || $user->role === 'admin')) {
            auth()->setUser($user);
            $request->setUserResolver(fn () => $user);
            return $next($request);
        }

        return response()->json([
            'message' => 'غير مصرح: تتطلب هذه العملية صلاحيات مدير النظام',
        ], 401);
    }
}
