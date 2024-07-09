<?php

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IndivProductController;
use App\Http\Controllers\StockController;

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
Route::apiResource('registration', RegisterController::class);
Route::apiResource('homepage', HomeController::class);
Route::apiResource('cart', CartController::class);
Route::post('/login', [LoginController::class, 'login'])->name('api.login');