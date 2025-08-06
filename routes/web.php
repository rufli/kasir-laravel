<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriProdukController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PengeluaranController;

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login']);


Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth');
    
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index_alt');

    Route::resource('kategori_produk', KategoriProdukController::class);
    Route::resource('produk', ProdukController::class);

    Route::resource('pengeluaran', PengeluaranController::class);

});
