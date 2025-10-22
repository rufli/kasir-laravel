@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/transaksi/keranjang.css') }}">
@endpush

@section('content')
    <div class="container">
        <h1 class="page-title">Keranjang Produk</h1>


        @if (count($items) > 0)
            <div class="cart-items-grid" id="cartItemsGrid">
                @foreach ($items as $item)
                    <div class="card product-card">
                        {{-- Gambar Produk --}}
                        @if ($item['produk']->gambar)
                            <img src="{{ Storage::url($item['produk']->gambar) }}" alt="{{ $item['produk']->nama }}"
                                class="product-image">
                        @else
                            <div class="d-flex flex-column align-items-center text-center">
                                <img src="{{ asset('images/default.jpg') }}" alt="Default Gambar"
                                    style="width: 90px; height: 90px; object-fit: cover; border-radius: 8px; margin-left: 7px;">
                                <div>
                                    <small class="text-muted mt-1">Belum ada gambar</small>
                                </div>
                            </div>
                        @endif

                        <div class="card-body">
                            <h5 class="card-title">{{ $item['produk']->nama }}</h5>
                            <p class="card-text">Harga: Rp{{ number_format($item['produk']->harga, 0, ',', '.') }}</p>
                            <p class="card-text">Jumlah: {{ $item['jumlah'] }}</p>
                            <p class="card-text">Subtotal: Rp{{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                            <form action="{{ route('penjualan.hapus', $item['produk']->id) }}" method="POST"
                                onsubmit="return confirm('Hapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger mt-2">Hapus</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="cart-summary">
                <a href="{{ route('penjualan.daftar_produk') }}" class="back-btn">Kembali</a>
                <div class="total-btn">Total: Rp {{ number_format($total) }}</div>
                <div class="count-badge">{{ count($items) }}</div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('penjualan.checkout.page') }}" class="btn btn-success btn-lg px-5">
                    <i class="fas fa-credit-card"></i> Bayar Sekarang
                </a>
            </div>
        @else
            <div class="empty-cart" id="emptyCart">
                <div class="empty-cart-icon">🛒</div>
                <h3>Keranjang Kosong</h3>
                <p>Silakan tambahkan produk ke keranjang Anda</p>
            </div>
        @endif
    </div>
@endsection
