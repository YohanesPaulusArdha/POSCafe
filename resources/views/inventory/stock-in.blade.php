@extends('sidebar.SideBar')

@section('title', 'Inventory - Stock In')

@push('styles')
    <style>
        .top-header-bar {
            background-color: #ffffff;
            padding: 0.75rem 1.5rem;
            border-bottom: 1px solid #dee2e6;
        }

        .user-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .filter-card {
            background-color: #ffffff;
            border-radius: 0.5rem;
            box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075);
        }

        .summary-card {
            color: #ffffff;
            background-color: rgb(26, 215, 33);
            border: none;
        }

        .summary-card .card-title {
            font-size: 0.9rem;
            font-weight: 600;
            opacity: 0.9;
        }
    </style>
@endpush

@section('content')
    <nav class="top-header-bar d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <i class="fa-solid fa-bars me-3" id="sidebar-toggler" style="cursor: pointer;"></i>
            <span>Inventory / Stock In</span>
        </div>
        <div class="d-flex align-items-center">
            <i class="fa-regular fa-bell me-3"></i>
            <div class="user-profile">
                <img src="https://i.pravatar.cc/150?u={{ auth()->id() }}" alt="User Avatar">
            </div>
        </div>
    </nav>

    <main class="main-content">
        <div class="card filter-card mb-4">
            <div class="card-body">
                <form class="row align-items-end g-3" action="{{ route('inventory.stock-in.index') }}" method="GET">
                    <div class="col-md-4">
                        <label for="date" class="form-label">Tampilkan Data Untuk Tanggal:</label>
                        <input type="date" id="date" name="date" class="form-control" value="{{ $selectedDate }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-secondary w-100">Tampilkan</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card summary-card">
                    <div class="card-body">
                        <h6 class="card-title">Total Stock In ({{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }})
                        </h6>
                        <h2 class="card-text fw-bold">{{ $totalStockIn }} <small class="fs-6 fw-normal">item</small></h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-custom">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                <h5 class="card-title mb-2 mb-md-0">Riwayat Stok Masuk</h5>
                <div class="d-flex align-items-center gap-2">
                    <form action="{{ route('inventory.stock-in.index') }}" method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control" placeholder="Cari produk/supplier..."
                            value="{{ request('search') }}">
                        <input type="hidden" name="date" value="{{ $selectedDate }}">
                        <button type="submit" class="btn btn-secondary ms-2">Cari</button>
                    </form>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStockInModal">
                        <i class="fa-solid fa-plus me-1"></i> Tambah Stok
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Produk</th>
                                <th>Jumlah</th>
                                <th>Supplier</th>
                                <th>Keterangan</th>
                                <th>User</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($stockIns as $item)
                                <tr>
                                    <td>{{ $item->created_at->format('d M Y, H:i') }}</td>
                                    <td>{{ $item->product?->name ?? 'N/A' }}</td>
                                    <td>+{{ $item->quantity }}</td>
                                    <td>{{ $item->supplier?->name ?? '-' }}</td>
                                    <td>{{ $item->remarks ?? '-' }}</td>
                                    <td>{{ $item->user?->name ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-danger delete-btn" data-bs-toggle="modal"
                                            data-bs-target="#deleteStockInModal" data-id="{{ $item->id }}"
                                            data-product-name="{{ $item->product?->name }}"
                                            data-quantity="{{ $item->quantity }}">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Belum ada data stok masuk untuk tanggal ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($stockIns->hasPages())
                    <div class="card-footer bg-white border-0 mt-3">
                        {{ $stockIns->appends(request()->all())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </main>

    <div class="modal fade" id="addStockInModal" tabindex="-1" aria-labelledby="addStockInModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStockInModalLabel">Tambah Stok Masuk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('inventory.stock-in.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="product_id" class="form-label">Produk</label>
                            <select name="product_id" id="product_id" class="form-select" required>
                                <option value="">Pilih Produk...</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }} (Stok: {{ $product->stock }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="supplier_id" class="form-label">Supplier (Opsional)</label>
                            <select name="supplier_id" id="supplier_id" class="form-select">
                                <option value="">Pilih Supplier...</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Jumlah Masuk</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" required min="1">
                        </div>
                        <div class="mb-3">
                            <label for="remarks" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="remarks" name="remarks" rows="2"
                                placeholder="Penjelasan..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteStockInModal" tabindex="-1" aria-labelledby="deleteStockInModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteStockInModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="deleteStockInForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p>Anda yakin ingin menghapus catatan stok masuk untuk:
                            <br><strong><span id="delete-product-name"></span></strong>
                            sebanyak <strong><span id="delete-quantity"></span></strong> item?
                        </p>
                        <p class="text-danger small">Tindakan ini akan mengurangi jumlah stok produk saat ini.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteModal = document.getElementById('deleteStockInModal');
            deleteModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.dataset.id;
                const productName = button.dataset.productName;
                const quantity = button.dataset.quantity;

                const form = document.getElementById('deleteStockInForm');
                form.action = `{{ url('inventory/stock-in') }}/${id}`;

                document.getElementById('delete-product-name').textContent = productName;
                document.getElementById('delete-quantity').textContent = quantity;
            });
        });
    </script>
@endpush