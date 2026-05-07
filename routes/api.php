<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductDiscoveryController;
use App\Http\Controllers\Api\CouponController;

/*
|--------------------------------------------------------------------------
| AUTH (Public)
|--------------------------------------------------------------------------
*/
Route::get('/test', function () {
    return response()->json(['message' => 'working']);
});
Route::prefix('auth')->middleware('throttle:60,1')->group(function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

});

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);
//----------------------------------------------------------------------------
// discover <----


Route::prefix('discover')->group(function () {

    //  BEST SELLERS
    Route::get(
        '/best-sellers',
        [ProductDiscoveryController::class, 'bestSellers']
    );

    //  LOW TO HIGH
    Route::get(
        '/price-low-high',
        [ProductDiscoveryController::class, 'lowToHigh']
    );

    //  HIGH TO LOW
    Route::get(
        '/price-high-low',
        [ProductDiscoveryController::class, 'highToLow']
    );

    //  NEW ARRIVALS
    Route::get(
        '/new-arrivals',
        [ProductDiscoveryController::class, 'newArrivals']
    );

    //  MOST VIEWED
    Route::get(
        '/most-viewed',
        [ProductDiscoveryController::class, 'mostViewed']
    );

    //  SEARCH
    Route::get(
        '/search',
        [ProductDiscoveryController::class, 'search']
    );

    //  IN STOCK
    Route::get(
        '/in-stock',
        [ProductDiscoveryController::class, 'inStock']
    );

    //  CATEGORY FILTER
    Route::get(
        '/category/{id}',
        [ProductDiscoveryController::class, 'byCategory']
    );
});


/*
|--------------------------------------------------------------------------
| AUTHENTICATED (User + Admin)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    /*
    |-------------------------
    | AUTH
    |-------------------------
    */
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/me', function () {
        return auth()->user()->load('role');
    });
    /*
    |-------------------------
    | payment
    |-------------------------
    */
    Route::post('/payments', [PaymentController::class, 'store']);
    /*
    |-------------------------
    | CART (User only)
    |-------------------------
    */
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::put('/cart', [CartController::class, 'update']);
    Route::delete('/cart', [CartController::class, 'clear']);

    // Wishlist
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle']);
    Route::get('/wishlist', [WishlistController::class, 'index']);
    /*
    |-------------------------
    | CHECKOUT (Cart → Order)
    |-------------------------
    */
    // preview coupon
    Route::post('/coupons/apply', [CouponController::class, 'apply']);

    // real checkout
    Route::post('/orders/checkout', [OrderController::class, 'checkout']);
    /*
    |-------------------------
    | ORDERS (User + Admin logic inside service)
    |-------------------------
    */
    Route::get('/orders', [OrderController::class, 'index']);        // user: own | admin: all
    Route::get('/orders/{order}', [OrderController::class, 'show']); // user: own | admin: any
    Route::delete('/orders/{order}', [OrderController::class, 'destroy']); // user cancel


    /*
    |-------------------------
    | ADMIN ONLY
    |-------------------------
    */
    Route::middleware('admin')->group(function () {

        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{category}', [CategoryController::class, 'update']);
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

        // PRODUCTS MANAGEMENT
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{product}', [ProductController::class, 'update']);
        Route::delete('/products/{product}', [ProductController::class, 'destroy']);

        // ORDERS MANAGEMENT
        Route::put('/orders/{order}', [OrderController::class, 'update']); // change status

    });

});