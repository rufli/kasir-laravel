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
use App\Http\Controllers\LaporanKeuanganController;
use App\Http\Controllers\ProfileController;

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Rute yang dapat diakses oleh Admin & Pegawai
Route::middleware(['auth:sanctum', 'role:admin,pegawai'])->group(function () {
    // Rute Profil
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'profile'])->name('profileedit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
    Route::post('/profile/password/change', [ProfileController::class, 'gantiPassword'])->name('profile.password.change');
    Route::put('/profile/update-foto', [ProfileController::class, 'updateFoto'])->name('profile.updateFoto');
    Route::put('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');

    // Rute Produk yang bisa diakses semua (read-only)
    Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
    Route::get('/produk/{produk}', [ProdukController::class, 'show'])->name('produk.show');

    // Rute Penjualan & Riwayat (dapat diakses oleh admin & pegawai)
    Route::get('/penjualan/detail/{id}', [PenjualanController::class, 'detailTransaksi'])->name('penjualan.detail');
    Route::get('/penjualan/riwayat', [PenjualanController::class, 'riwayatTransaksi'])->name('penjualan.riwayat');

    // Rute Laporan (jika diperlukan untuk pegawai, bisa juga dipindah ke role:admin)
    Route::prefix('laporan')->group(function () {
        Route::get('/', [LaporanKeuanganController::class, 'index'])->name('laporan.keuangan');
        Route::get('/harian', [LaporanKeuanganController::class, 'harian'])->name('laporan.harian');
    });
});

// Rute khusus untuk Admin
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index_alt');
    Route::get('/dashboard/data', [DashboardController::class, 'data'])->name('dashboard.data');

    // Rute Produk (yang dapat memodifikasi data)
    Route::resource('produk', ProdukController::class)->except(['index', 'show']);
    Route::get('/produk/nonaktif', [ProdukController::class, 'inactiveIndex'])->name('produk.inactive-index');
    Route::post('/produk/{id}/toggle-status', [ProdukController::class, 'toggleStatus'])->name('produk.toggle-status');

    // Rute Manajemen Kategori & Pegawai
    Route::resource('kategori_produk', KategoriProdukController::class);
    Route::resource('pengeluaran', PengeluaranController::class);
    Route::resource('kategori_pengeluaran', KategoriPengeluaranController::class);
    Route::prefix('pegawai')->name('pegawai.')->group(function () {
        Route::get('/', [SignupController::class, 'index'])->name('index');
        Route::get('/create', [SignupController::class, 'create'])->name('create');
        Route::post('/', [SignupController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [SignupController::class, 'edit'])->name('edit');
        Route::put('/{id}', [SignupController::class, 'update'])->name('update');
        Route::delete('/{id}', [SignupController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/toggle-status', [SignupController::class, 'toggleStatus'])->name('toggle-status');
    });
});

// Rute khusus untuk Pegawai
Route::middleware(['auth:sanctum', 'role:pegawai'])->group(function () {
    Route::get('/penjualan', [PenjualanController::class, 'daftarProduk'])->name('penjualan.daftar_produk');
    Route::post('/penjualan/tambah', [PenjualanController::class, 'tambahKeKeranjang'])->name('penjualan.tambah');
    Route::get('/penjualan/keranjang', [PenjualanController::class, 'keranjang'])->name('penjualan.keranjang');
    Route::get('/penjualan/checkout', [PenjualanController::class, 'checkoutPage'])->name('penjualan.checkout.page');
    Route::post('/penjualan/checkout', [PenjualanController::class, 'checkout'])->name('penjualan.checkout');
    Route::delete('/penjualan/keranjang/{id}', [PenjualanController::class, 'hapusDariKeranjang'])->name('penjualan.hapus');
});
