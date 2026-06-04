<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController; 
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController; 
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 1. HALAMAN UTAMA (PUBLIC)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [HomeController::class, 'products'])->name('products.index');
Route::get('/products/{id}', [HomeController::class, 'detail'])->name('products.detail');
Route::get('/category/{slug}', [HomeController::class, 'category'])->name('category.show');

// Rute API untuk Live Search / Autocomplete
Route::get('/api/search-products', function (Illuminate\Http\Request $request) {
    $keyword = $request->input('query');
    
    if (!$keyword) {
        return response()->json([]);
    }

    $products = App\Models\Product::where('name', 'like', '%' . $keyword . '%')
        ->take(5)
        ->get(['id', 'name', 'price', 'image']);

    return response()->json($products);
});

/*
|--------------------------------------------------------------------------
| 2. DASHBOARD USER & PROFILE (AUTH)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profil Pengguna
    Route::get('/profile', function () {
        return view('profile'); 
    })->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Keranjang Belanja
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');

    // --- FITUR CHECKOUT & ONGKIR KOMERCE ---
    Route::match(['get', 'post'], '/checkout', [CheckoutController::class, 'index'])->name('checkout.index'); 
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    
    // RUTE API LOKASI & ONGKIR
    Route::get('/api/search-destination', [CheckoutController::class, 'searchDestination'])->name('api.search.destination');
    Route::post('/api/check-ongkir', [CheckoutController::class, 'checkOngkir'])->name('api.check.ongkir');

    // Daftar Pesanan Pelanggan
    Route::get('/orders', [App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}/payment', [App\Http\Controllers\OrderController::class, 'payment'])->name('orders.payment');
    Route::post('/orders/{id}/receive', [App\Http\Controllers\OrderController::class, 'receiveOrder'])->name('orders.receive');
});

/*
|--------------------------------------------------------------------------
| 3. PANEL ADMIN (AUTH + ADMIN ROLE)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/sales-chart', [DashboardController::class, 'getChartData'])->name('dashboard.chart');
    Route::get('/api/products-report', [DashboardController::class, 'getProductsReport'])->name('dashboard.products-report');
    Route::get('/api/monthly-sales-report', [DashboardController::class, 'getMonthlySalesReport'])->name('dashboard.monthly-sales-report');
    Route::get('/api/revenue-report', [DashboardController::class, 'getRevenueReport'])->name('dashboard.revenue-report');

    // Manajemen Produk (CRUD)
    Route::resource('products', ProductController::class);

    // Manajemen Kategori (Sub-Kategori)
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Manajemen Metode Pembayaran (CRUD)
    Route::resource('payment-methods', \App\Http\Controllers\Admin\PaymentMethodController::class);

    // --- MANAJEMEN PESANAN & TRANSAKSI ---
    Route::get('/transaksi', [OrderController::class, 'transaksi'])->name('orders.transaksi');
    Route::post('/transaksi/{id}/konfirmasi', [OrderController::class, 'konfirmasiPembayaran'])->name('orders.konfirmasi');
    Route::delete('/transaksi/{id}/batal', [OrderController::class, 'batalkanPesanan'])->name('orders.batal');
    
    Route::get('/pesanan', [OrderController::class, 'pesanan'])->name('orders.pesanan');
    Route::post('/pesanan/{id}/resi', [OrderController::class, 'updateResi'])->name('orders.resi');
    Route::get('/pesanan/{id}/print', [OrderController::class, 'printOrder'])->name('orders.print');
    
    Route::get('/pesanan-selesai', [OrderController::class, 'selesai'])->name('orders.selesai');
    
    // User Management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::patch('/users/{id}/toggle-block', [UserController::class, 'toggleBlock'])->name('users.toggle-block');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
});

require __DIR__.'/auth.php';