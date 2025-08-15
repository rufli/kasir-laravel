@extends('layouts.app')

@section('title', 'Tambah Pegawai')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pegawai/create.css') }}">
@endpush

@section('content')
<h2 class="form-title">Tambah Pegawai</h2>
<div class="pegawai-form-container">

    <form action="{{ route('pegawai.store') }}" method="POST" class="pegawai-form">
        @csrf

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nama">Nama Pegawai</label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" class="@error('nama') is-invalid @enderror" required>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea name="alamat" id="alamat" rows="2" class="@error('alamat') is-invalid @enderror">{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="no_telepon">No. Telp</label>
                    <input type="text" name="no_telepon" id="no_telepon" value="{{ old('no_telepon') }}" class="@error('no_telepon') is-invalid @enderror">
                    @error('no_telepon')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" value="{{ old('username') }}" class="@error('username') is-invalid @enderror" required>
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="@error('password') is-invalid @enderror" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-submit">Simpan</button>
            <a href="{{ route('pegawai.index') }}" class="btn btn-cancel">Batal</a>
        </div>
    </form>
</div>
@endsection
