@extends('layouts.app')

@section('title', 'Edit Profile')

@push('styles')
    {{-- CSS Custom --}}
    <link rel="stylesheet" href="{{ asset('css/user/edit.css') }}">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
@endpush

@section('content')
    <div class="container edit-profile-container">
        <div class="row gutters-sm">

            {{-- Satu Form untuk Semua --}}
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="row gutters-sm">
                @csrf
                @method('PUT')

                {{-- Kolom Kiri --}}
                <div class="col-md-4 mb-3">
                    <div class="card text-center">
                        <div class="card-body">
                            {{-- Foto Profil --}}
                            @if ($user->img_profile && file_exists(storage_path('app/public/' . $user->img_profile)))
                                <img src="{{ asset('storage/' . $user->img_profile) }}" alt="Profile Picture"
                                    class="rounded-circle mb-3" width="150" height="150">
                            @else
                                <img src="{{ asset('storage/images/profile.jpg') }}" alt="Default Profile"
                                    class="rounded-circle mb-3" width="150" height="150">
                            @endif

                            {{-- Input Ganti Foto --}}
                            <div class="mb-2">
                                <input type="file" name="img_profile" class="form-control" accept="image/*">
                                <small class="text-muted">Format: JPG, JPEG, PNG. Max 5MB</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan --}}
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <strong>Terjadi kesalahan!</strong>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{-- Nama --}}
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <div class="input-group">
                                    <span class="input-icon"><i class="bi bi-person"></i></span>
                                    <input type="text" name="nama" id="nama" class="form-control"
                                        value="{{ old('nama', $user->nama) }}" required>
                                </div>
                            </div>

                            {{-- Username --}}
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <div class="input-group">
                                    <span class="input-icon"><i class="bi bi-lock"></i></span>
                                    <input type="text" name="username" id="username" class="form-control"
                                        value="{{ old('username', $user->username) }}" required>
                                </div>
                                <small class="text-muted">Hanya huruf, angka, dan garis bawah. Contoh: nadia_123</small>
                            </div>

                            {{-- Nama Usaha / Alamat Usaha (jika admin) --}}
                            @if ($user->role === 'admin')
                                <div class="mb-3">
                                    <label for="nama_usaha" class="form-label">Nama Usaha</label>
                                    <div class="input-group">
                                        <span class="input-icon"><i class="bi bi-shop"></i></span>
                                        <input type="text" name="nama_usaha" id="nama_usaha" class="form-control"
                                            value="{{ old('nama_usaha', $user->nama_usaha) }}">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="alamat_usaha" class="form-label">Alamat Usaha</label>
                                    <div class="input-group">
                                        <span class="input-icon"><i class="bi bi-geo-alt"></i></span>
                                        <input type="text" name="alamat_usaha" id="alamat_usaha" class="form-control"
                                            value="{{ old('alamat_usaha', $user->alamat_usaha) }}">
                                    </div>
                                </div>
                            @endif

                            @if ($user->role === 'pegawai')
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <div class="input-group">
                                    <span class="input-icon"><i class="bi bi-house"></i></span>
                                    <input type="text" name="alamat" id="alamat" class="form-control"
                                        value="{{ old('alamat', $user->alamat) }}" required>
                                </div>
                            </div>
                            @endif

                            {{-- No Telepon --}}
                            <div class="mb-3">
                                <label for="no_telepon" class="form-label">No Telepon</label>
                                <div class="input-group">
                                    <span class="input-icon"><i class="bi bi-telephone"></i></span>
                                    <input type="text" name="no_telepon" id="no_telepon" class="form-control"
                                        value="{{ old('no_telepon', $user->no_telepon) }}" required>
                                </div>
                            </div>

                            {{-- Tombol Ganti Password --}}
                            <div class="mb-3">
                                <button type="button" class="btn btn-outline-warning btn-sm" id="togglePasswordForm">
                                    <i class="bi bi-key"></i> Ganti Password
                                </button>
                            </div>

                            {{-- Field Password (Hidden Default) --}}
                            <div id="passwordFields" style="display: none;">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password Baru</label>
                                    <input type="password" name="password" id="password" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="form-control">
                                </div>
                                <small class="text-muted">Kosongkan jika tidak ingin mengganti password.</small>
                            </div>

                            {{-- Tombol --}}
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('profile') }}" class="btn btn-secondary">Batal</a>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>

                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('togglePasswordForm').addEventListener('click', function() {
            const passwordFields = document.getElementById('passwordFields');
            passwordFields.style.display = (passwordFields.style.display === 'none') ? 'block' : 'none';
        });
    </script>
@endpush
