@extends('layouts.app')

@section('title', 'Edit Kategori Produk')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/kategori_produk/edit.css') }}">
@endpush

@section('content')
<div class="kategori-edit">
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
                    <input type="text" 
                           class="form-control @error('nama') is-invalid @enderror"
                           id="nama" name="nama"
                           value="{{ old('nama', $kategoriProduk->nama) }}" 
                           required>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">Update</button>
                    <button type="button" class="btn-secondary" onclick="window.location='{{ route('kategori_produk.index') }}'">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
