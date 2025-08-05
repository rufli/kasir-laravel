<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KategoriProdukController;

// Login
Route::post('/login', [AuthController::class, 'authenticate']);

// Logout (harus login dan punya token)
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// kategori produk (semua endpoint perlu token)
Route::middleware('auth:sanctum')->apiResource('kategori-produk', KategoriProdukController::class);
