<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;

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
    | PRODUCTS (Read only for users)
    |-------------------------
    */
    


    /*
    |-------------------------
    | CART (User only)
    |-------------------------
    */
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::put('/cart', [CartController::class, 'update']);
    Route::delete('/cart', [CartController::class, 'clear']);


    /*
    |-------------------------
    | CHECKOUT (Cart → Order)
    |-------------------------
    */
    Route::post('/checkout', [OrderController::class, 'checkout']);


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

        // PRODUCTS MANAGEMENT
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{product}', [ProductController::class, 'update']);
        Route::delete('/products/{product}', [ProductController::class, 'destroy']);

        // ORDERS MANAGEMENT
        Route::put('/orders/{order}', [OrderController::class, 'update']); // change status

    });

});