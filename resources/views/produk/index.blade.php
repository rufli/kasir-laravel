@extends('layouts.app')

@section('title', 'Daftar Produk')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/produk/index.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush

@section('content')
    <div class="produk-container">
        <div class="produk-header">
            <h2>Daftar Produk</h2>
            <div class="produk-actions">
                <form action="{{ route('produk.index') }}" method="GET" class="search-form">
                    <div class="search-input-wrapper">
                        <input type="text" name="search" placeholder="Cari produk..." value="{{ request('search') }}">
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
                @if (Auth::user()->role == 'admin')
                    <a href="{{ route('produk.create') }}" class="btn-tambah">Tambah</a>
                @endif
            </div>
        </div>

        <div class="produk-table">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Tanggal</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Kategori</th>
                        @if (auth()->user()->role === 'admin')
                            <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($produks as $key => $produk)
                        <tr>
                            <td>{{ $produks->firstItem() + $key }}</td>
                            <td>
                                @if ($produk->gambar)
                                    <img src="{{ Storage::url($produk->gambar) }}" alt="{{ $produk->nama }}" class="rounded"
                                        style="width:60px; height:60px; object-fit:cover;">
                                @else
                                    <span class="text-muted">Belum ada gambar</span>
                                @endif
                            </td>
                            <td>{{ $produk->tanggal->format('d/m/Y') }}</td>
                            <td>{{ $produk->nama }}</td>
                            <td>Rp {{ number_format($produk->harga, 0, ',', '.') }}</td>
                            <td>{{ $produk->stok }}</td>
                            <td>{{ $produk->kategoriProduk->nama ?? '-' }}</td>
                            <td>
                                @if (Auth::user()->role == 'admin')
                                    <div class="btn-action-group">
                                        <a href="{{ route('produk.edit', $produk) }}" class="btn-edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('produk.destroy', $produk) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete" title="Hapus">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data produk</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($produks->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $produks->links() }}
            </div>
        @endif
    </div>
@endsection
