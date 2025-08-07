@extends('layouts.app')

@section('title', 'Edit Kategori Pengeluaran')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pengeluaran.css') }}">
@endpush

@section('content')
<h2 class="form-title">Edit Kategori Pengeluaran</h2>
<div class="pengeluaran-form-container">

    <form action="{{ route('kategori_pengeluaran.update', $kategori_pengeluaran->id) }}" method="POST" class="pengeluaran-form">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="nama">Nama</label>
            <input type="text" name="nama" id="nama" value="{{ old('nama', $kategori_pengeluaran->nama) }}" required>
        </div>

        <div class="form-actions">
            <a href="{{ route('kategori_pengeluaran.index') }}" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-submit">Perbarui</button>
        </div>
    </form>
</div>
@endsection
