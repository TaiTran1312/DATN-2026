<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function total(Request $request)
    {
        $cartItems = Cart::where('user_id', $request->user()->user_id)->with('variant.product')->get();
        $total = $cartItems->sum(function ($item) {
            return ($item->variant->final_price ?? $item->variant->product->final_price) * $item->quantity;
        });

        return response()->json(['total' => $total]);
    }
}
