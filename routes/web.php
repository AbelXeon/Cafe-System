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



// Auth Routes
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.view');
Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes (Protected)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // User / Staff / Delivery Management
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');

    // Category Management
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');

    // Product Management (Food & Drinks)
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::delete('/products/{id}', [AdminController::class, 'deleteProduct'])->name('products.delete');

    // Extras Management
    Route::post('/extras', [AdminController::class, 'storeExtra'])->name('extras.store');
});