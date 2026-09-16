<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiMenuController;
use App\Http\Controllers\Api\ApiAdminController;

Route::post('/login', [ApiAuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [ApiAuthController::class, 'logout']);
    Route::get('/menu', [ApiMenuController::class, 'index']);

     // Admin API endpoint
    Route::get('/admin/dashboard', [ApiAdminController::class, 'dashboard']);
});