@extends('layouts.app')

@section('title', 'Checkout')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/transaksi/pembayaran.css') }}">
@endpush

@section('content')
    <div class="checkout-container">
        <div class="checkout-card">
            <h2 class="checkout-title">Konfirmasi Pembayaran</h2>

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('penjualan.checkout') }}" method="POST">
                @csrf

                {{-- Metode Pembayaran --}}
                <div class="form-group">
                    <label for="metode_pembayaran">Metode Pembayaran</label>
                    <select name="metode_pembayaran" id="metode_pembayaran" required>
                        <option value="">-- Pilih --</option>
                        <option value="tunai">Tunai</option>
                        <option value="transfer">Transfer</option>
                        <option value="qris">QRIS</option>
                    </select>
                </div>
                @if ($errors->any())
                    <div style="color: red; margin-bottom: 20px;">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Jumlah Dibayar --}}
                <div class="form-group">
                    <label for="jumlah_dibayar">Jumlah Uang Dibayar</label>
                    <input type="number" name="jumlah_dibayar" id="jumlah_dibayar" min="0"
                        placeholder="Masukkan jumlah uang yang dibayarkan" value="{{ old('jumlah_dibayar') }}" required>
                    @error('jumlah_dibayar')
                        <span class="text-danger" style="font-size: 14px; display: block; margin-top: 32px;">
                            {{ $message }}
                        </span>
                    @enderror
                </div>


                {{-- Total Pembayaran --}}
                <div class="checkout-total">
                    Total: <strong id="total-display">Rp {{ number_format($total, 0, ',', '.') }}</strong>
                </div>

                <button type="submit" class="btn-submit">Bayar Sekarang</button>
            </form>

            <a href="{{ route('penjualan.keranjang') }}" class="btn-back">← Kembali ke Keranjang</a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const metodePembayaran = document.getElementById('metode_pembayaran');
            const jumlahDibayar = document.getElementById('jumlah_dibayar');
            const total = {{ $total }}; // Total dari server

            metodePembayaran.addEventListener('change', function() {
                if (['transfer', 'qris'].includes(this.value)) {
                    jumlahDibayar.value = total; // otomatis isi total
                    jumlahDibayar.readOnly = true; // tidak bisa diubah
                } else {
                    jumlahDibayar.value = ''; // kosongkan kalau tunai
                    jumlahDibayar.readOnly = false; // bisa diubah manual
                }
            });
        });
    </script>
@endsection
