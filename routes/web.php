<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\VerificationController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\customer\customerprof;
use App\Http\Controllers\customer\ShopController;
use App\Http\Controllers\IndivProductController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SearchController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//==========================================================================================
//after ma very mapupunta sa home
Auth::routes(['verify' => true]);
Route::view('/home','home')->name('home');
Route::get('/home/search', [SearchController::class, 'index'])->name('search');
//==========================================================================================


//==========================================================================================
//admin dashboard (Admin side)
Route::prefix('admin')->middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/mark-notification-read/{id}', [NotificationController::class, 'markNotificationAsRead'])
    ->name('markNotificationRead');
    Route::get('product', [ProductController::class, 'product']);
    Route::post('admin/products', [ProductController::class, 'store'])->name('products.store');
});

Route::prefix('/brands')->middleware(['auth', 'isAdmin'])->group(function () {
    Route::view('','admin.brands.index');
});
Route::post('brand/import', [App\Http\Controllers\BrandController::class,'brandsImport']);
Route::get('brand/import', [App\Http\Controllers\BrandController::class,'index']);

Route::prefix('/stocks')->middleware(['auth', 'isAdmin'])->group(function () {
    Route::view('','admin.stocks.index');
});

Route::prefix('/products')->group(function () {
    Route::view('','admin.products.index');
})->middleware(['auth', 'signed']);

Route::prefix('/suppliers')->middleware(['auth', 'isAdmin'])->group(function () {
    Route::view('','admin.suppliers.index');
});

Route::view('charts', 'admin.chart');

Route::prefix('/carts')->group(function () {
    Route::GET('', [CartController::class, 'index'])->name('cart');
    Route::GET('/{id}/add', [CartController::class, 'add_cart'])->name('cart.add');
    route::delete('remove-from-cart', [CartController::class, 'destroy'])->name('remove_from_cart');
    route::patch('update-cart', [CartController::class, 'update'])->name('update_cart');
    Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');
})->middleware(['auth', 'signed']);

Route::prefix('/mail')->group(function () {
    Route::GET('/send', [MailController::class, 'sendMail'])->name('sendMail');
})->middleware(['auth', 'signed']);
//pagtapos ma verify sa maitrap ma vevverify na sya sa navbar ng admin dashboard
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); // This will mark the email as verified

    return redirect('/home'); // Redirect the user after verification
})->middleware(['auth', 'signed'])->name('verification.verify');


//==========================================================================================
//==========================================================================================
Route::prefix('/users')->group(function () {
    Route::view('', 'admin.users.index');
//     Route::get('/cusmanage', [customerprof::class, 'customerprof']);
// //customer profile
//     Route::put('/{customer}/update', [customerprof::class, 'update'])->name('customer.profile.update');
//     Route::get('/shop', [ShopController::class, 'shop']);
})->middleware(['auth', 'signed']);
//(Customer side)

//==========================================================================================
