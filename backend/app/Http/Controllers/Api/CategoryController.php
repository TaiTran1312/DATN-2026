<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products') // đếm số sản phẩm
            ->where('is_active', 1)
            ->get()
            ->map(function ($cat) {
                $cat->product_count = $cat->products_count;
                return $cat;
            });

        return response()->json($categories);
    }
}
