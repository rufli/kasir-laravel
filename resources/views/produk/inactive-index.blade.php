@extends('layouts.app')

@section('title', 'Daftar Produk Nonaktif')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/produk/index.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush

@section('content')
    <div class="produk-container">
        <div class="produk-header">
            <h2>Daftar Produk Nonaktif</h2>
            <div class="produk-actions">
                <form action="{{ route('produk.inactive-index') }}" method="GET" class="search-form">
                    <div class="search-input-wrapper">
                        <input type="text" name="search" placeholder="Cari produk..." value="{{ request('search') }}">
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
                @if (Auth::user()->role == 'admin')
                    <a href="{{ route('produk.index') }}" class="btn-tambah">Kembali ke Produk Aktif</a>
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
                        <th>Status</th>
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
                                     <div class="d-flex flex-column align-items-center text-center">
                                        <img src="{{ asset('images/default.jpg') }}" alt="Default Gambar"
                                            style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; margin-left: 7px;">
                                        <div>
                                            <small class="text-muted mt-1">Belum ada gambar</small>
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td data-label="Tanggal">{{ $produk->tanggal->format('d/m/Y') }}</td>
                            <td data-label="Nama Produk">{{ $produk->nama }}</td>
                            <td data-label="Harga">Rp {{ number_format($produk->harga, 0, ',', '.') }}</td>
                            <td data-label="Stok">{{ $produk->stok }}</td>
                            <td data-label="Kategori">{{ $produk->kategoriProduk->nama ?? '-' }}</td>
                            <td data-label="Status">
                                <span class="status-badge status-nonaktif">Nonaktif</span>
                            </td>
                            @if (Auth::user()->role == 'admin')
                                <td data-label="Aksi">
                                    <div class="btn-action-group">
                                        <a href="{{ route('produk.edit', $produk) }}" class="btn-edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('produk.toggle-status', $produk->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin mengaktifkan produk ini?');">
                                            @csrf
                                            <button type="submit" class="btn-toggle-status btn-activate" title="Aktifkan">
                                                <i class="fas fa-toggle-off"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ Auth::user()->role === 'admin' ? 9 : 8 }}" class="no-data-cell">
                                Tidak ada data produk nonaktif
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
                        <a class="page-link" href="{{ route('produk.inactive-index', array_merge($searchQuery, ['page' => $page - 1])) }}" aria-label="Previous">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>

                    {{-- Page Number Links --}}
                    @for ($i = 1; $i <= $totalPages; $i++)
                        <li class="page-item @if($page == $i) active @endif">
                            <a class="page-link" href="{{ route('produk.inactive-index', array_merge($searchQuery, ['page' => $i])) }}">{{ $i }}</a>
                        </li>
                    @endfor

                    {{-- Next Page Link --}}
                    <li class="page-item @if($page >= $totalPages) disabled @endif">
                        <a class="page-link" href="{{ route('produk.inactive-index', array_merge($searchQuery, ['page' => $page + 1])) }}" aria-label="Next">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

    </div>
@endsection
