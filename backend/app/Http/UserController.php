<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'full_name' => 'sometimes|required|string|max:100',
            'phone'     => 'sometimes|nullable|string|max:20',
            'address'   => 'sometimes|nullable|string|max:500',
        ]);

        $user->update($request->only(['full_name', 'phone', 'address']));

        return response()->json([
            'message' => 'Cập nhật thông tin thành công',
            'user'    => $user->fresh(),
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password_hash)) {
            throw ValidationException::withMessages([
                'current_password' => ['Mật khẩu hiện tại không đúng.'],
            ]);
        }

        $user->update([
            'password_hash' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Đổi mật khẩu thành công']);
    }
}
