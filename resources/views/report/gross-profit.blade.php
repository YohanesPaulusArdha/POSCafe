@extends('sidebar.SideBar')

@section('title', 'Laporan - Gross Profit')
@push('styles')
    <style>
        .top-header-bar {
            background-color: #ffffff;
            padding: 0.75rem 1.5rem;
            border-bottom: 1px solid #dee2e6;
            box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075);
        }

        .user-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }
    </style>
@endpush
@section('content')
    <nav class="top-header-bar d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <i class="fa-solid fa-bars me-3" id="sidebar-toggler" style="cursor: pointer;"></i>
            <span>Report / Gross Profit</span>
        </div>
        <div class="d-flex align-items-center">
            <i class="fa-regular fa-bell me-3"></i>
            <div class="user-profile">
                <img src="https://i.pravatar.cc/150?u={{ auth()->id() }}" alt="User Avatar">
            </div>
        </div>
    </nav>

    <main class="main-content">
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card card-custom mb-4">
            <div class="card-body">
                <form action="{{ route('report.gross-profit') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}">
                    </div>
                    <div class="col-md-4">
                        <label for="end_date" class="form-label">Tanggal Selesai</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card text-white bg-danger h-100">
                    <div class="card-body">
                        <h5 class="card-title">Gross Sales (Kotor)</h5>
                        <p class="card-text fs-4 fw-bold">Rp. {{ number_format($grossSales, 0, ',', '.') }}</p>
                        <small>Total seluruh transaksi penjualan sebelum potongan.</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-white bg-success h-100">
                    <div class="card-body">
                        <h5 class="card-title">Nett Sales (Bersih)</h5>
                        <p class="card-text fs-4 fw-bold">Rp. {{ number_format($nettSales, 0, ',', '.') }}</p>
                        <small>Total pendapatan dari penjualan produk saja (sebelum pajak dan service).</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-custom">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                <h5 class="card-title mb-2 mb-md-0">Detail Transaksi Penjualan</h5>
                <div class="d-flex gap-2">
                    <a href="{{ route('report.gross-profit.export-excel', request()->query()) }}" class="btn btn-success"><i
                            class="fa-solid fa-file-csv me-1"></i> Cetak Excel</a>
                    <a href="{{ route('report.gross-profit.export-pdf', request()->query()) }}" class="btn btn-danger"><i
                            class="fa-solid fa-file-pdf me-1"></i> Cetak PDF</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Invoice</th>
                                <th>Tanggal</th>
                                <th>Subtotal (Nett)</th>
                                <th>Pajak & Servis</th>
                                <th>Total (Gross)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td><strong>{{ $order->invoice_number }}</strong></td>
                                    <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                                    @php
                                        $subtotal = $order->details->sum('subtotal');
                                        $taxAndService = $order->total_amount - $subtotal;
                                    @endphp
                                    <td>Rp. {{ number_format($subtotal, 0, ',', '.') }}</td>
                                    <td>Rp. {{ number_format($taxAndService, 0, ',', '.') }}</td>
                                    <td>Rp. {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Tidak ada data transaksi pada rentang
                                        tanggal ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($orders->hasPages())
                    <div class="card-footer bg-white border-0 mt-3">
                        {{ $orders->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </main>
@endsection