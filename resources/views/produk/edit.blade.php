@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/produk/edit.css') }}">
<div class="container">
    <h2>Edit Produk</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('produk.update', $produk) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Tanggal -->
            <div class="col-md-6 mb-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', $produk->tanggal->format('Y-m-d')) }}" required>
            </div>

            <!-- Nama -->
            <div class="col-md-6 mb-3">
                <label class="form-label">Nama Produk</label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama', $produk->nama) }}" maxlength="45" required>
            </div>

            <!-- Harga -->
            <div class="col-md-6 mb-3">
                <label class="form-label">Harga (Rp)</label>
                <input type="number" name="harga" class="form-control" step="0.01" min="0" value="{{ old('harga', $produk->harga) }}" required>
            </div>

            <!-- Stok -->
            <div class="col-md-6 mb-3">
                <label class="form-label">Stok</label>
                <input type="number" name="stok" class="form-control" min="0" value="{{ old('stok', $produk->stok) }}" required>
            </div>

            <!-- Kategori -->
            <div class="col-md-6 mb-3">
                <label class="form-label">Kategori</label>
                <select name="kategori_produk_id" class="form-select" required>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}" {{ $k->id == old('kategori_produk_id', $produk->kategori_produk_id) ? 'selected' : '' }}>
                            {{ $k->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Gambar -->
            <div class="col-md-6 mb-3">
                <label class="form-label">Gambar (kosongkan bila tidak diganti)</label>
                <input type="file" name="gambar" class="form-control" accept="image/*">
                @if($produk->gambar)
                    <div class="mt-2">
                        <img src="{{ Storage::url($produk->gambar) }}" width="100" class="img-thumbnail">
                        <br>
                        <small class="text-muted">Gambar saat ini</small>
                    </div>
                @endif
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('produk.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
