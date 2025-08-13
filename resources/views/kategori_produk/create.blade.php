@extends('layouts.app')



@section('title', 'Tambah Kategori Produk')

<link rel="stylesheet" href="{{ asset('css/kategori_produk/create.css') }}">

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h5>Tambah Kategori Produk</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('kategori_produk.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="nama">Nama Kategori</label>
                    <input type="text" class="form-control @error('nama') is-invalid @enderror"
                           id="nama" name="nama" value="{{ old('nama') }}" required>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary mt-3">Simpan</button>
                <a href="{{ route('kategori_produk.index') }}" class="btn btn-secondary mt-3">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
