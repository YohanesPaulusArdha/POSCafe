@extends('SideBar')

@section('title', 'Dashboard')

@push('styles')
<style>
    .top-navbar {
        background-color: #ffffff;
        padding: 0.75rem 1.5rem;
        border-bottom: 1px solid #dee2e6;
        box-shadow: 0 .125rem .25rem rgba(0,0,0,.075);
    }
    .breadcrumb-bar {
        background-color: #f8f9fa;
        padding: 0.75rem 1.5rem;
    }
    .user-profile img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
    }
    .user-profile .fa-bell {
        font-size: 1.25rem;
        color: #6c757d;
    }
    .breadcrumb-bar .fa-bars {
        font-size: 1.25rem;
        cursor: pointer;
        color: #6c757d;
    }
    .breadcrumb-text {
        color: #6c757d;
    }
    .breadcrumb-text .active {
        color: #343a40;
        font-weight: 600;
    }
    .filter-card {
        background-color: #ffffff;
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
    }
    .btn-generate {
        background-color: #0d6efd;
        border: none;
        color: #fff;
    }
    .stat-card {
        background-color: #ffffff;
        border: 1px solid #dee2e6;
        border-radius: 0.75rem;
        /* Garis warna di atas kartu */
        border-top: 4px solid #0d6efd; 
    }
    .stat-card.card-success {
        border-top-color: #198754;
    }
    .stat-card.card-warning {
        border-top-color: #ffc107;
    }
    .stat-card h6 {
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
    }
    .stat-card p {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0;
        color: #343a40;
    }
</style>
@endpush

@section('content')
<nav class="top-navbar d-flex justify-content-between align-items-center">
    <div></div> <div class="d-flex align-items-center">
        <i class="fa-regular fa-bell me-4"></i>
        <div class="user-profile">
            <img src="https://i.pravatar.cc/150?u={{ auth()->id() }}" alt="User Avatar">
        </div>
    </div>
</nav>

<div class="breadcrumb-bar d-flex align-items-center">
    <i class="fa-solid fa-bars me-3 d-lg-none" id="sidebar-toggler"></i>
    <span class="breadcrumb-text">Dashboard / <span class="active">Ringkasan Hari Ini</span></span>
</div>

<main class="content-main">
    <div class="card filter-card mb-4">
        <div class="card-body">
            <form class="row align-items-end g-3" action="{{ route('dashboard') }}" method="GET">
                <div class="col-md-3">
                    <label for="date" class="form-label">Tampilkan Data Untuk Tanggal:</label>
                    <input type="date" id="date" name="date" class="form-control" value="{{ $selectedDate }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-generate w-100">Tampilkan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="stat-card">
                <h6>Total Transaksi</h6>
                <p>{{ $totalTransaksi }}</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card card-warning">
                <h6>Produk Terjual</h6>
                <p>{{ $totalProdukTerjual }}</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card card-success">
                <h6>Penerimaan</h6>
                <p>Rp. {{ number_format($terimaPembayaran, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')

@endpush