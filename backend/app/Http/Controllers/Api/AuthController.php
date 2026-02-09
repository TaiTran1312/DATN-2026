<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:8|confirmed',
            'full_name'  => 'required|string|max:100',
            'phone'      => 'nullable|string|max:20',
            'address'    => 'nullable|string|max:255', // thêm nếu cần
        ]);

        $user = User::create([
            'email'       => $validated['email'],
            'password'    => $validated['password'], // mutator tự hash
            'full_name'   => $validated['full_name'],
            'phone'       => $validated['phone'] ?? null,
            'address'     => $validated['address'] ?? null,
            'is_active'   => 1,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Đăng ký thành công',
            'user'    => $user->only(['user_id', 'email', 'full_name', 'phone', 'address']),
            'token'   => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
            'remember' => 'boolean',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'message' => 'Email hoặc mật khẩu không chính xác.',
            ], 401); // ← Đổi sang 401 Unauthorized
        }

        if (!$user->is_active) {
            return response()->json([
                'message' => 'Tài khoản của bạn đã bị khóa.',
            ], 403);
        }

        // Tùy chọn: Xóa token cũ để chỉ cho 1 thiết bị đăng nhập
        // $user->tokens()->where('name', 'auth_token')->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Đăng nhập thành công',
            'user'    => $user->only(['user_id', 'email', 'full_name', 'phone', 'address']),
            'token'   => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Đăng xuất thành công'
        ]);
    }

}

