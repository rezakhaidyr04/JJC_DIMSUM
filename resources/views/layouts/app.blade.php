<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Cikampek Jajanan</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <style>
        :root {
            --primary-yellow: #FFED4E;
            --primary-red: #E31E24;
            --accent-red: #DC2626;
            --text-dark: #2D2D2D;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .content {
            padding-bottom: 1.25rem;
        }

        .content .container-fluid {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        /* Navbar Styling */
        .main-header.navbar {
            background: linear-gradient(90deg, var(--accent-red) 0%, #B91720 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
        }

        .main-header.navbar .navbar-brand {
            font-weight: bold;
            font-size: 1.3rem;
            color: white !important;
        }

        .navbar-text {
            color: white;
        }

        .navbar-text .badge {
            background-color: var(--primary-yellow) !important;
            color: var(--text-dark) !important;
            font-weight: 600;
        }

        /* Sidebar Styling */
        .main-sidebar {
            background: linear-gradient(180deg, #2C2C2C 0%, #1a1a1a 100%);
        }

        .brand-link {
            background: linear-gradient(135deg, var(--accent-red) 0%, #B91720 100%);
            border-bottom: 3px solid var(--primary-yellow);
            padding: 1rem !important;
        }

        .brand-link .brand-text {
            color: white;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .nav-sidebar .nav-link {
            color: #bbb;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .nav-sidebar .nav-link:hover {
            background-color: rgba(227, 30, 36, 0.1);
            border-left-color: var(--accent-red);
            color: white;
        }

        .nav-sidebar .nav-link.active {
            background-color: var(--accent-red);
            border-left-color: var(--primary-yellow);
            color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .nav-sidebar .nav-link i {
            margin-right: 0.5rem;
        }

        /* Content Area */
        .content-wrapper {
            background-color: #f8f9fa;
        }

        .content-header {
            background-color: white;
            border-bottom: 2px solid #e9ecef;
            padding: 1rem 0;
        }

        .content-header h1 {
            color: var(--accent-red);
            font-weight: bold;
            font-size: 2rem;
        }

        .card-title {
            margin: 0;
        }

        .table-responsive {
            border-radius: 0.5rem;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 0.4rem;
        }

        .form-control,
        .form-select {
            border-radius: 0.5rem;
        }

        .actions-inline {
            display: flex;
            flex-wrap: wrap;
            gap: 0.35rem;
            align-items: center;
        }

        /* Card Styling */
        .card {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            border: none;
            border-radius: 0.8rem;
            border-top: 4px solid var(--accent-red);
        }

        .card-header {
            background: linear-gradient(90deg, var(--accent-red) 0%, #B91720 100%);
            color: white;
            border: none;
            font-weight: 600;
        }

        /* Alert Styling */
        .alert {
            border-radius: 0.5rem;
            border: none;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        /* Button Styling */
        .btn-primary {
            background-color: var(--accent-red);
            border-color: var(--accent-red);
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #B91720;
            border-color: #B91720;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(227, 30, 36, 0.3);
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            font-weight: 600;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #5a6268;
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: var(--accent-red);
            border-color: var(--accent-red);
            font-weight: 600;
        }

        .btn-danger:hover {
            background-color: #B91720;
            border-color: #B91720;
        }

        /* Table Styling */
        .table {
            background-color: white;
        }

        .table thead {
            background-color: var(--accent-red);
            color: white;
        }

        .table thead th {
            font-weight: 600;
            border: none;
            padding: 1rem;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        /* Badge Styling */
        .badge {
            padding: 0.5rem 0.75rem;
            font-weight: 600;
        }

        /* Pagination Styling */
        .pagination {
            gap: 0.35rem;
        }

        .page-item .page-link {
            border-radius: 0.45rem;
            border: 1px solid #e5e7eb;
            color: var(--accent-red);
            font-weight: 600;
            padding: 0.45rem 0.75rem;
        }

        .page-item .page-link:hover {
            background-color: #fff1f2;
            border-color: #fecdd3;
            color: #b91c1c;
        }

        .page-item.active .page-link {
            background-color: var(--accent-red);
            border-color: var(--accent-red);
            color: #fff;
            box-shadow: 0 2px 8px rgba(227, 30, 36, 0.25);
        }

        .page-item.disabled .page-link {
            color: #9ca3af;
            background-color: #f9fafb;
            border-color: #e5e7eb;
        }

        /* Footer */
        .main-footer {
            background-color: #2C2C2C;
            color: #bbb;
            border-top: 3px solid var(--accent-red);
            padding: 1rem 1.5rem;
        }

        .main-footer strong {
            color: white;
        }

        .main-footer a {
            color: var(--primary-yellow);
            text-decoration: none;
        }

        .main-footer a:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 767px) {
            .content-header h1 {
                font-size: 1.5rem;
            }
        }
    </style>

    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-dark">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <div class="navbar-text ms-auto d-flex align-items-center">
                <span class="me-3"><i class="fas fa-user-circle me-2"></i>{{ Auth::user()->name }}</span>
                <span class="badge text-uppercase me-3">{{ Auth::user()->role }}</span>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-light text-dark fw-600">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </button>
                </form>
            </div>
        </nav>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar elevation-4">
            <!-- Brand/Logo -->
            <a href="{{ route('dashboard') }}" class="brand-link text-decoration-none">
                <i class="fas fa-drumstick-bite me-2"></i>
                <span class="brand-text">Cikampek Jajanan</span>
            </a>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-th-large"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('barang.index') }}" class="nav-link {{ request()->routeIs('barang.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cube"></i>
                            <p>Data Barang</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('barang-masuk.index') }}" class="nav-link {{ request()->routeIs('barang-masuk.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-arrow-down"></i>
                            <p>Barang Masuk</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('barang-keluar.index') }}" class="nav-link {{ request()->routeIs('barang-keluar.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-arrow-up"></i>
                            <p>Barang Keluar</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('laporan.index') }}" class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>Laporan</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0"><i class="fas fa-chevron-right me-2"></i>@yield('page-title')</h1>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Alert Messages -->
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong><i class="fas fa-exclamation-triangle me-2"></i>Error!</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </section>
        </div>

        <!-- Footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2024 <a href="{{ url('/') }}">Cikampek Jajanan</a>.</strong>
            Sistem Manajemen Stok Cerdas
            <div class="float-end d-none d-sm-inline-block">
                <b>Versi</b> 1.0.0 - Sekali Jajan, Jajan Teroos!!!
            </div>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AdminLTE JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>

    @stack('scripts')
</body>
</html>
