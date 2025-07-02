@extends('sidebar.SideBar')

@section('title', 'Daftar Order Pesanan')

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

        .main-content {
            padding: 1.5rem;
            background-color: #f8f9fc;
            min-height: calc(100vh - 120px);
        }

        .card-custom {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .card-custom .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            font-weight: 700;
            color: #4e73df;
            padding: 1rem 1.25rem;
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fc;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .badge-status {
            font-size: 0.75rem;
            padding: 0.4em 0.7em;
            font-weight: 600;
        }

        .btn-view-details {
            font-size: 0.8rem;
            padding: 0.25rem 0.6rem;
        }

        .modal-header {
            border-bottom: 1px solid #e3e6f0;
        }

        .modal-footer {
            border-top: 1px solid #e3e6f0;
        }

        .modal-body .summary-row p {
            margin-bottom: 0.5rem;
        }

        .modal-body .summary-row .fw-bold {
            color: #4e73df;
        }
    </style>
@endpush

@section('content')
    <nav class="top-header-bar d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <i class="fa-solid fa-bars me-3" id="sidebar-toggler" style="cursor: pointer;"></i>
            <span>Daftar Order Pesanan</span>
        </div>
        <div class="d-flex align-items-center">
            <i class="fa-regular fa-bell me-3"></i>
            <div class="user-profile">
                <img src="https://i.pravatar.cc/150?u={{ auth()->id() }}" alt="User Avatar">
            </div>
        </div>
    </nav>

    <main class="main-content">
        <div class="card card-custom">
            <div class="card-header">
                <h5 class="card-title mb-0">Riwayat Pesanan</h5>
            </div>
            <div class="card-body p-0">
                @if($orders->isEmpty())
                    <div class="text-center py-5">
                        <i class="fa-solid fa-folder-open fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada data pesanan.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">No. Invoice</th>
                                    <th>Kasir</th>
                                    <th>Total Pembayaran</th>
                                    <th>Metode</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th class="text-center">Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td class="ps-4"><strong>{{ $order->invoice_number }}</strong></td>
                                        <td>{{ $order->user->name ?? 'N/A' }}</td>
                                        <td>Rp. {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                        <td>{{ $order->payment_method }}</td>
                                        <td>
                                            <span
                                                class="badge rounded-pill bg-success-subtle text-success-emphasis border border-success-subtle badge-status">{{ $order->status }}</span>
                                        </td>
                                        <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-outline-primary btn-sm btn-view-details" data-bs-toggle="modal"
                                                data-bs-target="#orderDetailModal{{ $order->id }}">
                                                Lihat
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($orders->hasPages())
                        <div class="card-footer bg-white">
                            {{ $orders->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </main>

    @foreach ($orders as $order)
        <div class="modal fade" id="orderDetailModal{{ $order->id }}" tabindex="-1"
            aria-labelledby="orderDetailModalLabel{{ $order->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="orderDetailModalLabel{{ $order->id }}">Detail Pesanan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="text-center mb-3">
                            <h6 class="mb-0"><strong>{{ $order->invoice_number }}</strong></h6>
                            <small class="text-muted">{{ $order->created_at->format('d F Y, H:i:s') }} • Kasir:
                                {{ $order->user->name ?? 'N/A' }}</small>
                        </div>

                        <table class="table table-sm mb-4">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->details as $detail)
                                    <tr>
                                        <td>{{ $detail->product->name ?? 'Produk Dihapus' }}</td>
                                        <td class="text-center">{{ $detail->quantity }}</td>
                                        <td class="text-end">Rp{{ number_format($detail->price, 0, ',', '.') }}</td>
                                        <td class="text-end">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @php
                            $subtotal = $order->details->sum('subtotal');
                            $tax = $subtotal * 0.10;
                            $service = $subtotal * 0.05;
                        @endphp

                        <div class="row summary-row">
                            <div class="col-6 text-muted">Subtotal:</div>
                            <div class="col-6 text-end">Rp. {{ number_format($subtotal, 0, ',', '.') }}</div>

                            <div class="col-6 text-muted">Pajak (10%):</div>
                            <div class="col-6 text-end">Rp. {{ number_format($tax, 0, ',', '.') }}</div>

                            <div class="col-6 text-muted">Servis (5%):</div>
                            <div class="col-6 text-end">Rp. {{ number_format($service, 0, ',', '.') }}</div>

                            <div class="col-12">
                                <hr class="my-2">
                            </div>

                            <div class="col-6 fw-bold">Total:</div>
                            <div class="col-6 text-end fw-bold">Rp. {{ number_format($order->total_amount, 0, ',', '.') }}</div>

                            <div class="col-12">
                                <hr class="my-2 border-2">
                            </div>

                            <div class="col-6 text-muted">Metode:</div>
                            <div class="col-6 text-end">{{ $order->payment_method }}</div>

                            @if($order->payment_method == 'Cash')
                                <div class="col-6 text-muted">Bayar:</div>
                                <div class="col-6 text-end">Rp. {{ number_format($order->amount_paid, 0, ',', '.') }}</div>

                                <div class="col-6 text-muted">Kembali:</div>
                                <div class="col-6 text-end">Rp. {{ number_format($order->change, 0, ',', '.') }}</div>
                            @endif
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary"><i class="fa-solid fa-print me-1"></i> Cetak
                            Struk</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection