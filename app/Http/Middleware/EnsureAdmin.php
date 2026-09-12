<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        if (!Auth::user()->is_admin) {
            Auth::logout();
            $request->session()->invalidate();
            return redirect()->route('admin.login')->withErrors([
                'email' => 'هذا الحساب لا يملك صلاحية دخول لوحة الإدارة.',
            ]);
        }

        return $next($request);
    }
}
