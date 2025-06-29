<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Sistem Manajemen Inventaris</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>

    <style>
        
        body {
            background-color: #f8f9fa; 
            color: #495057; 
            font-family: 'Nunito', sans-serif;
            overflow-x: hidden;
        }
        .main-wrapper {
            display: flex;
            min-height: 100vh;
        }
      
        .sidebar {
            width: 260px;
            background-color: #ffffff; 
            border-right: 1px solid #dee2e6;
            padding: 1.5rem 1rem;
            transition: transform 0.3s ease-in-out;
            z-index: 1000;
        }
        .sidebar-header {
            font-weight: 700;
            font-size: 1.5rem;
            color: #343a40; 
            text-align: center;
            margin-bottom: 2rem;
        }
        .sidebar-nav .nav-link {
            color: #495057; 
            font-size: 1rem;
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            margin-bottom: 0.5rem;
            transition: background-color 0.2s, color 0.2s;
        }
        .sidebar-nav .nav-link i {
            width: 25px;
            margin-right: 10px;
            text-align: center;
            color: #6c757d; 
        }
        .sidebar-nav .nav-link:hover {
            background-color: #e9ecef; 
            color: #0d6efd;
        }
        .sidebar-nav .nav-link.active {
            background-color: #0d6efd; 
            color: #ffffff; 
            font-weight: 600;
        }
        .sidebar-nav .nav-link.active i {
            color: #ffffff; 
        }
       
        .content-wrapper {
            flex-grow: 1;
            padding: 0;
            display: flex;
            flex-direction: column;
        }
        .content-main {
            flex-grow: 1;
            padding: 1.5rem;
            overflow-y: auto;
        }
     
        @media (max-width: 992px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100%;
                transform: translateX(-100%);
                box-shadow: 0 0 1rem rgba(0,0,0,.1);
            }
            .sidebar.active {
                transform: translateX(0);
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="main-wrapper">
        <aside class="sidebar" id="sidebar">
            <h1 class="sidebar-header">Sistem Manajemen Inventaris Toko</h1>
            <ul class="nav sidebar-nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pos') ? 'active' : '' }}" href="{{ route('home') }}"><i class="fa-solid fa-cash-register"></i> POS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('absensi') ? 'active' : '' }}" href="{{ route('absensi') }}"><i class="fa-solid fa-user-check"></i> Absensi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="fa-solid fa-chart-pie"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('master.categories') }}"><i class="fa-solid fa-database"></i> Master Data</a>
                </li>
                 <li class="nav-item">
                    <a class="nav-link" href="{{ route('report.sales') }}"><i class="fa-solid fa-file-alt"></i> Report</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('inventory') }}"><i class="fa-solid fa-warehouse"></i> Inventory</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('coupons') }}"><i class="fa-solid fa-tags"></i> Coupon</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('orders') }}"><i class="fa-solid fa-list-alt"></i> Order Pesanan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('users') }}"><i class="fa-solid fa-users"></i> Users</a>
                </li>
            </ul>
        </aside>

        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggler = document.getElementById('sidebar-toggler');
            const sidebar = document.getElementById('sidebar');
            if (sidebarToggler) {
                sidebarToggler.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>