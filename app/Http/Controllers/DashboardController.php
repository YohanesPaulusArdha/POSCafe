<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $selectedDate = $request->input('date', Carbon::today()->toDateString());

        $totalTransaksi = Order::whereDate('created_at', $selectedDate)->count();
        $terimaPembayaran = Order::whereDate('created_at', $selectedDate)->sum('total_amount');
        $totalProdukTerjual = OrderDetail::whereHas('order', function ($query) use ($selectedDate) {
            $query->whereDate('created_at', $selectedDate);
        })->sum('quantity');


        $dailySales = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_amount) as total')
        )
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $dailySalesLabels = $dailySales->map(fn($item) => Carbon::parse($item->date)->format('d M'));
        $dailySalesData = $dailySales->map(fn($item) => $item->total);


        $monthlySales = Order::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(total_amount) as total')
        )
            ->where('created_at', '>=', Carbon::now()->subYear())
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')->orderBy('month', 'asc')
            ->get();

        $monthlySalesLabels = $monthlySales->map(fn($item) => Carbon::create()->month($item->month)->format('M') . ' ' . $item->year);
        $monthlySalesData = $monthlySales->map(fn($item) => $item->total);


        $yearlySales = Order::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('SUM(total_amount) as total')
        )
            ->groupBy('year')
            ->orderBy('year', 'asc')
            ->get();

        $yearlySalesLabels = $yearlySales->map(fn($item) => $item->year);
        $yearlySalesData = $yearlySales->map(fn($item) => $item->total);


        return view('Dashboard', [
            'selectedDate' => $selectedDate,
            'totalTransaksi' => $totalTransaksi,
            'terimaPembayaran' => $terimaPembayaran,
            'totalProdukTerjual' => $totalProdukTerjual,
            'dailySalesLabels' => $dailySalesLabels,
            'dailySalesData' => $dailySalesData,
            'monthlySalesLabels' => $monthlySalesLabels,
            'monthlySalesData' => $monthlySalesData,
            'yearlySalesLabels' => $yearlySalesLabels,
            'yearlySalesData' => $yearlySalesData,
        ]);
    }
}