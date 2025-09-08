@extends('layouts.app')

@section('title', 'Tambah Produk')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/produk/create.css') }}">
@endpush

@section('content')
<div class="container">
    <h2>Tambah Produk Baru</h2>

    <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <!-- Tanggal -->
            <div class="col-md-6 mb-3">
                <label class="form-label" for="tanggal">Tanggal</label>
                <input type="date"
                       name="tanggal"
                       id="tanggal"
                       class="form-control @error('tanggal') is-invalid @enderror"
                       value="{{ old('tanggal') }}">
                @error('tanggal')
                    <div class="invalid-feedback">*{{ $message }}</div>
                @enderror
            </div>

            <!-- Nama Produk -->
            <div class="col-md-6 mb-3">
                <label class="form-label" for="nama">Nama Produk</label>
                <input type="text"
                       name="nama"
                       id="nama"
                       class="form-control @error('nama') is-invalid @enderror"
                       value="{{ old('nama') }}"
                       placeholder="Masukkan Nama Produk">
                @error('nama')
                    <div class="invalid-feedback">*{{ $message }}</div>
                @enderror
            </div>

            <!-- Harga -->
            <div class="col-md-6 mb-3">
                <label class="form-label" for="harga">Harga (Rp)</label>
                <input type="number"
                       name="harga"
                       id="harga"
                       step="0.01"
                       min="0"
                       class="form-control @error('harga') is-invalid @enderror"
                       value="{{ old('harga') }}"
                       placeholder="Masukkan Harga Produk">
                @error('harga')
                    <div class="invalid-feedback">*{{ $message }}</div>
                @enderror
            </div>

            <!-- Stok -->
            <div class="col-md-6 mb-3">
                <label class="form-label" for="stok">Stok</label>
                <input type="number"
                       name="stok"
                       id="stok"
                       min="0"
                       class="form-control @error('stok') is-invalid @enderror"
                       value="{{ old('stok') }}"
                       placeholder="Masukkan Stok Produk">
                @error('stok')
                    <div class="invalid-feedback">*{{ $message }}</div>
                @enderror
            </div>

            <!-- Kategori -->
            <div class="col-md-6 mb-3">
                <label class="form-label" for="kategori_produk_id">Kategori</label>
                <select name="kategori_produk_id"
                        id="kategori_produk_id"
                        class="form-select @error('kategori_produk_id') is-invalid @enderror">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}" {{ old('kategori_produk_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama }}
                        </option>
                    @endforeach
                </select>
                @error('kategori_produk_id')
                    <div class="invalid-feedback">*{{ $message }}</div>
                @enderror
            </div>

            <!-- Gambar -->
            <div class="col-md-6 mb-3">
                <label class="form-label" for="gambar">Gambar</label>
                <input type="file"
                       name="gambar"
                       id="gambar"
                       accept="image/*"
                       class="form-control @error('gambar') is-invalid @enderror"
                       placeholder="Pilih Gambar Produk">
                @error('gambar')
                    <div class="invalid-feedback">*{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan Produk</button>
            <a href="{{ route('produk.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
