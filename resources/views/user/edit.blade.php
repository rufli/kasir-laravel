@extends('layouts.app')

@section('title', 'Edit Profile')

@push('styles')
    {{-- CSS Custom --}}
    <link rel="stylesheet" href="{{ asset('css/user/edit.css') }}">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
@endpush

@section('content')
<div class="container">
    <div class="profile-card">
        <h2 class="profile-title">Edit Profile</h2>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="profile-form" autocomplete="off">
            @csrf
            @method('PUT')

            {{-- Foto Profil --}}
            <div class="profile-header">
                @if ($user->img_profile && file_exists(storage_path('app/public/' . $user->img_profile)))
                    <img src="{{ asset('storage/' . $user->img_profile) }}" alt="Profile Picture" class="profile-img">
                @else
                    <img src="{{ asset('storage/images/profile.jpg') }}" alt="Default Profile" class="profile-img">
                @endif

                <div class="profile-btn-group">
                    <label class="btn-change">
                        <i class="bi bi-image"></i> Ganti Foto
                        <input type="file" name="img_profile" accept="image/*" hidden>
                    </label>
                    <button type="submit" name="delete_img" value="1" class="btn-delete">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                </div>
                <small class="text-muted">Format: JPG, JPEG, PNG. Max 5MB</small>
            </div>

            {{-- Error Validation --}}
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
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" name="nama" id="nama" value="{{ old('nama', $user->nama) }}" required>
            </div>

            {{-- Username --}}
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" required>
                <small class="text-muted">Hanya huruf, angka, dan garis bawah. Contoh: nadia_123</small>
            </div>

            {{-- Admin Only --}}
            @if ($user->role === 'admin')
                <div class="form-group">
                    <label for="nama_usaha">Nama Usaha</label>
                    <input type="text" name="nama_usaha" id="nama_usaha" value="{{ old('nama_usaha', $user->nama_usaha) }}">
                </div>
                <div class="form-group">
                    <label for="alamat_usaha">Alamat Usaha</label>
                    <textarea name="alamat_usaha" id="alamat_usaha">{{ old('alamat_usaha', $user->alamat_usaha) }}</textarea>
                </div>
            @endif

            {{-- Pegawai Only --}}
            @if ($user->role === 'pegawai')
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea name="alamat" id="alamat" required>{{ old('alamat', $user->alamat) }}</textarea>
                </div>
            @endif

            {{-- No Telepon --}}
            <div class="form-group">
                <label for="no_telepon">No Telepon</label>
                <input type="text" name="no_telepon" id="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}" required>
            </div>

            {{-- Toggle Ganti Password --}}
            <div class="form-group">
                <button type="button" id="togglePasswordForm" class="btn-secondary" style="width: 100%;">
                    <i class="bi bi-key"></i> Ganti Password
                </button>
            </div>

            {{-- Field Password (tersembunyi) --}}
            <div id="passwordFields" style="display: none;">
                <div class="form-group">
                    <label for="password">Password Baru</label>
                    <input type="password" name="password" id="password" value="" autocomplete="new-password">
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" value="" autocomplete="new-password">
                </div>
                <small class="text-muted">Kosongkan jika tidak ingin mengganti password.</small>
            </div>

            {{-- Tombol Simpan/Batal --}}
            <div class="d-flex justify-content-between mt-3">
                <a href="{{ route('profile') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-edit">Simpan Perubahan</button>
            </div>

        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('togglePasswordForm').addEventListener('click', function() {
        const passwordFields = document.getElementById('passwordFields');
        const isHidden = passwordFields.style.display === 'none';
        passwordFields.style.display = isHidden ? 'block' : 'none';

        if (isHidden) {
            // Kosongkan field password saat ditampilkan
            document.getElementById('password').value = '';
            document.getElementById('password_confirmation').value = '';
        }
    });
</script>
@endpush
