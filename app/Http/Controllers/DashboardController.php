<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use Carbon\Carbon; // Library untuk mengelola tanggal

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        
        $selectedDate = $request->input('date', Carbon::today()->toDateString());

        //total transaksi
        $totalTransaksi = Order::whereDate('created_at', $selectedDate)->count();

        //total pembayaran diterima
        $terimaPembayaran = Order::whereDate('created_at', $selectedDate)->sum('total_amount');

        //total produk terjual
        $totalProdukTerjual = OrderDetail::whereHas('order', function ($query) use ($selectedDate) {
            $query->whereDate('created_at', $selectedDate);
        })->sum('quantity');

       
        return view('dashboard', [
            'totalTransaksi' => $totalTransaksi,
            'terimaPembayaran' => $terimaPembayaran,
            'totalProdukTerjual' => $totalProdukTerjual,
            'selectedDate' => $selectedDate 
        ]);
    }
}