@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/kategori_pengeluaran/edit.css') }}">
@endpush

@section('title', 'Edit Kategori Pengeluaran')

@section('content')
<div class="edit-kategori-pengeluaran">
    <div class="card">
        <div class="card-header">
            <h5>Edit Kategori Pengeluaran</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('kategori_pengeluaran.update', $kategori_pengeluaran->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" name="nama" id="nama"
                           value="{{ old('nama', $kategori_pengeluaran->nama) }}"
                           class="form-control @error('nama') is-invalid @enderror" >
                    @error('nama')
                        <div class="invalid-feedback">*{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">Simpan</button>
                    <button type="button" class="btn-secondary" onclick="window.location='{{ route('kategori_pengeluaran.index') }}'">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
