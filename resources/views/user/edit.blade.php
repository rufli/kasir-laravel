@extends('layouts.app')

@section('title', 'Edit Profile')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/user/edit.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
@endpush

@section('content')
    <div class="container">
        <div class="profile-wrapper">
            {{-- Box Kiri --}}
            <div class="profile-box left">

                {{-- Form Update Foto --}}
                <form action="{{ route('profile.updateFoto') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    @if ($user->img_profile && file_exists(storage_path('app/public/' . $user->img_profile)))
                        <img src="{{ asset('storage/' . $user->img_profile) }}" alt="Profile Picture" class="profile-img">
                    @else
                        <img src="{{ asset('storage/images/profile.jpg') }}" alt="Default Profile" class="profile-img">
                    @endif

                    <div class="profile-btn-group">
                        <label class="btn-change">
                            <i class="bi bi-image"></i> Ganti Foto
                            <input type="file" name="img_profile" accept="image/*" hidden onchange="this.form.submit()">
                        </label>
                        @if ($user->img_profile)
                            <button type="submit" name="delete_img" value="1" class="btn-delete">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        @endif
                    </div>
                    <small class="text-muted">Format: JPG, JPEG, PNG. Max 5MB</small>

                    {{-- Error untuk foto --}}
                    @error('img_profile')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </form>

                {{-- Form Update Password --}}
                <form action="{{ route('profile.updatePassword') }}" method="POST" class="mt-3">
                    @csrf
                    @method('PUT')

                    <div class="form-group mt-3">
                        <button type="button" id="togglePasswordForm" class="btn-secondary" style="width: 100%;">
                            <i class="bi bi-key"></i> Ganti Password
                        </button>
                    </div>

                    {{-- Field Password --}}
                    <div id="passwordFields" class="password-box" style="display: none;">
                        <div class="form-group">
                            <label for="password">Password Baru</label>
                            <div class="input-icon">
                                <i class="bi bi-lock"></i>
                                <input type="password" name="password" id="password" placeholder="Masukkan password baru">
                            </div>
                            @error('password')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password Baru</label>
                            <div class="input-icon">
                                <i class="bi bi-lock-fill"></i>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    placeholder="Ulangi password baru">
                            </div>
                        </div>
                        <small class="text-muted d-block mt-1">Kosongkan jika tidak ingin mengganti password.</small>

                        <button type="submit" class="btn-edit mt-2" style="width: 100%;">Simpan Password</button>
                    </div>
                </form>
            </div>

            {{-- Box Kanan --}}
            <div class="profile-box right">
                <h2 class="profile-title">Edit Profile</h2>

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
                    class="profile-form" autocomplete="off">
                    @csrf
                    @method('PUT')

                    {{-- Nama --}}
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama', $user->nama) }}" >
                        @error('nama')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Username --}}
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}"
                            >
                        @error('username')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Admin Only --}}
                    @if ($user->role === 'admin')
                        <div class="form-group">
                            <label for="nama_usaha">Nama Usaha</label>
                            <input type="text" name="nama_usaha" id="nama_usaha"
                                value="{{ old('nama_usaha', $user->nama_usaha) }}">
                            @error('nama_usaha')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="alamat_usaha">Alamat Usaha</label>
                            <textarea name="alamat_usaha" id="alamat_usaha">{{ old('alamat_usaha', $user->alamat_usaha) }}</textarea>
                            @error('alamat_usaha')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>
                    @endif

                    {{-- Pegawai Only --}}
                    @if ($user->role === 'pegawai')
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea name="alamat" id="alamat" >{{ old('alamat', $user->alamat) }}</textarea>
                            @error('alamat')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>
                    @endif

                    {{-- No Telepon --}}
                    <div class="form-group">
                        <label for="no_telepon">No Telepon</label>
                        <input type="text" name="no_telepon" id="no_telepon"
                            value="{{ old('no_telepon', $user->no_telepon) }}" >
                        @error('no_telepon')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Tombol Simpan/Batal --}}
                    <div class="profile-actions">
                        <a href="{{ route('profile') }}" class="btn-secondary">Batal</a>
                        <button type="submit" class="btn-edit">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('togglePasswordForm').addEventListener('click', function() {
            const passwordFields = document.getElementById('passwordFields');
            passwordFields.style.display = passwordFields.style.display === 'none' ? 'block' : 'none';
        });
    </script>
@endpush
