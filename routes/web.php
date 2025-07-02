<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;

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

    //Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //Absensi
    Route::get('/absensi', function () {
        return "<h1>Halaman Absensi</h1>"; 
    })->name('absensi');

    //Buat order    
    Route::post('/orders/store', [POSController::class, 'storeOrder'])->name('orders.store');

    // Master Data
    Route::prefix('master')->name('master.')->group(function () {
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('suppliers', SupplierController::class)->except(['show']);
        Route::resource('products', ProductController::class)->except(['show']);
    });

    //Report
    Route::prefix('report')->name('report.')->group(function () {
        Route::get('/sales', function () {
            return "<h1>Halaman Laporan Penjualan</h1>"; })->name('sales');
    });

    //Inventory
    Route::get('/inventory', function () {
        return "<h1>Halaman Inventory</h1>"; })->name('inventory');

    //Pesanan 
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');

    //Users
    Route::get('/users', function () {
        return "<h1>Halaman Manajemen User</h1>"; })->name('users');
});