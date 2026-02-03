<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PromotionController;
use App\Http\Controllers\Api\UserController;

Route::prefix('v1')->group(function () {

    // ======================
    // PUBLIC ROUTES (không cần đăng nhập)
    // ======================
    // Products
    Route::get('/products', [ProductController::class, 'index']);               // Danh sách + filter (hot/new/sale/category/price/search)
    Route::get('/products/{slug}', [ProductController::class, 'show']);         // Chi tiết sản phẩm
    Route::get('/search', [ProductController::class, 'search']);                     // Tìm kiếm nhanh
    Route::get('/products/search', [ProductController::class, 'search']);       // Tìm kiếm nhanh

    // Categories
    Route::get('/categories', [CategoryController::class, 'index']);            // Danh sách danh mục
    Route::get('/categories/{slug}', [CategoryController::class, 'show']);      // Chi tiết danh mục + sản phẩm trong danh mục

    // Promotions
    Route::get('/promotions/active', [PromotionController::class, 'active']);   // Danh sách mã khuyến mãi đang hoạt động
    Route::post('/promotions/check', [PromotionController::class, 'checkCode']); // Kiểm tra mã giảm giá hợp lệ (dùng khi checkout)

    // Auth
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

    // ======================
    // PROTECTED ROUTES (yêu cầu đăng nhập + token)
    // ======================
    Route::middleware('auth:sanctum')->group(function () {

        // User / Profile
        Route::get('/user', [UserController::class, 'me']);                     // Thông tin user hiện tại
        Route::put('/user/profile', [UserController::class, 'updateProfile']);  // Cập nhật thông tin (tên, sđt, địa chỉ)
        Route::put('/user/password', [UserController::class, 'updatePassword']); // Đổi mật khẩu
        Route::post('/user/avatar', [UserController::class, 'updateAvatar']);   // Upload avatar (nếu cần)

        // Cart (giỏ hàng)
        Route::get('/cart', [CartController::class, 'index']);                  // Lấy giỏ hàng
        Route::post('/cart/add', [CartController::class, 'add']);               // Thêm sản phẩm (variant_id + quantity)
        Route::put('/cart/update', [CartController::class, 'update']);          // Cập nhật số lượng
        Route::delete('/cart/remove/{variant_id}', [CartController::class, 'remove']); // Xóa item
        Route::delete('/cart/clear', [CartController::class, 'clear']);         // Xóa toàn bộ giỏ
        Route::get('/cart/total', [CartController::class, 'total']);            // Tính tổng tiền (có thể áp dụng promo)

        // Wishlist (yêu thích)
        Route::get('/wishlist', [WishlistController::class, 'index']);          // Danh sách yêu thích
        Route::post('/wishlist/add/{product_id}', [WishlistController::class, 'add']);     // Thêm vào yêu thích
        Route::delete('/wishlist/remove/{product_id}', [WishlistController::class, 'remove']); // Xóa khỏi yêu thích
        Route::delete('/wishlist/clear', [WishlistController::class, 'clear']); // Xóa toàn bộ yêu thích

        // Orders (đơn hàng)
        Route::get('/orders', [OrderController::class, 'index']);               // Danh sách đơn hàng của user
        Route::get('/orders/{order_id}', [OrderController::class, 'show']);     // Chi tiết đơn hàng
        Route::post('/orders', [OrderController::class, 'store']);              // Tạo đơn hàng mới (checkout)
        Route::post('/orders/{order_id}/cancel', [OrderController::class, 'cancel']); // Hủy đơn (nếu còn pending)
        Route::post('/orders/{order_id}/confirm-received', [OrderController::class, 'confirmReceived']); // Xác nhận đã nhận hàng

        // Comments / Reviews
        Route::post('/products/{product_id}/comments', [CommentController::class, 'store']); // Viết bình luận mới
        Route::post('/comments/{comment_id}/reply', [CommentController::class, 'reply']);    // Trả lời bình luận
        Route::put('/comments/{comment_id}', [CommentController::class, 'update']);          // Sửa bình luận
        Route::delete('/comments/{comment_id}', [CommentController::class, 'destroy']);      // Xóa bình luận
        Route::post('/comments/{comment_id}/like', [CommentController::class, 'like']);      // Like bình luận
        Route::post('/comments/{comment_id}/unlike', [CommentController::class, 'unlike']);  // Unlike

        // Checkout & Payment (nếu cần bước riêng)
        Route::post('/checkout/validate', [OrderController::class, 'validateCheckout']); // Validate trước khi tạo order (stock, promo, ...)
    });
});
