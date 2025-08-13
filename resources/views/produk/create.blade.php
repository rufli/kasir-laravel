@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/produk/create.css') }}">


<div class="container">
    <h2>Tambah Produk Baru</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <!-- Tanggal -->
            <div class="col-md-6 mb-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal') }}" required>
            </div>

            <!-- Nama -->
            <div class="col-md-6 mb-3">
                <label class="form-label">Nama Produk</label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" maxlength="45" required>
            </div>

            <!-- Harga -->
            <div class="col-md-6 mb-3">
                <label class="form-label">Harga (Rp)</label>
                <input type="number" name="harga" class="form-control" step="0.01" min="0" value="{{ old('harga') }}" required>
            </div>

            <!-- Stok -->
            <div class="col-md-6 mb-3">
                <label class="form-label">Stok</label>
                <input type="number" name="stok" class="form-control" min="0" value="{{ old('stok') }}" required>
            </div>

            <!-- Kategori -->
            <div class="col-md-6 mb-3">
                <label class="form-label">Kategori</label>
                <select name="kategori_produk_id" class="form-select" required>
                    <option value="" disabled selected>Pilih Kategori</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}" {{ old('kategori_produk_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Gambar -->
            <div class="col-md-6 mb-3">
                <label class="form-label">Gambar</label>
                <input type="file" name="gambar" class="form-control" accept="image/*">
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Produk</button>
        <a href="{{ route('produk.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
