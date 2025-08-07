@extends('layouts.app')

@section('title', 'Edit Pegawai')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pegawai.css') }}">
@endpush

@section('content')
    <h2 class="edit-pegawai-title">Edit Pegawai</h2>
    <div class="edit-pegawai-form-container">
        <form action="{{ route('pegawai.update', $pegawai->id) }}" method="POST" class="edit-pegawai-form">
            @csrf
            @method('PUT')

            <div class="edit-form-group">
                <label for="nama">Nama Pegawai</label>
                <input type="text" name="nama" id="nama" value="{{ old('nama', $pegawai->nama) }}"
                    class="@error('nama') is-invalid @enderror" required>
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="edit-form-group">
                <label for="alamat">Alamat</label>
                <textarea name="alamat" id="alamat" rows="2" class="@error('alamat') is-invalid @enderror">{{ old('alamat', $pegawai->alamat) }}</textarea>
                @error('alamat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="edit-form-group">
                <label for="no_telepon">No. Telp</label>
                <input type="text" name="no_telepon" id="no_telepon"
                    value="{{ old('no_telepon', $pegawai->no_telepon) }}" class="@error('no_telepon') is-invalid @enderror">
                @error('no_telepon')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="edit-form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" value="{{ old('username', $pegawai->username) }}"
                    class="@error('username') is-invalid @enderror" required>
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="edit-form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="@error('password') is-invalid @enderror">
                <small class="form-note">Kosongkan jika tidak ingin mengubah password</small>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


            <div class="edit-form-actions">
                <a href="{{ route('pegawai.index') }}" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-submit">Update</button>
            </div>
        </form>
    </div>
@endsection
