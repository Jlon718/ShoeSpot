<?php

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Mail;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\CartController;
    use App\Http\Controllers\HomeController;
    use App\Http\Controllers\MailController;
    use App\Http\Controllers\BrandController;
    use App\Http\Controllers\OrderController;
    use App\Http\Controllers\SearchController;
    use App\Http\Controllers\SupplierController;
    use App\Http\Controllers\TransactionController;
    use App\Http\Controllers\UserController;
    use App\Http\Controllers\customer\customerprof;
    use App\Http\Controllers\IndivProductController;
    use App\Http\Controllers\Admin\ProductController;
    use App\Http\Controllers\customer\ShopController;
    use App\Http\Controllers\Admin\DashboardController;
    use App\Http\Controllers\Admin\NotificationController;
    use App\Http\Controllers\Admin\VerificationController;
    use Illuminate\Foundation\Auth\EmailVerificationRequest;

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
    Route::get('/products/info/{id}', [ProductController::class, 'viewproduct'])->name('prodinfo');
    //==========================================================================================

    // Route::get('/customer/cusmanage', [UserController::class, 'manageProfile'])->name('customer.manageProfile');

    // Route::prefix('/customers')->middleware(['auth', 'signed'])->group(function () {
    //     Route::put('/{customer}/update', [CustomerProf::class, 'update'])->name('customer.profile.update');
    // });

    // Route::get('/customer/cusmanage', [UserController::class, 'manageProfile'])->name('customer.manageProfile');
    // Route::put('/users/{customer}/update', [customerprof::class, 'update'])->name('customer.profile.update');
    // Route::put('/customer/{id}', [UserController::class, 'updateProfile'])->name('customer.updateProfile');


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
    Route::post('brand/import', [BrandController::class,'brandsImport']);
    Route::get('brand/import', [BrandController::class,'index']);

    Route::post('product/import', [ProductController::class, 'productsImport']);
    Route::get('product/import', [ProductController::class, 'index']);

    Route::post('supplier/import', [SupplierController::class,'suppliersImport']);
    Route::get('supplier/import', [SupplierController::class,'index']);

    Route::prefix('/stocks')->middleware(['auth', 'isAdmin'])->group(function () {
        Route::view('','admin.stocks.index');
    });

    Route::prefix('/products')->group(function () {
        Route::view('','admin.products.index');
    })->middleware(['auth', 'signed']);

    Route::prefix('/suppliers')->middleware(['auth', 'isAdmin'])->group(function () {
        Route::view('','admin.suppliers.index');
    });

    Route::prefix('/transactions')->middleware(['auth', 'isAdmin'])->group(function () {
        Route::view('','admin.transaction.index');
    });
    Route::put('/transactions', [TransactionController::class, 'updateOrderStatus']);

    Route::view('/chart1', 'admin.chart1');
    Route::view('/chart2', 'admin.chart2');
    Route::view('/chart3', 'admin.chart3');


    Route::prefix('/carts')->group(function () {
        Route::GET('', [CartController::class, 'index'])->name('cart');
        Route::GET('/{id}/add', [CartController::class, 'add_cart'])->name('cart.add');
        route::delete('remove-from-cart', [CartController::class, 'destroy'])->name('remove_from_cart');
        route::patch('update-cart', [CartController::class, 'update'])->name('update_cart');
        Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');
    })->middleware(['auth', 'signed']);

    // Route::get('/search', [SearchController::class, 'index']);

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

        // Route::put('/{customer}/update', [customerprof::class, 'update'])->name('customer.profile.update');
    //     Route::get('/shop', [ShopController::class, 'shop']);
    })->middleware(['auth', 'signed']);
    //(Customer side)

    //==========================================================================================

    Route::get('/transactionCustomer', [TransactionController::class, 'customerIndex'])->name('customer.custransactions');
    Route::get('/api/orders', [TransactionController::class, 'getOrders']);