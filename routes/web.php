<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriProdukController;
use App\Http\Controllers\ProdukController;

/*
Route::get('/', function () {
    return view('welcome');
});*/

// Rute untuk halaman Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index_alt'); // Opsional: rute alternatif

// Rute resource untuk KategoriProduk
Route::resource('kategori_produk', KategoriProdukController::class);

// Rute resource untuk Produk
Route::resource('produk', ProdukController::class);
