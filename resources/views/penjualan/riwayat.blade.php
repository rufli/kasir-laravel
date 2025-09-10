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
                    style="display: flex; align-items:flex-start; gap: 10px;">

                    @if (auth()->user()->role === 'admin')
                        <div style="display: flex; align-items: center; gap: 4px; min-width: 180px;">
                            <label for="user_id"
                                style="color: #333; font-weight: 600; font-size: 14px; margin: 0;">Pengguna:</label>
                            <select name="user_id" id="user_id"
                                style="border: 1px solid #ccc; border-radius: 12px; padding: 6px 12px; font-size: 14px; cursor: pointer;"
                                onchange="this.form.submit()">
                                <option value="">Semua</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    {{-- Filter Tanggal --}}
                    <input type="date" name="tanggal" id="tanggal" value="{{ request('tanggal') }}"
                        style="border-radius: 20px; border: none; padding: 6px 12px; font-size: 14px; cursor: pointer;"
                        onchange="this.form.submit()">
                </form>
            </div>
        </div>

        <div class="riwayat-table">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>

                        @if (auth()->user()->role === 'admin')
                            <th>Pegawai</th>
                        @endif

                        <th>Id Transaksi</th>
                        <th>
                            <div class="filter-inline">
                                <span>Metode Pembayaran</span>
                                <form action="{{ route('penjualan.riwayat') }}" method="GET" id="filterMetodeForm">
                                    <select name="metode_pembayaran" onchange="this.form.submit()">
                                        <option value="">Semua</option>
                                        <option value="tunai" {{ request('metode_pembayaran') == 'tunai' ? 'selected' : '' }}>Tunai</option>
                                        <option value="qris" {{ request('metode_pembayaran') == 'qris' ? 'selected' : '' }}>QRIS</option>
                                        <option value="transfer" {{ request('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                    </select>
                                </form>
                            </div>
                        </th>
                        <th>Total Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat as $item)
                        <tr>
                            <td data-label="No">{{ $loop->iteration }}</td>
                            <td data-label="Tanggal">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d/m/Y') }}</td>
                            @if (auth()->user()->role === 'admin')
                                <td data-label="Pengguna">{{ $item->user->nama ?? '-' }}</td>
                            @endif
                            <td data-label="Id Transaksi">{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</td>
                            <td data-label="Metode Pembayaran">{{ ucfirst($item->metode_pembayaran) }}</td>
                            <td data-label="Total Harga">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                            <td data-label="Aksi">
                                <div class="btn-action-group">
                                    <a href="{{ route('penjualan.detail', $item->id) }}" class="btn-detail">
                                        <i class="fas fa-info-circle"></i> Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->role === 'admin' ? '7' : '6' }}" class="empty-message">
                                Belum ada data transaksi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
