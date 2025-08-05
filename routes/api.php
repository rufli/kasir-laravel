<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\KategoriPengeluaranController;
use App\Http\Controllers\Api\PengeluaranController;
use App\Http\Controllers\Api\SignupController;
use App\Http\Controllers\Api\KategoriProdukController;
use App\Http\Controllers\Api\ProdukController;

// Login (tanpa perlu login)
Route::post('/login', [AuthController::class, 'authenticate']);

// Grup route yang wajib login + role admin/pegawai
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // API untuk admin dan pegawai
    Route::apiResource('/kategori-pengeluaran', KategoriPengeluaranController::class);
    Route::apiResource('pengeluaran', PengeluaranController::class);
    Route::apiResource('kategori-produk', KategoriProdukController::class);
    Route::apiResource('produk', ProdukController::class);
    Route::get('/pegawai', [SignupController::class, 'daftarPegawai'])->name('daftarPegawai');
    Route::get('/pegawai/print', [SignupController::class, 'print'])->name('printPegawai');
    Route::get('/pegawai/tambah', [SignupController::class, 'index'])->name('tambahPegawai');
    Route::get('/pegawai/laporan', [SignupController::class, 'laporan'])->name('laporanPegawai');
    Route::post('/pegawai', [SignupController::class, 'register'])->name('registerPegawai');
    Route::put('/pegawai/{id}', [SignupController::class, 'update']);
    Route::delete('/pegawai/{id}', [SignupController::class, 'destroy'])->name('hapusPegawai');
});
