<?php

use App\Http\Controllers\Api\Customer\AuthController;
use App\Http\Controllers\Api\Customer\BillingAddressController;
use App\Http\Controllers\Api\Customer\CartController;
use App\Http\Controllers\Api\Customer\CategoryController;
use App\Http\Controllers\Api\Customer\ChatController;
use App\Http\Controllers\Api\Customer\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

// Route::middleware('auth')->group(function () {
Route::get('categories', [CategoryController::class, 'index']);
Route::get('products', [ProductController::class, 'index']);

Route::post('billing-address', [BillingAddressController::class, 'store']);
Route::put('billing-address/{id}', [BillingAddressController::class, 'update']);
Route::delete('billing-address/{id}', [BillingAddressController::class, 'destroy']);
Route::get('billing-address', [BillingAddressController::class, 'index']);

Route::get('cart', [CartController::class, 'index']);
Route::post('cart', [CartController::class, 'store']);
Route::delete('cart/{id}', [CartController::class, 'destroy']);

Route::get('chats', [ChatController::class, 'index']);
Route::post('chats', [ChatController::class, 'store']);
// });
