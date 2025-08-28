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

        <div class="produk-table table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Tanggal</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Kategori</th>
                        @if (Auth::user()->role === 'admin')
                            <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($produks as $key => $produk)
                        <tr>
                            <td data-label="No">{{ ($page - 1) * $perPage + $key + 1 }}</td>
                            <td data-label="Gambar">
                                @if ($produk->gambar)
                                    <img src="{{ Storage::url($produk->gambar) }}" alt="{{ $produk->nama }}" class="rounded"
                                        style="width:60px; height:60px; object-fit:cover;">
                                @else
                                    <span class="text-muted">Belum ada gambar</span>
                                @endif
                            </td>
                            <td data-label="Tanggal">{{ $produk->tanggal->format('d/m/Y') }}</td>
                            <td data-label="Nama Produk">{{ $produk->nama }}</td>
                            <td data-label="Harga">Rp {{ number_format($produk->harga, 0, ',', '.') }}</td>
                            <td data-label="Stok">{{ $produk->stok }}</td>
                            <td data-label="Kategori">{{ $produk->kategoriProduk->nama ?? '-' }}</td>
                            @if (Auth::user()->role == 'admin')
                                <td data-label="Aksi">
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
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ Auth::user()->role === 'admin' ? 8 : 7 }}" class="no-data-cell">
                                Tidak ada data produk
                            </td>

                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>


        @php
            $totalPages = ceil($totalProduks / $perPage);
            $searchQuery = request('search') ? ['search' => request('search')] : [];
        @endphp

        <div class="d-flex justify-content-center mt-4">
            <nav>
                <ul class="pagination">
                    {{-- Previous Page Link --}}
                    <li class="page-item @if($page <= 1) disabled @endif">
                        <a class="page-link" href="{{ route('produk.index', array_merge($searchQuery, ['page' => $page - 1])) }}" aria-label="Previous">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>

                    {{-- Page Number Links --}}
                    @for ($i = 1; $i <= $totalPages; $i++)
                        <li class="page-item @if($page == $i) active @endif">
                            <a class="page-link" href="{{ route('produk.index', array_merge($searchQuery, ['page' => $i])) }}">{{ $i }}</a>
                        </li>
                    @endfor

                    {{-- Next Page Link --}}
                    <li class="page-item @if($page >= $totalPages) disabled @endif">
                        <a class="page-link" href="{{ route('produk.index', array_merge($searchQuery, ['page' => $page + 1])) }}" aria-label="Next">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

    </div>
@endsection
