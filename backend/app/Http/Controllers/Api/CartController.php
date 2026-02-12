<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartController extends Controller
{
    /**
     * Lấy danh sách giỏ hàng của user hiện tại (hoặc guest qua session)
     */
    public function index(Request $request)
    {
        $user = Auth::guard('sanctum')->user();

        if ($user) {
            // User đã đăng nhập: lấy theo user_id
            $items = CartItem::with(['product', 'variant'])
                ->where('user_id', $user->id)
                ->get();
        } else {
            // Guest: lấy theo session_id
            $sessionId = $request->session()->getId();
            $items = CartItem::with(['product', 'variant'])
                ->where('session_id', $sessionId)
                ->whereNull('user_id')
                ->get();
        }

        $subtotal = $items->sum(function ($item) {
            $price = $item->variant ? $item->variant->final_price : $item->product->base_price;
            return $price * $item->quantity;
        });

        return response()->json([
            'items' => $items,
            'subtotal' => $subtotal,
            'total_items' => $items->count(),
        ]);
    }

    /**
     * Thêm sản phẩm vào giỏ (hỗ trợ cả user và guest)
     */
    public function add(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:product_variants,variant_id',
            'quantity' => 'required|integer|min:1',
        ]);

        $variant = ProductVariant::findOrFail($request->variant_id);

        $user = Auth::guard('sanctum')->user();
        $cartItem = null;

        if ($user) {
            // User đăng nhập: kiểm tra xem đã có item chưa
            $cartItem = CartItem::where('user_id', $user->id)
                ->where('variant_id', $variant->variant_id)
                ->first();

            if ($cartItem) {
                $cartItem->quantity += $request->quantity;
                $cartItem->save();
            } else {
                $cartItem = CartItem::create([
                    'user_id' => $user->id,
                    'product_id' => $variant->product_id,
                    'variant_id' => $variant->variant_id,
                    'quantity' => $request->quantity,
                ]);
            }
        } else {
            // Guest: dùng session_id
            $sessionId = $request->session()->getId();

            $cartItem = CartItem::where('session_id', $sessionId)
                ->where('variant_id', $variant->variant_id)
                ->whereNull('user_id')
                ->first();

            if ($cartItem) {
                $cartItem->quantity += $request->quantity;
                $cartItem->save();
            } else {
                $cartItem = CartItem::create([
                    'session_id' => $sessionId,
                    'product_id' => $variant->product_id,
                    'variant_id' => $variant->variant_id,
                    'quantity' => $request->quantity,
                ]);
            }
        }

        return response()->json([
            'message' => 'Đã thêm vào giỏ hàng',
            'cart_item' => $cartItem,
        ], 201);
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ
     */
    public function update(Request $request)
{
    $validated = $request->validate([
        'id'         => 'required|exists:cart_items,id',
        'quantity'   => 'required|integer|min:1',           // chỉ yêu cầu min:1, không giới hạn max
        'variant_id' => 'nullable|exists:product_variants,id', // hoàn toàn tùy chọn
    ]);

    $cartItem = CartItem::findOrFail($validated['id']);
    $user = Auth::guard('sanctum')->user();

    // Cập nhật số lượng và variant_id (nếu gửi)
    $cartItem->update([
        'quantity'   => $validated['quantity'],
        'variant_id' => $validated['variant_id'] ?? $cartItem->variant_id, // giữ nguyên nếu không gửi
    ]);

    // Tùy chọn: reload item với quan hệ để trả về thông tin đầy đủ
    $cartItem->load(['variant', 'variant.product']);

    return response()->json([
        'message' => 'Cập nhật số lượng thành công',
        'item'    => $cartItem,
    ]);
}

    /**
     * Xóa một sản phẩm khỏi giỏ
     */
    public function remove($id)
    {
        $cartItem = CartItem::findOrFail($id);

        $cartItem->delete();

        return response()->json(['message' => 'Xóa sản phẩm thành công'], 200);
    }
    /**
     * Xóa toàn bộ giỏ hàng
     */
    public function clear(Request $request)
    {
        $user = Auth::guard('sanctum')->user();

        if ($user) {
            CartItem::where('user_id', $user->id)->delete();
        } else {
            CartItem::where('session_id', $request->session()->getId())
                    ->whereNull('user_id')
                    ->delete();
        }

        return response()->json(['message' => 'Đã xóa toàn bộ giỏ hàng']);
    }

    /**
     * Tính tổng số lượng và giá trị giỏ hàng
     */
    public function total(Request $request)
    {
        $user = Auth::guard('sanctum')->user();

        $query = CartItem::query();

        if ($user) {
            $query->where('user_id', $user->id);
        } else {
            $query->where('session_id', $request->session()->getId())
                  ->whereNull('user_id');
        }

        $totalQuantity = $query->sum('quantity');
        $totalPrice = $query->get()->sum(function ($item) {
            $price = $item->variant ? $item->variant->final_price : $item->product->base_price;
            return $price * $item->quantity;
        });

        return response()->json([
            'total_quantity' => $totalQuantity,
            'total_price' => $totalPrice,
        ]);
    }
}
