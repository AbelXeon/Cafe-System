<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\SavedLocationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AuthController;



Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});




Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth'])->prefix('admin')->group(function () {
    // Only allow if role is admin
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // Management Pages
    Route::get('/users', [AdminController::class, 'manageUsers'])->name('admin.users');
    Route::post('/users/create', [AdminController::class, 'storeUser'])->name('admin.users.store');
    
    Route::get('/products', [AdminController::class, 'manageProducts'])->name('admin.products');
    Route::post('/products/create', [AdminController::class, 'storeProduct'])->name('admin.products.store');

    Route::post('/extras/create', [AdminController::class, 'storeExtra'])->name('admin.extras.store');
});