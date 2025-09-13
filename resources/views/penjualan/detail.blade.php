@extends('layouts.app')

@section('title', 'Detail Transaksi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/transaksi/penjualan.css') }}">
@endpush

@section('content')

    <!-- Judul Halaman dan Tombol Kembali di luar receipt-container -->
    <div class="detail-header"
        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0; color: #0e7f49;">Detail Transaksi</h3>
        <a href="{{ route('penjualan.riwayat') }}" class="btn-kembali"
            style="
        background-color: #0e7f49;
        color: white;
        padding: 6px 14px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: background-color 0.3s ease;
    "
            onmouseover="this.style.backgroundColor='#0c6c3c'" onmouseout="this.style.backgroundColor='#0e7f49'">Kembali</a>
    </div>

    <div class="receipt-container">
        <div class="receipt-header">
            <div>{{ $toko->nama_usaha ?? 'Nama Toko' }}</div>
            <div>{{ $toko->alamat_usaha ?? 'Alamat Toko' }}</div>
        </div>

        <div class="receipt-line"></div>

        <div class="receipt-info">
            <div>
                {{ \Carbon\Carbon::parse($transaksi->tanggal)->translatedFormat('d F Y') }}<br>
                {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('H : i') }}<br>
                Kasir : {{ $transaksi->user ? $transaksi->user->nama : '-' }}
            </div>
            <div class="receipt-id">
                Id Transaksi {{ str_pad($transaksi->id, 3, '0', STR_PAD_LEFT) }}
            </div>
        </div>

        <div class="receipt-line"></div>

        @foreach ($transaksi->detailTransaksi as $detail)
            <div class="receipt-item">
                <div>
                    <strong>{{ $detail->nama_produk }}</strong>
                    {{ $detail->jumlah }} X {{ number_format($detail->harga_produk, 0, ',', '.') }}
                </div>
                <div>Rp. {{ number_format($detail->subtotal, 0, ',', '.') }}</div>
            </div>
        @endforeach

        <div class="receipt-line"></div>

        <div class="receipt-item">
            <strong>Total</strong>
            Rp. {{ number_format($transaksi->total_harga, 0, ',', '.') }}
        </div>
        <div class="receipt-item">
            Bayar ({{ ucfirst($transaksi->metode_pembayaran) }})
            Rp. {{ number_format($transaksi->jumlah_dibayar, 0, ',', '.') }}
        </div>
        <div class="receipt-item">
            Kembali
            Rp. {{ number_format($transaksi->jumlah_kembalian, 0, ',', '.') }}
        </div>

        <a href="#" onclick="window.print()" class="btn-print">Cetak Struk</a>


    </div>
@endsection
