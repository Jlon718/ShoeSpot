<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\IndivProductController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Auth\RegisterController;

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
Route::apiResource('brand', BrandController::class);
Route::apiResource('stock', StockController::class);
Route::apiResource('availableProduct', IndivProductController::class)->only(['index']);
Route::apiResource('availableBrand', IndivProductController::class)->only(['index']);
Route::apiResource('registration', RegisterController::class);
Route::apiResource('homepage', HomeController::class);
Route::apiResource('cart', CartController::class);
Route::apiResource('product', ProductController::class);
Route::apiResource('supplier', SupplierController::class);
Route::apiResource('user', UserController::class);
Route::get('all-brands', [BrandController::class, 'getAll']);
Route::get('all-suppliers', [SupplierController::class, 'getAll']);
Route::post('/login', [LoginController::class, 'login'])->name('api.login');