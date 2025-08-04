<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

// Login
Route::post('/login', [AuthController::class, 'authenticate']);

// Logout (harus login dan punya token)
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
