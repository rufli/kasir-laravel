@extends('layouts.app')

@section('title', 'Checkout')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/transaksi/pembayaran.css') }}">
@endpush

@section('content')
    <div class="checkout-container">
        <div class="checkout-card">
            <h2 class="checkout-title">Konfirmasi Pembayaran</h2>

            <form action="{{ route('penjualan.checkout') }}" method="POST">
                @csrf

                {{-- Metode Pembayaran --}}
                <div class="form-group">
                    <label for="metode_pembayaran">Metode Pembayaran</label>
                    <select name="metode_pembayaran"
                            id="metode_pembayaran"
                            class="@error('metode_pembayaran') is-invalid @enderror">
                        <option value="">-- Pilih --</option>
                        <option value="tunai" {{ old('metode_pembayaran') == 'tunai' ? 'selected' : '' }}>Tunai</option>
                        <option value="transfer" {{ old('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                        <option value="qris" {{ old('metode_pembayaran') == 'qris' ? 'selected' : '' }}>QRIS</option>
                    </select>
                    @error('metode_pembayaran')
                        <div class="invalid-feedback">*{{ $message }}</div>
                    @enderror
                </div>

                {{-- Jumlah Dibayar --}}
                <div class="form-group">
                    <label for="jumlah_dibayar">Jumlah Uang Dibayar</label>
                    <input type="number"
                           name="jumlah_dibayar"
                           id="jumlah_dibayar"
                           min="0"
                           placeholder="Masukkan jumlah uang yang dibayarkan"
                           value="{{ old('jumlah_dibayar') }}"
                           class="@error('jumlah_dibayar') is-invalid @enderror">
                    @error('jumlah_dibayar')
                        <div class="invalid-feedback">*{{ $message }}</div>
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
            const total = {{ $total }};

            metodePembayaran.addEventListener('change', function() {
                if (['transfer', 'qris'].includes(this.value)) {
                    jumlahDibayar.value = total;
                    jumlahDibayar.readOnly = true;
                } else {
                    jumlahDibayar.value = '';
                    jumlahDibayar.readOnly = false;
                }
            });
        });
    </script>
@endsection
