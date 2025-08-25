@extends('layouts.app')

@section('title', 'Daftar Pegawai')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pegawai/index.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush

@section('content')
    <div class="pegawai-container">
        <div class="pegawai-header">
            <h1>Daftar Pegawai</h1>
            <div class="pegawai-actions">
                <form action="{{ route('pegawai.index') }}" method="GET" class="search-form">
                    <div class="search-input-wrapper">
                        <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}">
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
                <a href="{{ route('pegawai.create') }}" class="btn-tambah">Tambah</a>
            </div>
        </div>

        <div class="pegawai-table">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>No. Telepon</th>
                        <th>Alamat</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($daftarPegawai as $pegawai)
                        <tr>
                            <td data-label="No">{{ $loop->iteration }}</td>
                            <td data-label="Nama">{{ $pegawai->nama }}</td>
                            <td data-label="Username">{{ $pegawai->username }}</td>
                            <td data-label="No. Telepon">{{ $pegawai->no_telepon }}</td>
                            <td data-label="Alamat">{{ $pegawai->alamat }}</td>
                            <td data-label="Status">
                                @if ($pegawai->is_active)
                                    <span class="status-active">Aktif</span>
                                @else
                                    <span class="status-non-aktif">Non-aktif</span>
                                @endif
                            </td>
                            <td data-label="Aksi">
                                <div class="btn-action-group">
                                    <a href="{{ route('pegawai.edit', $pegawai->id) }}" class="btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if ($pegawai->transaksi_penjualans_count == 0)
                                        <form action="{{ route('pegawai.destroy', $pegawai->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus pegawai ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete" title="Hapus">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('pegawai.toggle-status', $pegawai->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-toggle-status"
                                            title="{{ $pegawai->is_active ? 'Non-aktifkan' : 'Aktifkan' }}">
                                            @if ($pegawai->is_active)
                                                <i class="fas fa-toggle-on"></i>
                                            @else
                                                <i class="fas fa-toggle-off"></i>
                                            @endif
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada data pegawai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
