<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Attendance; 
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GrossProfitExport;
use App\Exports\PaymentMethodExport;
use App\Exports\AbsensiExport; 
class ReportController extends Controller
{
    public function grossProfit(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        $ordersQuery = Order::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59']);
        $grossSales = (clone $ordersQuery)->sum('total_amount');
        $nettSales = OrderDetail::whereHas('order', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59']);
        })->sum('subtotal');
        $orders = (clone $ordersQuery)->with(['details.product', 'user'])->orderBy('created_at', 'desc')->paginate(15);
        return view('report.gross-profit', compact('grossSales', 'nettSales', 'orders', 'startDate', 'endDate'));
    }

    public function paymentMethod(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        $paymentMethods = Order::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->select('payment_method', DB::raw('COUNT(*) as transaction_count'), DB::raw('SUM(total_amount) as total_amount'))
            ->groupBy('payment_method')->orderBy('transaction_count', 'desc')->get();
        return view('report.payment-method', compact('paymentMethods', 'startDate', 'endDate'));
    }

    public function absensi(Request $request)
    {
        $isFeatureReady = true; 
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $attendances = Attendance::whereBetween('clock_in', [$startDate, $endDate . ' 23:59:59'])
            ->with('user') 
            ->orderBy('clock_in', 'desc')
            ->get();

        return view('report.absensi', compact('attendances', 'startDate', 'endDate', 'isFeatureReady'));
    }

    public function exportGrossProfitExcel(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $grossSales = Order::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])->sum('total_amount');
        $nettSales = OrderDetail::whereHas('order', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59']);
        })->sum('subtotal');
        $fileName = "Laporan-Gross-Profit-{$startDate}-sd-{$endDate}.xlsx";
        return Excel::download(new GrossProfitExport($startDate, $endDate, $nettSales, $grossSales), $fileName);
    }

    public function exportGrossProfitPdf(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        $orders = Order::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->with(['details.product', 'user'])->orderBy('created_at', 'desc')->get();
        $pdf = Pdf::loadView('report.pdf.gross-profit', compact('orders', 'startDate', 'endDate'));
        return $pdf->download('Laporan-Laba-Kotor.pdf');
    }

    public function exportPaymentMethodExcel(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        $fileName = "Laporan-Metode-Pembayaran-{$startDate}-sd-{$endDate}.xlsx";
        return Excel::download(new PaymentMethodExport($startDate, $endDate), $fileName);
    }

    public function exportPaymentMethodPdf(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        $paymentMethods = Order::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->select('payment_method', DB::raw('COUNT(*) as transaction_count'), DB::raw('SUM(total_amount) as total_amount'))
            ->groupBy('payment_method')->orderBy('transaction_count', 'desc')->get();
        $pdf = Pdf::loadView('report.pdf.payment-method', compact('paymentMethods', 'startDate', 'endDate'));
        return $pdf->download('Laporan-Metode-Pembayaran.pdf');
    }

    public function exportAbsensiExcel(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        $fileName = "Laporan-Absensi-{$startDate}-sd-{$endDate}.xlsx";
        return Excel::download(new AbsensiExport($startDate, $endDate), $fileName);
    }

    public function exportAbsensiPdf(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $attendances = Attendance::whereBetween('clock_in', [$startDate, $endDate . ' 23:59:59'])
            ->with('user')
            ->orderBy('clock_in', 'asc')
            ->get();

        $pdf = Pdf::loadView('report.pdf.absensi', compact('attendances', 'startDate', 'endDate'));
        return $pdf->download("Laporan-Absensi-{$startDate}-sd-{$endDate}.pdf");
    }
}