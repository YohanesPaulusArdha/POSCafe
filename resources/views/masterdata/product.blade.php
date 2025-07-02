@extends('sidebar.SideBar')

@section('title', 'Master Data - Produk')
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
            <span>Master Data / Produk</span>
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
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                <h5 class="card-title mb-2 mb-md-0">Daftar Produk</h5>
                <div class="d-flex align-items-center gap-2">
                    <form action="{{ route('master.products.index') }}" method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control" placeholder="Cari produk..."
                            value="{{ request('search') }}">
                        <button type="submit" class="btn btn-secondary ms-2">Cari</button>
                    </form>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                        <i class="fa-solid fa-plus me-1"></i> Tambah
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%;">No.</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Supplier</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th style="width: 15%;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td>{{ $loop->iteration + $products->firstItem() - 1 }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category?->name ?? 'N/A' }}</td>
                                    <td>{{ $product->supplier?->name ?? 'N/A' }}</td>
                                    <td>Rp. {{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td>{{ $product->stock }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-warning edit-btn" data-bs-toggle="modal"
                                            data-bs-target="#editProductModal" data-id="{{ $product->id }}"
                                            data-name="{{ $product->name }}" data-category-id="{{ $product->category_id }}"
                                            data-supplier-id="{{ $product->supplier_id }}" data-price="{{ $product->price }}"
                                            data-stock="{{ $product->stock }}">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger delete-btn" data-bs-toggle="modal"
                                            data-bs-target="#deleteProductModal" data-id="{{ $product->id }}"
                                            data-name="{{ $product->name }}">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Belum ada data produk.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(isset($products) && $products->hasPages())
                    <div class="card-footer bg-white border-0 mt-3">
                        {{ $products->appends(request()->all())->links() }}
                    </div>
                @endif

            </div>
        </div>
    </main>

    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Tambah Produk Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('master.products.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Produk</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Kategori</label>
                            <select name="category_id" id="category_id" class="form-select" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="supplier_id" class="form-label">Supplier</label>
                            <select name="supplier_id" id="supplier_id" class="form-select">
                                <option value="">Pilih Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Harga</label>
                                <input type="number" class="form-control" id="price" name="price" required min="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="stock" class="form-label">Stok</label>
                                <input type="number" class="form-control" id="stock" name="stock" required min="0">
                            </div>
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

    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Edit Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editProductForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit-name" class="form-label">Nama Produk</label>
                            <input type="text" class="form-control" id="edit-name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-category_id" class="form-label">Kategori</label>
                            <select name="category_id" id="edit-category_id" class="form-select" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit-supplier_id" class="form-label">Supplier</label>
                            <select name="supplier_id" id="edit-supplier_id" class="form-select">
                                <option value="">Pilih Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit-price" class="form-label">Harga</label>
                                <input type="number" class="form-control" id="edit-price" name="price" required min="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit-stock" class="form-label">Stok</label>
                                <input type="number" class="form-control" id="edit-stock" name="stock" required min="0">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteProductModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="deleteProductForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p>Anda yakin ingin menghapus produk <strong id="delete-product-name"></strong>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editProductModal = document.getElementById('editProductModal');
            editProductModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const form = document.getElementById('editProductForm');

                const id = button.dataset.id;
                const name = button.dataset.name;
                const categoryId = button.dataset.categoryId;
                const supplierId = button.dataset.supplierId;
                const price = button.dataset.price;
                const stock = button.dataset.stock;

                form.action = `{{ url('master/products') }}/${id}`;

                form.querySelector('#edit-name').value = name;
                form.querySelector('#edit-category_id').value = categoryId;
                form.querySelector('#edit-supplier_id').value = supplierId;
                form.querySelector('#edit-price').value = price;
                form.querySelector('#edit-stock').value = stock;
            });

            const deleteProductModal = document.getElementById('deleteProductModal');
            deleteProductModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.dataset.id;
                const name = button.dataset.name;

                const form = document.getElementById('deleteProductForm');

                form.action = `{{ url('master/products') }}/${id}`;

                document.getElementById('delete-product-name').textContent = name;
            });
        });
    </script>
@endpush