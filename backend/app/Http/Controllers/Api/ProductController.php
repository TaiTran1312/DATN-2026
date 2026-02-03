<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Danh sách sản phẩm (trang /products + trang chủ filter)
     */
    public function index(Request $request)
{
    $query = Product::query()
        ->with(['variants', 'category'])
        ->where('is_visible', 1);

    /* ================= FILTER TRANG CHỦ ================= */
    $filter = $request->input('filter');
    if ($filter === 'hot') {
        $query->orderBy('likes_count', 'desc');
    } elseif ($filter === 'new') {
        $query->orderBy('created_at', 'desc');
    } elseif ($filter === 'sale') {
        $query->whereNotNull('discount_price')
              ->whereRaw('discount_price < base_price')
              ->orderByRaw('(base_price - discount_price) DESC');
    }

    /* ================= FILTER SIDEBAR ================= */
    if ($request->filled('category_id')) {
        $ids = array_filter(explode(',', $request->category_id));
        if ($ids) {
            $query->whereIn('category_id', $ids);
        }
    }

    if ($request->filled('min_price')) {
        $query->where('base_price', '>=', $request->min_price);
    }

    if ($request->filled('max_price')) {
        $query->where('base_price', '<=', $request->max_price);
    }

    /* ================= SEARCH ================= */
    if ($request->filled('search')) {
        $search = mb_strtolower(trim($request->search));

        $query->where(function ($q) use ($search) {
            $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
              ->orWhereRaw('LOWER(short_desc) LIKE ?', ["%{$search}%"])
              ->orWhereRaw('LOWER(description) LIKE ?', ["%{$search}%"]);
        });
    }

    /* ================= SORT ================= */
    $sort = $request->input('sort', 'newest');
    switch ($sort) {
        case 'price_asc':
            $query->orderBy('base_price', 'asc');
            break;
        case 'price_desc':
            $query->orderBy('base_price', 'desc');
            break;
        case 'bestseller':
            $query->orderBy('likes_count', 'desc');
            break;
        case 'rating':
            $query->orderBy('average_rating', 'desc');
            break;
        case 'newest':
        default:
            $query->orderBy('created_at', 'desc');
            break;
    }

    /* ================= PAGINATION ================= */
    $perPage = $request->input('per_page', 12);
    $products = $query->paginate($perPage);

    /* ================= TRANSFORM ================= */
    $products->getCollection()->transform(function ($p) {
        $p->final_price = $p->discount_price ?? $p->base_price;
        return $p;
    });

    return response()->json($products);
}


    /**
     * Chi tiết sản phẩm theo slug
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_visible', 1)
            ->with([
                'variants',
                'category',
                'comments' => function ($q) {
                    $q->whereNull('parent_id')
                      ->with(['user', 'replies.user'])
                      ->orderBy('created_at', 'desc');
                }
            ])
            ->firstOrFail();

        // Thêm final_price
        $product->final_price = $product->discount_price ?? $product->base_price;

        // Tính average_rating động
        $product->average_rating = $product->comments->isNotEmpty()
            ? $product->comments->avg('rating') ?? 5
            : 0;

        return response()->json($product);
    }

    public function search(Request $request)
{
    $q = trim($request->input('q', ''));
    $limit = (int) $request->input('limit', 8);

    if ($q === '') {
        return response()->json([
            'data' => [],
            'total' => 0
        ]);
    }

    $keyword = mb_strtolower($q);

    $products = Product::query()
        ->where('is_visible', 1)
        ->whereRaw('LOWER(name) LIKE ?', ["%{$keyword}%"])
        ->select('id','name','slug','base_price','discount_price','thumbnail')
        ->orderByRaw(
            "CASE
                WHEN LOWER(name) LIKE ? THEN 1
                WHEN LOWER(name) LIKE ? THEN 2
                ELSE 3
            END",
            ["{$keyword}%", "%{$keyword}%"]
        )
        ->limit($limit)
        ->get();

    return response()->json([
        'data' => $products->map(fn ($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'slug' => $p->slug,
            'price' => $p->discount_price ?? $p->base_price,
            'formatted_price' =>
                number_format($p->discount_price ?? $p->base_price, 0, ',', '.') . '₫',
            'thumbnail' => $p->thumbnail
                ? asset('storage/' . $p->thumbnail)
                : asset('images/placeholder.png'),
        ]),
        'total' => $products->count(),
    ]);
}



}
