@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/transaksi/riwayat.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush

@section('content')
    <div class="riwayat-container">
        <div class="riwayat-header">
            <h5>Riwayat Transaksi</h5>
            <div class="riwayat-actions">
                <form action="{{ route('penjualan.riwayat') }}" method="GET" id="filterTanggalForm"
                    style="display: flex; align-items: center; gap: 10px;">
                    <label for="tanggal" style="color: #fff; font-weight: 600; margin: 0;">Filter Tanggal:</label>
                    <input type="date" name="tanggal" id="tanggal" value="{{ request('tanggal') }}"
                        style="border-radius: 20px; border: none; padding: 6px 12px; font-size: 14px; cursor: pointer;"
                        onchange="document.getElementById('filterTanggalForm').submit()">
                </form>
            </div>

        </div>

        <div class="riwayat-table">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Id Transaksi</th>
                        <th>Metode Pembayaran</th>
                        <th>Total Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</td>
                            <td>{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ ucfirst($item->metode_pembayaran) }}</td>
                            <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                            <td>
                                <div class="btn-action-group">
                                    <a href="{{ route('penjualan.detail', $item->id) }}" class="btn-detail" title="Detail">
                                        <i class="fas fa-info-circle"></i> Detail
                                    </a>
                                    {{-- Tambahan tombol edit/hapus bisa disesuaikan jika diperlukan --}}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
