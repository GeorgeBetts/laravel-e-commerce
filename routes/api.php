<?php

use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShoppingCartController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * Products
 */
Route::resource('products', ProductController::class)
    ->only(['index', 'show']);
Route::resource('products', ProductController::class)
    ->only(['store', 'update', 'destroy'])
    ->middleware(['auth:sanctum']);

/**
 * Shopping Cart
 */
Route::controller(ShoppingCartController::class)->group(function () {
    Route::get('/cart', 'index');
    Route::post('/cart', 'store');
    Route::delete('/cart', 'destroy');
});

/**
 * Orders
 */
Route::post('/checkout', [OrderController::class, 'store'])
    ->middleware(['auth:sanctum']);
