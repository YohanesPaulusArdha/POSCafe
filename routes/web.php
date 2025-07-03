<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockInController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\UserController;


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
    Route::controller(AttendanceController::class)->prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/clock-in', 'clockIn')->name('clock-in');
        Route::post('/clock-out', 'clockOut')->name('clock-out');
    });

    //Buat order    
    Route::post('/orders/store', [POSController::class, 'storeOrder'])->name('orders.store');

    // Master Data
    Route::prefix('master')->name('master.')->group(function () {
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('suppliers', SupplierController::class)->except(['show']);
        Route::resource('products', ProductController::class)->except(['show']);
    });

    //Stock in
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('stock-in', [StockInController::class, 'index'])->name('stock-in.index');
        Route::post('stock-in', [StockInController::class, 'store'])->name('stock-in.store');
        Route::delete('stock-in/{stockIn}', [StockInController::class, 'destroy'])->name('stock-in.destroy');
    });

    //Report
    Route::prefix('report')->name('report.')->group(function () {
        Route::get('/gross-profit', [ReportController::class, 'grossProfit'])->name('gross-profit');
        Route::get('/payment-method', [ReportController::class, 'paymentMethod'])->name('payment-method');
        Route::get('/absensi', [ReportController::class, 'absensi'])->name('absensi');

        //Print excel, pdf
        Route::get('/gross-profit/export-excel', [ReportController::class, 'exportGrossProfitExcel'])->name('gross-profit.export-excel');
        Route::get('/gross-profit/export-pdf', [ReportController::class, 'exportGrossProfitPdf'])->name('gross-profit.export-pdf');
        Route::get('/payment-method/export-excel', [ReportController::class, 'exportPaymentMethodExcel'])->name('payment-method.export-excel');
        Route::get('/payment-method/export-pdf', [ReportController::class, 'exportPaymentMethodPdf'])->name('payment-method.export-pdf');
        Route::get('/absensi/export-excel', [ReportController::class, 'exportAbsensiExcel'])->name('absensi.export-excel');
        Route::get('/absensi/export-pdf', [ReportController::class, 'exportAbsensiPdf'])->name('absensi.export-pdf');
    });

    //Pesanan 
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');

    //Users
    Route::resource('users', UserController::class)->except(['show']);
});