<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\KategoriPengeluaranController;
use App\Http\Controllers\Api\PengeluaranController;


// Login (tanpa perlu login)
Route::post('/login', [AuthController::class, 'authenticate']);


// Grup route yang wajib login
Route::middleware('auth:sanctum')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Route API yang butuh login
    Route::apiResource('/kategori-pengeluaran', KategoriPengeluaranController::class);
    Route::apiResource('pengeluaran', PengeluaranController::class);
});

