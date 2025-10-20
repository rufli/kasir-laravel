@extends('layouts.app')
@section('title', 'Pilih Produk')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/transaksi/penjualan.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterDropdown = document.querySelector('.filter-dropdown');
            const filterToggle = document.querySelector('.filter-toggle');
            const filterMenu = document.querySelector('.filter-menu');

            filterToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                filterMenu.classList.toggle('active');
            });

            // Klik di luar .filter-dropdown → tutup
            document.addEventListener('click', function(e) {
                if (!filterDropdown.contains(e.target)) {
                    filterMenu.classList.remove('active');
                }
            });
        });
    </script>
@endpush



@section('content')
    <div class="container">

        <h2 class="page-title">Pilih Produk</h2>

        {{-- Pesan error global (misalnya validasi umum) --}}
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Pesan error dari validasi bawaan Laravel --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form search + filter kategori --}}
        <form action="{{ route('penjualan.daftar_produk') }}" method="GET" class="search-section" style="gap:10px;">
            <input type="text" name="search" class="search-input" placeholder="Search" value="{{ request('search') }}">

            <div class="filter-dropdown">
                <button type="button" class="filter-toggle" aria-label="Toggle filter menu">
                    <i class="fas fa-filter"></i>
                </button>
                <div class="filter-menu {{ request('kategori') ? 'active' : '' }}">
                    <select name="kategori" class="kategori-select" onchange="this.form.submit()">
                        <option value="">-- Semua Kategori --</option>
                        @foreach ($kategoriProduks as $kategori)
                            <option value="{{ $kategori->id }}"
                                {{ request('kategori') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

        </form>

        <div class="products-grid">
            @foreach ($produks as $produk)
                <div>
                    <div class="product-card">

                        {{-- Gambar produk --}}
                        @if ($produk->gambar)
                            <img src="{{ Storage::url($produk->gambar) }}" alt="{{ $produk->nama }}" class="product-image">
                        @else
                            <div class="d-flex flex-column align-items-center text-center">
                                <img src="{{ asset('images/default.jpg') }}" alt="Default Gambar"
                                    style="width: 90px; height: 90px; object-fit: cover; border-radius: 8px; margin-left: 7px;">
                                <div>
                                    <small class="text-muted mt-1">Belum ada gambar</small>
                                </div>
                            </div>
                        @endif

                        <div class="product-info">
                            <h3>{{ $produk->nama }}</h3>
                            <p class="product-category">Kategori: {{ $produk->kategoriProduk->nama ?? 'Lainnya' }}</p>

                            <div class="product-details">
                                <span class="stock-info">Stok: {{ $produk->stok }}</span>
                                {{-- Form tambah ke keranjang --}}
                                <form action="{{ route('penjualan.tambah') }}" method="POST" class="add-to-cart-form">
                                    @csrf
                                    <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                                    <input type="hidden" name="jumlah" value="1">
                                    <button type="submit" class="add-btn"
                                        aria-label="Tambah {{ $produk->nama }} ke keranjang"
                                        {{ $produk->stok < 1 ? 'disabled' : '' }}>+</button>
                                </form>
                            </div>
                            {{-- Pesan error khusus untuk produk ini --}}
                            @if (session('error_produk') && data_get(session('error_produk'), 'id') == $produk->id)
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>{{ data_get(session('error_produk'), 'pesan') }}</span>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
        <!-- Floating Cart Button -->
        <a href="{{ route('penjualan.keranjang') }}" class="cart-floating" aria-label="Lihat keranjang belanja">
            🛒
            @if (session('keranjang') && count(session('keranjang')))
                <div class="cart-badge">{{ array_sum(session('keranjang')) }}</div>
            @endif
        </a>
    @endsection
