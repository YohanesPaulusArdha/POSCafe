@extends('sidebar.SideBar')

@section('title', 'Master Data - Supplier')
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
@push('styles')
<link rel="stylesheet" href="{{ asset('css/masterdata.css') }}"> @endpush

@section('content')
    <nav class="top-header-bar d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <i class="fa-solid fa-bars me-3" id="sidebar-toggler" style="cursor: pointer;"></i>
            <span>Master Data / Supplier</span>
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
                <h5 class="card-title mb-2 mb-md-0">Daftar Supplier</h5>
                <div class="d-flex align-items-center gap-2">
                    <form action="{{ route('master.suppliers.index') }}" method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control" placeholder="Cari supplier..."
                            value="{{ request('search') }}">
                        <button type="submit" class="btn btn-secondary ms-2">Cari</button>
                    </form>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
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
                                <th>Nama Supplier</th>
                                <th>Telepon</th>
                                <th>Alamat</th>
                                <th style="width: 15%;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($suppliers as $supplier)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $supplier->name }}</td>
                                    <td>{{ $supplier->phone }}</td>
                                    <td>{{ $supplier->address }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-warning edit-btn" data-id="{{ $supplier->id }}"
                                            data-name="{{ $supplier->name }}" data-phone="{{ $supplier->phone }}"
                                            data-address="{{ $supplier->address }}" data-bs-toggle="modal"
                                            data-bs-target="#editSupplierModal">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $supplier->id }}"
                                            data-name="{{ $supplier->name }}" data-bs-toggle="modal"
                                            data-bs-target="#deleteSupplierModal">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Belum ada data supplier.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if(isset($suppliers) && $suppliers->hasPages())
                    <div class="card-footer bg-white border-0 mt-3">
                        {{ $suppliers->appends(request()->all())->links() }}
                    </div>
                @endif

            </div>
        </div>
    </main>

    <div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSupplierModalLabel">Tambah Supplier Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('master.suppliers.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Supplier</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control" id="phone" name="phone">
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat</label>
                            <textarea class="form-control" id="address" name="address" rows="3"></textarea>
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

    <div class="modal fade" id="editSupplierModal" tabindex="-1" aria-labelledby="editSupplierModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSupplierModalLabel">Edit Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editSupplierForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" id="edit-id" name="id">
                        <div class="mb-3">
                            <label for="edit-name" class="form-label">Nama Supplier</label>
                            <input type="text" class="form-control" id="edit-name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-phone" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control" id="edit-phone" name="phone">
                        </div>
                        <div class="mb-3">
                            <label for="edit-address" class="form-label">Alamat</label>
                            <textarea class="form-control" id="edit-address" name="address" rows="3"></textarea>
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

    <div class="modal fade" id="deleteSupplierModal" tabindex="-1" aria-labelledby="deleteSupplierModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteSupplierModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="deleteSupplierForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p>Anda yakin ingin menghapus supplier <strong id="delete-supplier-name"></strong>?</p>
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
            const editSupplierModal = document.getElementById('editSupplierModal');
            editSupplierModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');
                const phone = button.getAttribute('data-phone');
                const address = button.getAttribute('data-address');

                const form = document.getElementById('editSupplierForm');
                form.action = `/master/suppliers/${id}`;

                form.querySelector('#edit-name').value = name;
                form.querySelector('#edit-phone').value = phone;
                form.querySelector('#edit-address').value = address;
            });

            const deleteSupplierModal = document.getElementById('deleteSupplierModal');
            deleteSupplierModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');

                const form = document.getElementById('deleteSupplierForm');
                form.action = `/master/suppliers/${id}`;

                document.getElementById('delete-supplier-name').textContent = name;
            });
        });
    </script>
@endpush