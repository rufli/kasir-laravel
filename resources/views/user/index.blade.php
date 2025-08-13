@extends('layouts.app')

@section('title', 'Profile')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/user/index.css') }}">
@endpush
@section('content')


    <div class="container">
        <div class="row gutters-sm">
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body text-center">
                        @if ($user->img_profile && file_exists(storage_path('app/public/' . $user->img_profile)))
                            <img src="{{ asset('storage/' . $user->img_profile) }}" alt="Profile Picture"
                                class="rounded-circle" width="150" height="150">
                        @else
                            <img src="{{ asset('storage/images/profile.jpg') }}" alt="Default Profile"
                                class="rounded-circle" width="150" height="150">
                        @endif

                        <h4 class="mt-3">{{ $user->nama }}</h4>
                        <p class="text-secondary mb-1">{{ $user->role }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card mb-1">
                    <div class="card-body">
                        @if ($user->role === 'admin')
                            <div class="row mb-2">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Nama Usaha</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">{{ $user->nama_usaha ?? '-' }}</div>
                            </div>
                            <hr>

                            <div class="row mb-2">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Alamat Usaha</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">{{ $user->alamat_usaha ?? '-' }}</div>
                            </div>
                            <hr>

                            <div class="row mb-2">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">No Telepon</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">{{ $user->no_telepon ?? '-' }}</div>
                            </div>
                            <hr>
                        @elseif ($user->role === 'pegawai')
                            <div class="row mb-2">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Alamat</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">{{ $user->alamat ?? '-' }}</div>
                            </div>
                            <hr>

                            <div class="row mb-2">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">No Telepon</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">{{ $user->no_telepon ?? '-' }}</div>
                            </div>
                            <hr>
                        @endif

                        <div class="row">
                            <div class="col-sm-12">
                                <a href="{{ route('profileedit') }}" class="btn btn-info">Edit Profile</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toastEl = document.getElementById('myToast');
            if (toastEl) {
                var myToast = new bootstrap.Toast(toastEl);
                myToast.show();
            }
        });
    </script>

@endsection
