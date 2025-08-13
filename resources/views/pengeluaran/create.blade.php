@extends('layouts.app')

@section('title', 'Tambah Pengeluaran')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pengeluaran/create.css') }}">
@endpush

@section('content')
<div class="container">
    <h2>Tambah Pengeluaran</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pengeluaran.store') }}" method="POST">
        @csrf
        <div class="row">
            <!-- Tanggal -->
            <div class="col-md-6 mb-3">
                <label class="form-label" for="tanggal">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ old('tanggal') }}" required>
            </div>

            <!-- Nama -->
            <div class="col-md-6 mb-3">
                <label class="form-label" for="nama">Nama</label>
                <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama') }}" required>
            </div>

            <!-- Kategori -->
            <div class="col-md-6 mb-3">
                <label class="form-label" for="kategori_pengeluaran_id">Kategori</label>
                <small><a href="{{ route('kategori_pengeluaran.index') }}">Tambah Kategori +</a></small>
                <select name="kategori_pengeluaran_id" id="kategori_pengeluaran_id" class="form-select custom-select">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($kategori as $item)
                        <option value="{{ $item->id }}" {{ old('kategori_pengeluaran_id') == $item->id ? 'selected' : '' }}>
                            {{ $item->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Jumlah -->
            <div class="col-md-6 mb-3">
                <label class="form-label" for="jumlah">Jumlah</label>
                <input type="number" name="jumlah" id="jumlah" class="form-control" value="{{ old('jumlah') }}" required>
            </div>

            <!-- Catatan -->
            <div class="col-md-6 mb-3">
                <label class="form-label" for="catatan">Catatan</label>
                <textarea name="catatan" id="catatan" class="form-control" rows="3">{{ old('catatan') }}</textarea>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('pengeluaran.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
