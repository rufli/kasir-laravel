@extends('layouts.app')

@section('title', 'Tambah Pegawai')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pegawai/create.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
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
                        <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                            class="form-control @error('nama') is-invalid @enderror" placeholder="Masukkan Nama">
                        @error('nama')
                            <div class="invalid-feedback">*{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea name="alamat" id="alamat" rows="2" class="form-control @error('alamat') is-invalid @enderror"
                            placeholder="Masukkan Alamat">{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">*{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="no_telepon">No. Telp</label>
                        <input type="text" name="no_telepon" id="no_telepon" value="{{ old('no_telepon') }}"
                            class="form-control @error('no_telepon') is-invalid @enderror"
                            placeholder="Masukkan No Telepon">
                        @error('no_telepon')
                            <div class="invalid-feedback">*{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username"
                            class="form-control @error('username') is-invalid @enderror" placeholder="Masukkan Username">
                        @error('username')
                            <div class="invalid-feedback">*{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="position-relative">
                            <input type="password" name="password" id="password"
                                class="form-control pe-5 @error('password') is-invalid @enderror"
                                placeholder="Masukkan Password">
                            <span class="position-absolute top-50 end-0 translate-middle-y me-3 toggle-password"
                                onclick="togglePassword()" style="cursor: pointer;">
                                <i class="bi bi-eye"></i>
                            </span>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">*{{ $message }}</div>
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

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.querySelector('.toggle-password i');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        }
    </script>
@endsection
