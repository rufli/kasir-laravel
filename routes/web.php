<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriProdukController;
use App\Http\Controllers\ProdukController;
<<<<<<< HEAD
use App\Http\Controllers\SignupController;
=======
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\KategoriPengeluaranController; // ← tambahkan ini
>>>>>>> 47a39adb369e990d4d14e818c471f8cb573828e4

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index_alt');

    Route::resource('kategori_produk', KategoriProdukController::class);
    Route::resource('produk', ProdukController::class);
<<<<<<< HEAD

    Route::prefix('pegawai')->name('pegawai.')->group(function () {
        Route::get('/', [SignupController::class, 'index'])->name('index');
        Route::get('/create', [SignupController::class, 'create'])->name('create');
        Route::post('/', [SignupController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [SignupController::class, 'edit'])->name('edit');
        Route::put('/{id}', [SignupController::class, 'update'])->name('update');
        Route::delete('/{id}', [SignupController::class, 'destroy'])->name('destroy');
    });
=======
    Route::resource('pengeluaran', PengeluaranController::class);

    Route::resource('kategori_pengeluaran', KategoriPengeluaranController::class); // ← tambahkan ini
>>>>>>> 47a39adb369e990d4d14e818c471f8cb573828e4
});
