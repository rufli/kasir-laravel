<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POSKasir - @yield('title')</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- CSS Global -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- CSS khusus halaman (jika ada) -->
    @stack('styles')

    <!-- Font Awesome (opsional) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Script Head (jika diperlukan) -->
    @stack('head-scripts')
</head>
<body>
    <!-- Header (Navbar Atas) -->
    <header class="main-header">
        <div class="container header-content">
            <div class="app-logo">
                <!-- logo-->
                 <img src="{{ asset('images/logo-poskasir.png') }}" alt="POSKasir Logo" height="40" >
            </div>

            <!-- User Profile (opsional) -->
            <div class="user-profile">
                <span class="username">Admin</span>
                <div class="avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
            </div>
        </div>
    </header>

    <!-- Konten Utama dan Sidebar -->
    <div class="main-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <nav class="sidebar-nav">
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="{{ route('dashboard.index') }}" class="sidebar-link {{ request()->routeIs('dashboard.*') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('produk.index') }}" class="sidebar-link {{ request()->routeIs('produk.*') ? 'active' : '' }}">
                            <i class="fas fa-box-open"></i>
                            <span>Produk</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('kategori_produk.index') }}" class="sidebar-link {{ request()->routeIs('kategori_produk.*') ? 'active' : '' }}">
                            <i class="fas fa-tags"></i>
                            <span>Kategori Produk</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="sidebar-link">
                            <i class="fas fa-money-bill-wave"></i>
                            <span>Pengeluaran</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="sidebar-link">
                            <i class="fas fa-history"></i>
                            <span>Riwayat Transaksi</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="sidebar-link">
                            <i class="fas fa-file-invoice-dollar"></i>
                            <span>Laporan Keuangan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="sidebar-link">
                            <i class="fas fa-users"></i>
                            <span>Manajemen Pegawai</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Tombol Logout
            <div class="sidebar-footer">
                {{--
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-button">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                    --}}
                </form>
            </div> -->
        </aside>

        <!-- Area Konten Utama -->
        <main class="content-area">
            <div class="container">
                <!-- Breadcrumb (opsional) -->
                <div class="breadcrumb">
                    @yield('breadcrumb')
                </div>

                <!-- Bagian untuk menampilkan pesan sukses/error/validasi -->
                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-triangle"></i>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Konten spesifik halaman akan disuntikkan di sini -->
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="container">
            &copy; {{ date('Y') }} POSKasir. All rights reserved.
            <span class="version">v1.0.0</span>
        </div>
    </footer>

    <!-- Script Global -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Script khusus halaman -->
    @stack('scripts')
</body>
</html>
