@extends('layouts.app')

@section('title', 'Tambah Pengeluaran')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pengeluaran/create.css') }}">
@endpush

@section('content')
    <div class="container">
        <h2>Tambah Pengeluaran</h2>
        <form action="{{ route('pengeluaran.store') }}" method="POST">
            @csrf
            <div class="row">
                <!-- Tanggal -->
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="tanggal">Tanggal</label>
                    <input type="date"
                           name="tanggal"
                           id="tanggal"
                           class="form-control @error('tanggal') is-invalid @enderror"
                           value="{{ old('tanggal') }}">
                    @error('tanggal')
                        <div class="invalid-feedback">*{{ $message }}</div>
                    @enderror
                </div>

                <!-- Nama -->
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="nama">Nama</label>
                    <input type="text"
                           name="nama"
                           id="nama"
                           class="form-control @error('nama') is-invalid @enderror"
                           value="{{ old('nama') }}">
                    @error('nama')
                        <div class="invalid-feedback">*{{ $message }}</div>
                    @enderror
                </div>

                <!-- Kategori -->
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="kategori_pengeluaran_id">Kategori</label>
                    <small>
                        <a href="{{ route('kategori_pengeluaran.create') }}" class="btn-tambah-kategori">
                            Tambah Kategori +
                        </a>
                    </small>

                    <select name="kategori_pengeluaran_id"
                            id="kategori_pengeluaran_id"
                            class="form-select @error('kategori_pengeluaran_id') is-invalid @enderror">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($kategori as $item)
                            <option value="{{ $item->id }}"
                                {{ old('kategori_pengeluaran_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_pengeluaran_id')
                        <div class="invalid-feedback">*{{ $message }}</div>
                    @enderror
                </div>

                <!-- Satuan -->
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="satuan">Satuan</label>
                    <input type="text"
                           name="satuan"
                           id="satuan"
                           class="form-control @error('satuan') is-invalid @enderror"
                           value="{{ old('satuan') }}"
                           placeholder="Contoh: kg, liter, pcs">
                    @error('satuan')
                        <div class="invalid-feedback">*{{ $message }}</div>
                    @enderror
                </div>

                <!-- Jumlah -->
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="jumlah">Jumlah</label>
                    <input type="number"
                           name="jumlah"
                           id="jumlah"
                           class="form-control @error('jumlah') is-invalid @enderror"
                           value="{{ old('jumlah') }}">
                    @error('jumlah')
                        <div class="invalid-feedback">*{{ $message }}</div>
                    @enderror
                </div>

                <!-- Catatan -->
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="catatan">Catatan</label>
                    <textarea name="catatan"
                              id="catatan"
                              class="form-control @error('catatan') is-invalid @enderror"
                              rows="3">{{ old('catatan') }}</textarea>
                    @error('catatan')
                        <div class="invalid-feedback">*{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('pengeluaran.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
@endsection
