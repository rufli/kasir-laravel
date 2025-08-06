@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

<div class="dashboard-container">
    <h2 class="dashboard-title">Dashboard</h2>

    <div class="card-grid">
        <!-- Card Total Penjualan -->
        <div class="info-card info-card-blue">
            <h3 class="card-title">Total Penjualan</h3>
            <p class="card-value">Rp 5.250.000</p>
            <p class="card-description text-blue-600">+12% dari bulan lalu</p>
        </div>

        <!-- Card Total Pengeluaran -->
        <div class="info-card info-card-green">
            <h3 class="card-title">Total Pengeluaran</h3>
            <p class="card-value">Rp 2.100.000</p>
            <p class="card-description text-green-600">+5% dari bulan lalu</p>
        </div>

        <!-- Card Laporan Keuangan -->
        <div class="info-card info-card-yellow">
            <h3 class="card-title">Laporan Keuangan</h3>
            <p class="card-value">Rp 3.150.000</p>
            <p class="card-description text-yellow-600">Laba bersih bulan ini</p>
        </div>
    </div>

    <div class="bestseller-section">
        <h3>Produk Terlaris</h3>
        <!-- Daftar produk terlaris akan ditampilkan di sini -->
        <p>Fitur ini akan menampilkan daftar produk terlaris dalam periode tertentu.</p>
    </div>
</div>
@endsection
