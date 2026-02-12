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

// Tất cả route trong api.php đã có prefix /api tự động
// Ta thêm prefix 'v1' để versioning → /api/v1/...

Route::prefix('v1')->group(function () {

    // ======================
    // PUBLIC ROUTES (không cần auth)
    // ======================

    // Products
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{slug}', [ProductController::class, 'show']);
    Route::get('/products/search', [ProductController::class, 'search']);

    // Categories
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{slug}', [CategoryController::class, 'show']);

    // Promotions
    Route::get('/promotions/active', [PromotionController::class, 'active']);
    Route::post('/promotions/check', [PromotionController::class, 'checkCode']);

    // Auth
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);


    // ======================
    // PROTECTED ROUTES (yêu cầu đăng nhập Sanctum)
    // ======================
    Route::middleware('auth:sanctum')->group(function () {

        // User / Profile
        Route::get('/user', [AuthController::class, 'me']);           // /api/v1/user → lấy info user hiện tại
        Route::put('/user/profile', [AuthController::class, 'updateProfile']);
        Route::put('/user/password', [AuthController::class, 'updatePassword']);
        Route::post('/user/avatar', [AuthController::class, 'updateAvatar']);

        // Logout
        Route::post('/logout', [AuthController::class, 'logout']);

        // Cart (cho user đăng nhập)
        Route::prefix('cart')->group(function () {
            Route::get('/', [CartController::class, 'index']);
            Route::post('/add', [CartController::class, 'add']);
            Route::put('/update', [CartController::class, 'update']);
            Route::delete('/remove/{variant_id}', [CartController::class, 'remove']);
            Route::delete('/clear', [CartController::class, 'clear']);
            Route::get('/total', [CartController::class, 'total']);
        });

        // Wishlist
        Route::prefix('wishlist')->group(function () {
            Route::get('/', [WishlistController::class, 'index']);
            Route::post('/add/{product_id}', [WishlistController::class, 'add']);
            Route::delete('/remove/{product_id}', [WishlistController::class, 'remove']);
            Route::delete('/clear', [WishlistController::class, 'clear']);
        });

        // Orders
        Route::prefix('orders')->group(function () {
            Route::get('/', [OrderController::class, 'index']);
            Route::get('/{order_id}', [OrderController::class, 'show']);
            Route::post('/', [OrderController::class, 'store']);                    // tạo đơn hàng
            Route::post('/{order_id}/cancel', [OrderController::class, 'cancel']);
            Route::post('/{order_id}/confirm-received', [OrderController::class, 'confirmReceived']);
            Route::post('/checkout/validate', [OrderController::class, 'validateCheckout']); // validate trước khi tạo order
        });

        // Comments / Reviews
        Route::prefix('products/{product_id}/comments')->group(function () {
            Route::post('/', [CommentController::class, 'store']);
        });

        Route::prefix('comments')->group(function () {
            Route::post('/{comment_id}/reply', [CommentController::class, 'reply']);
            Route::put('/{comment_id}', [CommentController::class, 'update']);
            Route::delete('/{comment_id}', [CommentController::class, 'destroy']);
            Route::post('/{comment_id}/like', [CommentController::class, 'like']);
            Route::post('/{comment_id}/unlike', [CommentController::class, 'unlike']);
        });
    });

    // ======================
    // GUEST CART (tùy chọn - cho khách chưa đăng nhập, dùng session)
    // ======================
    // Nếu frontend chưa hỗ trợ guest cart thì comment block này lại
    Route::prefix('guest/cart')->group(function () {
        Route::get('/', [CartController::class, 'guestIndex']);
        Route::post('/add', [CartController::class, 'addGuest']);
        Route::put('/update', [CartController::class, 'updateGuest']);
        Route::delete('/remove/{variant_id}', [CartController::class, 'removeGuest']);
        Route::delete('/clear', [CartController::class, 'clearGuest']);
    });
});
