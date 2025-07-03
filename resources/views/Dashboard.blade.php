@extends('sidebar.SideBar')

@section('title', 'Dashboard')

@push('styles')
    <style>
        .main-content {
            padding: 1.25rem;
            background-color: #f8f9fa;
            width: 100%;
            overflow-x: hidden;
        }

        .top-navbar {
            background-color: #ffffff;
            padding: 0.75rem 1.5rem;
            border-bottom: 1px solid #dee2e6;
        }

        .user-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .stat-card {
            background-color: #fff;
            border-radius: 0.75rem;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            padding: 1.25rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }

        .stat-card .icon-container {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #fff;
            flex-shrink: 0;
        }

        .stat-card .icon-blue {
            background-color: #3b82f6;
        }

        .stat-card .icon-yellow {
            background-color: #f59e0b;
        }

        .stat-card .icon-green {
            background-color: #10b981;
        }

        .stat-card .info h6 {
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            margin-bottom: 0.25rem;
        }

        .stat-card .info p {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0;
            color: #1f2937;
        }

        .chart-card {
            background-color: #ffffff;
            border-radius: 0.75rem;
            padding: 1.5rem;
            border: 1px solid #e2e8f0;
        }

        .chart-container {
            position: relative;
            width: 100%;
            aspect-ratio: 2 / 1;
        }

        @media (max-width: 768px) {
            .chart-container {
                aspect-ratio: 1.5 / 1;
            }
        }

        @media (max-width: 576px) {
            .btn-group {
                width: 100%;
                flex-direction: column;
            }

            .btn-group .btn {
                margin-bottom: 0.5rem;
                border-radius: 0.375rem !important;
            }

            .chart-container {
                aspect-ratio: 1 / 1;
            }
        }
    </style>
@endpush

@section('content')
    <nav class="top-navbar d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <i class="fa-solid fa-bars me-3" id="sidebar-toggler" style="cursor: pointer;"></i>
            <span>Dashboard</span>
        </div>
        <div class="d-flex align-items-center">
            <i class="fa-regular fa-bell me-4"></i>
            <div class="user-profile">
                <img src="https://i.pravatar.cc/150?u={{ auth()->id() }}" alt="User Avatar">
            </div>
        </div>
    </nav>

    <main class="main-content">
        <div class="card filter-card mb-4">
            <div class="card-body">
                <form class="row align-items-end g-3" action="{{ route('dashboard') }}" method="GET">
                    <div class="col-md-4 col-sm-12">
                        <label for="date" class="form-label">Tampilkan Statistik Harian Untuk:</label>
                        <input type="date" id="date" name="date" class="form-control" value="{{ $selectedDate }}">
                    </div>
                    <div class="col-md-auto col-sm-12">
                        <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-4 col-md-6 col-12">
                <div class="stat-card">
                    <div class="icon-container icon-blue"><i class="fa-solid fa-receipt"></i></div>
                    <div class="info">
                        <h6>Total Transaksi</h6>
                        <p>{{ $totalTransaksi }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12">
                <div class="stat-card">
                    <div class="icon-container icon-yellow"><i class="fa-solid fa-box"></i></div>
                    <div class="info">
                        <h6>Produk Terjual</h6>
                        <p>{{ $totalProdukTerjual }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12">
                <div class="stat-card">
                    <div class="icon-container icon-green"><i class="fa-solid fa-wallet"></i></div>
                    <div class="info">
                        <h6>Penerimaan Pembayaran</h6>
                        <p>Rp{{ number_format($terimaPembayaran, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12">
                <div class="chart-card">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                        <h5 class="mb-0">Grafik Penjualan</h5>
                        <div class="btn-group" role="group" aria-label="Grafik Filter">
                            <button type="button" class="btn btn-outline-primary active" id="dailyBtn">Harian</button>
                            <button type="button" class="btn btn-outline-primary" id="monthlyBtn">Bulanan</button>
                            <button type="button" class="btn btn-outline-primary" id="yearlyBtn">Tahunan</button>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dailyLabels = @json($dailySalesLabels);
            const dailyData = @json($dailySalesData);
            const monthlyLabels = @json($monthlySalesLabels);
            const monthlyData = @json($monthlySalesData);
            const yearlyLabels = @json($yearlySalesLabels);
            const yearlyData = @json($yearlySalesData);

            const ctx = document.getElementById('salesChart').getContext('2d');
            const dailyBtn = document.getElementById('dailyBtn');
            const monthlyBtn = document.getElementById('monthlyBtn');
            const yearlyBtn = document.getElementById('yearlyBtn');
            const filterButtons = [dailyBtn, monthlyBtn, yearlyBtn];

            const salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: dailyLabels,
                    datasets: [{
                        label: 'Total Penjualan',
                        data: dailyData,
                        backgroundColor: 'rgba(59, 130, 246, 0.2)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });

            function updateChartData(labels, data) {
                salesChart.data.labels = labels;
                salesChart.data.datasets[0].data = data;
                salesChart.update();
            }

            function setActiveButton(activeBtn) {
                filterButtons.forEach(button => button.classList.remove('active'));
                activeBtn.classList.add('active');
            }

            dailyBtn.addEventListener('click', function () {
                updateChartData(dailyLabels, dailyData);
                setActiveButton(this);
            });

            monthlyBtn.addEventListener('click', function () {
                updateChartData(monthlyLabels, monthlyData);
                setActiveButton(this);
            });

            yearlyBtn.addEventListener('click', function () {
                updateChartData(yearlyLabels, yearlyData);
                setActiveButton(this);
            });
        });
    </script>
@endpush