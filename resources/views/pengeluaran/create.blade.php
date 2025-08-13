@extends('layouts.app')

@section('title', 'Tambah Pengeluaran')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pengeluaran/create.css') }}">
@endpush

@section('content')
<h2 class="form-title">Tambah Pengeluaran</h2>
<div class="pengeluaran-form-container">


    <form action="{{ route('pengeluaran.store') }}" method="POST" class="pengeluaran-form">
        @csrf

        <div class="form-group">
            <label for="tanggal">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal') }}" required>
        </div>

        <div class="form-group">
            <label for="nama">Nama</label>
            <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required>
        </div>

        <div class="form-group">
            <label for="kategori_pengeluaran_id">Kategori</label>
            <div class="kategori-header">
                <small><a href="{{ route('kategori_pengeluaran.index') }}">Tambah Kategori +</a></small>
            </div>
            <select name="kategori_pengeluaran_id" id="kategori_pengeluaran_id" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach ($kategori as $item)
                    <option value="{{ $item->id }}" {{ old('kategori_pengeluaran_id') == $item->id ? 'selected' : '' }}>
                        {{ $item->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="jumlah">Jumlah</label>
            <input type="number" name="jumlah" id="jumlah" value="{{ old('jumlah') }}" required>
        </div>

        <div class="form-group">
            <label for="catatan">Catatan</label>
            <textarea name="catatan" id="catatan" rows="3">{{ old('catatan') }}</textarea>
        </div>

        <div class="form-actions">
            <a href="{{ route('pengeluaran.index') }}" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-submit">Simpan</button>
        </div>
    </form>
</div>
@endsection
