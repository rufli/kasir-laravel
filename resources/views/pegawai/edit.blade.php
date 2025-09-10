@extends('layouts.app')

@section('title', 'Edit Pegawai')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pegawai/edit.css') }}">
<!-- Anda bisa menggunakan create.css yang sudah ada atau membuat edit.css terpisah -->
@endpush

@section('content')
<h2 class="form-title">Edit Pegawai</h2>
<div class="pegawai-form-container">

    <form action="{{ route('pegawai.update', $pegawai->id) }}" method="POST" class="pegawai-form">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Nama Pegawai -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nama">Nama Pegawai</label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama', $pegawai->nama) }}" class="@error('nama') is-invalid @enderror" >
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Alamat -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea name="alamat" id="alamat" rows="2" class="@error('alamat') is-invalid @enderror">{{ old('alamat', $pegawai->alamat) }}</textarea>
                    @error('alamat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <!-- No. Telepon -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="no_telepon">No. Telp</label>
                    <input type="text" name="no_telepon" id="no_telepon" value="{{ old('no_telepon', $pegawai->no_telepon) }}" class="@error('no_telepon') is-invalid @enderror">
                    @error('no_telepon')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Username -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" value="{{ old('username', $pegawai->username) }}" class="@error('username') is-invalid @enderror" >
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Password (Opsional: Tambahkan field password untuk ganti password) -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="password">Password (Biarkan kosong jika tidak ingin mengubah)</label>
                    <input type="password" name="password" id="password" class="@error('password') is-invalid @enderror">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-submit">Update</button>
            <a href="{{ route('pegawai.index') }}" class="btn btn-cancel">Batal</a>
        </div>
    </form>
</div>
@endsection
