@extends('layouts.app')

@section('title', 'Edit Kategori Produk')

<link rel="stylesheet" href="{{ asset('css/kategoriproduk.css') }}">

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h5>Edit Kategori Produk</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('kategori_produk.update', $kategoriProduk->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="nama">Nama Kategori</label>
                    <input type="text" class="form-control @error('nama') is-invalid @enderror"
                           id="nama" name="nama"
                           value="{{ old('nama', $kategoriProduk->nama) }}" required>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary mt-3">Update</button>
                <a href="{{ route('kategori_produk.index') }}" class="btn btn-secondary mt-3">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
