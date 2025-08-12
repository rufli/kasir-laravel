<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriProdukController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\SignupController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\KategoriPengeluaranController;
use App\Http\Controllers\PenjualanController;

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index_alt');

    Route::resource('kategori_produk', KategoriProdukController::class);
    Route::resource('produk', ProdukController::class);

    Route::prefix('pegawai')->name('pegawai.')->group(function () {
        Route::get('/', [SignupController::class, 'index'])->name('index');
        Route::get('/create', [SignupController::class, 'create'])->name('create');
        Route::post('/', [SignupController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [SignupController::class, 'edit'])->name('edit');
        Route::put('/{id}', [SignupController::class, 'update'])->name('update');
        Route::delete('/{id}', [SignupController::class, 'destroy'])->name('destroy');
    });
    Route::resource('pengeluaran', PengeluaranController::class);
    Route::resource('kategori_pengeluaran', KategoriPengeluaranController::class);

    Route::get('/penjualan', [PenjualanController::class, 'daftarProduk'])->name('penjualan.daftar_produk');
    Route::post('/penjualan/tambah', [PenjualanController::class, 'tambahKeKeranjang'])->name('penjualan.tambah');
    Route::get('/penjualan/keranjang', [PenjualanController::class, 'keranjang'])->name('penjualan.keranjang');
    Route::get('/penjualan/checkout', [PenjualanController::class, 'checkoutPage'])->name('penjualan.checkout.page');
    Route::post('/penjualan/checkout', [PenjualanController::class, 'checkout'])->name('penjualan.checkout');
    Route::get('/penjualan/detail/{id}', [PenjualanController::class, 'detailTransaksi'])->name('penjualan.detail');
    Route::get('/penjualan/riwayat', [PenjualanController::class, 'riwayatTransaksi'])->name('penjualan.riwayat');
    Route::delete('/penjualan/keranjang/{id}', [PenjualanController::class, 'hapusDariKeranjang'])->name('penjualan.hapus');
});
// routes/web.php
