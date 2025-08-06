@extends('layouts.app')

@section('title', 'Tambah Kategori Pengeluaran')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pengeluaran.css') }}">
@endpush

@section('content')
<h2 class="form-title">Tambah Kategori Pengeluaran</h2>
<div class="pengeluaran-form-container">

    <form action="{{ route('kategori_pengeluaran.store') }}" method="POST" class="pengeluaran-form">
        @csrf

        <div class="form-group">
            <label for="nama">Nama</label>
            <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required>
        </div>

        <div class="form-actions">
            <a href="{{ route('pengeluaran.create') }}" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-submit">Simpan</button>
        </div>
    </form>
</div>
@endsection
