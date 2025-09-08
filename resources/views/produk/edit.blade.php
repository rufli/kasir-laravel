@extends('layouts.app')

@section('title', 'Edit Produk')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/produk/edit.css') }}">
@endpush

@section('content')
<div class="container">
    <h2>Edit Produk</h2>

    <form action="{{ route('produk.update', $produk) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Tanggal -->
            <div class="col-md-6 mb-3">
                <label class="form-label" for="tanggal">Tanggal</label>
                <input type="date"
                       name="tanggal"
                       id="tanggal"
                       class="form-control @error('tanggal') is-invalid @enderror"
                       value="{{ old('tanggal', $produk->tanggal->format('Y-m-d')) }}">
                @error('tanggal')
                    <div class="invalid-feedback">*{{ $message }}</div>
                @enderror
            </div>

            <!-- Nama -->
            <div class="col-md-6 mb-3">
                <label class="form-label" for="nama">Nama Produk</label>
                <input type="text"
                       name="nama"
                       id="nama"
                       maxlength="45"
                       class="form-control @error('nama') is-invalid @enderror"
                       value="{{ old('nama', $produk->nama) }}"
                       >
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
                       value="{{ old('harga', $produk->harga) }}">
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
                       value="{{ old('stok', $produk->stok) }}">
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
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}" {{ $k->id == old('kategori_produk_id', $produk->kategori_produk_id) ? 'selected' : '' }}>
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
                <label class="form-label" for="gambar">Gambar (kosongkan bila tidak diganti)</label>
                <input type="file"
                       name="gambar"
                       id="gambar"
                       accept="image/*"
                       class="form-control @error('gambar') is-invalid @enderror">
                @error('gambar')
                    <div class="invalid-feedback">*{{ $message }}</div>
                @enderror

                @if($produk->gambar)
                    <div class="mt-2">
                        <img src="{{ Storage::url($produk->gambar) }}" width="100" class="img-thumbnail">
                        <br>
                        <small class="text-muted">Gambar saat ini</small>
                    </div>
                @endif
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Perbarui</button>
            <a href="{{ route('produk.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
