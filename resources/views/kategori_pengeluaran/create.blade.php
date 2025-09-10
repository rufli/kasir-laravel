@extends('layouts.app')

@section('title', 'Tambah Kategori Pengeluaran')

<link rel="stylesheet" href="{{ asset('css/kategori_pengeluaran/create.css') }}">

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h5>Tambah Kategori Pengeluaran</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('kategori_pengeluaran.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="nama">Nama Kategori</label>
                    <input type="text"
                           class="form-control @error('nama') is-invalid @enderror"
                           id="nama"
                           name="nama"
                           value="{{ old('nama') }}"
                           placeholder="Masukkan Nama Kategori">
                    @error('nama')
                        <div class="invalid-feedback">*{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary mt-3">Simpan</button>
                    <a href="{{ route('kategori_pengeluaran.index') }}" class="btn btn-secondary mt-3" style="text-decoration: none;">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
