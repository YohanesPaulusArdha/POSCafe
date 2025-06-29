<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\DashboardController;


// login
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// check 
Route::middleware(['auth'])->group(function () {

    Route::get('/home', [POSController::class, 'index'])->name('home');

    // Route untuk Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Route untuk Absensi
    Route::get('/absensi', function() {
        return "<h1>Halaman Absensi</h1>"; // Placeholder
    })->name('absensi');

    // Grup Route untuk Master Data
    Route::prefix('master')->name('master.')->group(function() {
        Route::get('/categories', function() { return "<h1>Halaman Master Kategori</h1>"; })->name('categories');
        Route::get('/products', function() { return "<h1>Halaman Master Produk</h1>"; })->name('products');
        Route::get('/suppliers', function() { return "<h1>Halaman Master Supplier</h1>"; })->name('suppliers');
    });

    // Grup Route untuk Report
    Route::prefix('report')->name('report.')->group(function() {
        Route::get('/sales', function() { return "<h1>Halaman Laporan Penjualan</h1>"; })->name('sales');
    });
    
    // Grup Route untuk Inventory
    // Anda bisa arahkan ke halaman master produk jika fungsinya sama
    Route::get('/inventory', function() { return "<h1>Halaman Inventory</h1>"; })->name('inventory');
    
    // Route untuk Coupon
    Route::get('/coupons', function() { return "<h1>Halaman Kupon</h1>"; })->name('coupons');
    
    // Route untuk Order Pesanan
    Route::get('/orders', function() { return "<h1>Halaman Daftar Order Pesanan</h1>"; })->name('orders');

    // Route untuk Users
    Route::get('/users', function() { return "<h1>Halaman Manajemen User</h1>"; })->name('users');
});
