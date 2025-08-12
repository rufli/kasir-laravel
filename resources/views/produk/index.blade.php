@extends('layouts.app')


@section('content')

<link rel="stylesheet" href="{{ asset('css/produk.css') }}">
<div class="container">
    <div class="row justify-content-between mb-4">
        <div class="col-md-6">
            <h2>Daftar Produk</h2>
        </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('produk.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Produk
                </a>
            </div>
    </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Gambar</th>
                            <th>Tanggal</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produks as $key => $produk)
                        <tr>
                            <td>{{ $produks->firstItem() + $key }}</td>

                            {{-- Kolom Gambar --}}
                            <td>
                                @if($produk->gambar)
                                    <img src="{{ Storage::url($produk->gambar) }}"
                                         alt="{{ $produk->nama }}"
                                         class="rounded"
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
                                <!--<div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('produk.show', $produk) }}" class="btn btn-info" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>-->
                                    <a href="{{ route('produk.edit', $produk) }}" class="btn btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('produk.destroy', $produk) }}" method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
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

            @if($produks->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $produks->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
