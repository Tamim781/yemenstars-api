<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    private function ensureAdmin(Request $request): void
    {
        abort_unless($request->user()->isAdmin(), 403, 'هذه العملية متاحة للأدمن فقط');
    }

    public function index(Request $request)
    {
        $this->ensureAdmin($request);
        return response()->json(User::whereIn('role', ['admin', 'reception'])->latest()->get());
    }

    public function store(Request $request)
    {
        $this->ensureAdmin($request);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', Rule::in(['admin', 'reception'])],
        ]);
        $data['password'] = Hash::make($data['password']);
        $data['is_admin'] = $data['role'] === 'admin';
        return response()->json(User::create($data), 201);
    }

    public function update(Request $request, User $user)
    {
        $this->ensureAdmin($request);
        abort_unless(in_array($user->role, ['admin', 'reception'], true), 404);
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['sometimes', Rule::in(['admin', 'reception'])],
        ]);
        if (isset($data['password'])) $data['password'] = Hash::make($data['password']);
        if (isset($data['role'])) $data['is_admin'] = $data['role'] === 'admin';
        $user->update($data);
        return response()->json($user->fresh());
    }

    public function destroy(Request $request, User $user)
    {
        $this->ensureAdmin($request);
        abort_unless($user->id !== $request->user()->id, 422, 'لا يمكن حذف حسابك الحالي');
        abort_unless(in_array($user->role, ['admin', 'reception'], true), 404);
        $user->delete();
        return response()->json(['message' => 'تم حذف الحساب']);
    }
}
