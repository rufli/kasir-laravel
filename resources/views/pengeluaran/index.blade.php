@extends('layouts.app')

@section('title', 'Pengeluaran')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pengeluaran.css') }}">
@endpush

@section('content')
<div class="pengeluaran-container">
    <div class="pengeluaran-header">
        <h5>Pengeluaran</h5>
        <div class="pengeluaran-actions">
            <form action="{{ route('pengeluaran.index') }}" method="GET" class="search-form">
                <input type="text" name="search" placeholder="search" value="{{ request('search') }}">
            </form>
            <a href="{{ route('pengeluaran.create') }}" class="btn-tambah">Tambah +</a>
        </div>
    </div>

    <div class="pengeluaran-table">
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Catatan</th>
                    <th>Jumlah</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pengeluaran as $item)
                    <tr>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->kategori->nama ?? '-' }}</td>
                        <td>{{ $item->catatan ?? '-' }}</td>
                        <td>Rp. {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('pengeluaran.edit', $item->id) }}" class="btn-edit">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">Belum ada data pengeluaran.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
