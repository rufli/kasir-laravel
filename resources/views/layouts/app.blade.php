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

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- CSS Halaman Khusus -->
    @stack('styles')
    @stack('head-scripts')
</head>
<body>
    <!-- Header -->
    <header class="main-header">
        <div class="container header-content">
            <div class="app-logo">
                <img src="{{ asset('images/logo-poskasir.png') }}" alt="POSKasir Logo" height="40">
            </div>
            <div class="user-profile">
                <span class="username">Admin</span>
                <a href="{{ route('profile') }}" class="username-link" style="text-decoration: none; color: inherit;">
                    <div class="avatar">
                        <i class="fas fa-user-circle"></i>
                    </div>
                </a>
            </div>
        </div>
    </header>

    <!-- Wrapper -->
    <div class="main-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <nav class="sidebar-nav">
                <ul class="nav-menu">
                    {{-- Menu untuk semua role --}}
                    <li class="nav-item">
                        <a href="{{ route('dashboard.index') }}"
                            class="sidebar-link {{ request()->routeIs('dashboard.*') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('produk.index') }}"
                            class="sidebar-link {{ request()->routeIs('produk.*') ? 'active' : '' }}">
                            <i class="fas fa-box-open"></i>
                            <span>Produk</span>
                        </a>
                    </li>
                    {{-- Kategori hanya admin --}}
                    @if (Auth::user()->role == 'admin')
                        <li
                            class="nav-item has-submenu {{ request()->routeIs('kategori_produk.*') || request()->routeIs('kategori_pengeluaran.*') ? 'open' : '' }}">
                            <a href="#" class="sidebar-link js-submenu-toggle">
                                <i class="fas fa-tags"></i>
                                <span>Kategori</span>
                                <i class="fas fa-chevron-down submenu-toggle-icon"></i>
                            </a>
                            <ul class="submenu">

                                <li>
                                    <a href="{{ route('kategori_produk.index') }}"
                                        class="{{ request()->routeIs('kategori_produk.*') ? 'active' : '' }}">
                                        Kategori Produk
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('kategori_pengeluaran.index') }}"
                                        class="{{ request()->routeIs('kategori_pengeluaran.*') ? 'active' : '' }}">
                                        Kategori Pengeluaran
                                    </a>
                                </li>

                            </ul>
                        </li>
                    @endif
                    {{-- Transaksi Penjualan & Riwayat untuk semua --}}
                    <li class="nav-item">
                        <a href="{{ route('penjualan.daftar_produk') }}"
                            class="sidebar-link {{ request()->routeIs('penjualan.daftar_produk*') ? 'active' : '' }}">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Transaksi Penjualan</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('penjualan.riwayat') }}"
                            class="sidebar-link {{ request()->routeIs('penjualan.riwayat*') ? 'active' : '' }}">
                            <i class="fas fa-history"></i>
                            <span>Riwayat Transaksi</span>
                        </a>
                    </li>

                    {{-- Menu khusus admin --}}
                    @if (Auth::user()->role == 'admin')
                        <li class="nav-item">
                            <a href="{{ route('pengeluaran.index') }}"
                                class="sidebar-link {{ request()->routeIs('pengeluaran.*') ? 'active' : '' }}">
                                <i class="fas fa-money-bill-wave"></i>
                                <span>Pengeluaran</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('laporan.keuangan') }}"
                                class="sidebar-link {{ request()->routeIs('laporan.keuangan') ? 'active' : '' }}">

                                <i class="fas fa-file-invoice-dollar"></i>
                                <span>Laporan Keuangan</span>
                            </a>
                        </li>--}}

                        <li class="nav-item">
                            <a href="{{ route('pegawai.index') }}"
                                class="sidebar-link {{ request()->routeIs('pegawai.*') ? 'active' : '' }}">
                                <i class="fas fa-users"></i>
                                <span>Manajemen Pegawai</span>
                            </a>
                        </li>
                    @endif
                </ul>

            </nav>

            <!-- Logout -->
            <div class="sidebar-footer">
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-button">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="content-area">
            <div class="container">
                <!-- Breadcrumb -->
                <div class="breadcrumb">
                    @yield('breadcrumb')
                </div>

                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ session('error') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Page Content -->
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

    <!-- Toggle Submenu Sidebar -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.js-submenu-toggle').forEach(function(el) {
                el.addEventListener('click', function(e) {
                    e.preventDefault();
                    const parent = this.closest('.has-submenu');
                    parent.classList.toggle('open');
                });
            });
        });
    </script>

    <!-- JS Halaman Khusus -->
    @stack('scripts')
</body>

</html>
