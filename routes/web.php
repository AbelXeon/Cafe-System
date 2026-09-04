<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
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



Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::post('/staff', [AdminController::class, 'storeStaff'])->name('staff.store');
    Route::post('/extras', [AdminController::class, 'storeExtra'])->name('extras.store');
});


Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');


// Staff / Kitchen Routes
Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [StaffController::class, 'dashboard'])->name('dashboard');
    Route::get('/orders/live', [StaffController::class, 'getLiveOrders'])->name('orders.live');
    Route::patch('/orders/{order}/status', [StaffController::class, 'updateStatus'])->name('orders.status');
});


Route::middleware(['auth', 'role:delivery'])->prefix('delivery')->name('delivery.')->group(function () {
    Route::get('/dashboard', [DeliveryController::class, 'dashboard'])->name('dashboard');
    Route::get('/orders/live', [DeliveryController::class, 'getLiveOrders'])->name('orders.live');
    Route::post('/orders/{order}/accept', [DeliveryController::class, 'acceptOrder'])->name('orders.accept');
    Route::post('/orders/{order}/decline', [DeliveryController::class, 'declineOrder'])->name('orders.decline');
    Route::post('/orders/{order}/delivered', [DeliveryController::class, 'markDelivered'])->name('orders.delivered');
    Route::post('/online', [DeliveryController::class, 'toggleOnline'])->name('online.toggle');
});

Route::middleware(['auth', 'role:customer'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UsersController::class, 'dashboard'])->name('dashboard');
    Route::post('/orders', [UsersController::class, 'storeOrder'])->name('orders.store');
    Route::post('/addresses', [SavedLocationController::class, 'store'])->name('addresses.store');
    Route::delete('/addresses/{savedLocation}', [SavedLocationController::class, 'destroy'])->name('addresses.destroy');
});