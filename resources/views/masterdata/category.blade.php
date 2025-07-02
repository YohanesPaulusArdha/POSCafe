@extends('sidebar.SideBar')

@section('title', 'Master Data - Kategori')
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
            <span>Master Data / Kategori</span>
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
                <h5 class="card-title mb-2 mb-md-0">Daftar Kategori Produk</h5>
                <div class="d-flex align-items-center gap-2">
                    <form action="{{ route('master.categories.index') }}" method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control" placeholder="Cari kategori..."
                            value="{{ request('search') }}">
                        <button type="submit" class="btn btn-secondary ms-2">Cari</button>
                    </form>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
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
                                <th>Nama Kategori</th>
                                <th style="width: 15%;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($categories as $category)
                                <tr>
                                    <td>{{ $loop->iteration + $categories->firstItem() - 1 }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-warning edit-btn" data-id="{{ $category->id }}"
                                            data-name="{{ $category->name }}" data-bs-toggle="modal"
                                            data-bs-target="#editCategoryModal">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $category->id }}"
                                            data-name="{{ $category->name }}" data-bs-toggle="modal"
                                            data-bs-target="#deleteCategoryModal">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Belum ada data kategori.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(isset($categories) && $categories->hasPages())
                    <div class="card-footer bg-white border-0 mt-3">
                        {{ $categories->appends(request()->all())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </main>

    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Tambah Kategori Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('master.categories.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" id="name" name="name" required>
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

    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel">Edit Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editCategoryForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit-name" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" id="edit-name" name="name" required>
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

    <div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-labelledby="deleteCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteCategoryModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="deleteCategoryForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p>Anda yakin ingin menghapus kategori <strong id="delete-category-name"></strong>?</p>
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
            const editCategoryModal = document.getElementById('editCategoryModal');
            editCategoryModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');

                const form = document.getElementById('editCategoryForm');
                form.action = `{{ url('master/categories') }}/${id}`;

                form.querySelector('#edit-name').value = name;
            });

            const deleteCategoryModal = document.getElementById('deleteCategoryModal');
            deleteCategoryModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');

                const form = document.getElementById('deleteCategoryForm');
                form.action = `{{ url('master/categories') }}/${id}`;

                document.getElementById('delete-category-name').textContent = name;
            });
        });
    </script>
@endpush