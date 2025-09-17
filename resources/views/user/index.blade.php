@extends('layouts.app')

@section('title', 'Profile')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/user/index.css') }}">
<style>
    .profile-detail {
        padding: 6px 0;
        border-bottom: 1px solid #ccc;
        margin-bottom: 12px;
        font-size: 15px;
    }

    .profile-label {
        font-weight: bold;
        display: block;
        margin-bottom: 2px;
        color: #555;
    }
</style>
@endpush

@section('content')
<div class="profile-wrapper">

    <!-- Box Kiri -->
    <div class="profile-box left">
        <div class="profile-header">
            <img src="{{ asset($user->img_profile ? 'storage/' . $user->img_profile : 'images/default profile.jpg') }}" alt="Profile Picture" class="profile-img">

            <!-- Tambahan Nama & Role -->
            <div class="profile-info">
                <h4 class="profile-name">{{ $user->nama }}</h4>
                <p class="profile-role">{{ ucfirst($user->role) }}</p>
            </div>
        </div>
    </div>

    <!-- Box Kanan -->
    <div class="profile-box right">
        <h3 class="profile-title">Informasi {{ ucfirst($user->role) }}</h3>
        <div class="profile-form">

            <div class="profile-detail">
                <span class="profile-label">Username</span>
                <span>{{ $user->username }}</span>
            </div>

            @if ($user->role === 'admin')
                <div class="profile-detail">
                    <span class="profile-label">Nama Usaha</span>
                    <span>{{ $user->nama_usaha ?? '-' }}</span>
                </div>
                <div class="profile-detail">
                    <span class="profile-label">Alamat Usaha</span>
                    <span>{{ $user->alamat_usaha ?? '-' }}</span>
                </div>
            @elseif ($user->role === 'pegawai')
                <div class="profile-detail">
                    <span class="profile-label">Alamat</span>
                    <span>{{ $user->alamat ?? '-' }}</span>
                </div>
            @endif

            <div class="profile-detail">
                <span class="profile-label">No Telepon</span>
                <span>{{ $user->no_telepon ?? '-' }}</span>
            </div>

            <button type="button" onclick="window.location='{{ route('profileedit') }}'" class="btn-edit">
                Edit Profile
            </button>
        </div>
    </div>

</div>
@endsection
