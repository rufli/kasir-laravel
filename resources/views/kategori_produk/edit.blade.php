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

                <div class="form-group mb-3">
                    <label for="nama">Nama Kategori</label>
                    <input type="text"
                           id="nama"
                           name="nama"
                           class="form-control @error('nama') is-invalid @enderror"
                           value="{{ old('nama', $kategoriProduk->nama) }}"
                           maxlength="45"
                           >
                    @error('nama')
                        <div class="invalid-feedback">*{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('kategori_produk.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
