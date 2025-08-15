@extends('layouts.app')

@section('title', 'Profile')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/user/index.css') }}">
@endpush

@section('content')
<div class="profile-card">
    <h3 class="profile-title">Profile Admin</h3>

    <div class="profile-header">
        @if ($user->img_profile && file_exists(public_path('profile/' . $user->img_profile)))
            <img src="{{ asset('profile/' . $user->img_profile) }}" alt="Profile Picture" class="profile-img">
        @else
            <img src="{{ asset('images/admin.jpg') }}" alt="Default Profile" class="profile-img">
        @endif
    </div>

    <form class="profile-form">
        <div class="form-group">
            <label>Nama</label>
            <input type="text" value="{{ $user->nama }}" readonly>
        </div>

        <div class="form-group">
            <label>Username</label>
            <input type="text" value="{{ $user->role }}" readonly>
        </div>

        @if ($user->role === 'admin')
            <div class="form-group">
                <label>Nama Usaha</label>
                <input type="text" value="{{ $user->nama_usaha ?? '-' }}" readonly>
            </div>

            <div class="form-group">
                <label>Alamat Usaha</label>
                <textarea readonly class="alamat-textarea">{{ $user->alamat_usaha ?? '-' }}</textarea>
            </div>

            <div class="form-group">
                <label>No Telepon</label>
                <input type="text" value="{{ $user->no_telepon ?? '-' }}" readonly>
            </div>
        @elseif ($user->role === 'pegawai')
            <div class="form-group">
                <label>Alamat</label>
                <textarea readonly class="alamat-textarea">{{ $user->alamat ?? '-' }}</textarea>
            </div>

            <div class="form-group">
                <label>No Telepon</label>
                <input type="text" value="{{ $user->no_telepon ?? '-' }}" readonly>
            </div>
        @endif

        <button type="button" onclick="window.location='{{ route('profileedit') }}'" class="btn-edit">
            Edit Profile
        </button>
    </form>
</div>
@endsection
