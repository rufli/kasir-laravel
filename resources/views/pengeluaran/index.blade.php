@extends('layouts.app')

@section('title', 'Daftar Pengeluaran')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pengeluaran/index.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush

@section('content')
    <div class="pengeluaran-container">
        <div class="pengeluaran-header">
            <h5>Daftar Pengeluaran</h5>
            <div class="pengeluaran-actions">
                <form action="{{ route('pengeluaran.index') }}" method="GET" class="search-form">
                    <div class="search-input-wrapper">
                        <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}">
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
                <a href="{{ route('pengeluaran.create') }}" class="btn-tambah">Tambah +</a>
            </div>
        </div>

        <div class="pengeluaran-table">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Catatan</th>
                        <th>Satuan</th>
                        <th>Jumlah</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($pengeluaran as $item)
                    <tr>
                        <td data-label="No">{{ $loop->iteration }}</td>
                        <td data-label="Nama">{{ $item->nama }}</td>
                        <td data-label="Kategori">{{ $item->kategori->nama ?? '-' }}</td>
                        <td data-label="Catatan">{{ $item->catatan ?? '-' }}</td>
                        <td data-label="Satuan">{{ $item->satuan }}</td>
                        <td data-label="Jumlah">Rp. {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                        <td data-label="Tanggal">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                        <td data-label="Aksi">
                            <div class="btn-action-group">
                                <a href="{{ route('pengeluaran.edit', $item->id) }}" class="btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                    <td colspan="7" class="empty-message">Belum ada data pengeluaran.</td>
                    </tr>
                @endforelse
            </tbody>
            </table>
        </div>
    </div>
@endsection
