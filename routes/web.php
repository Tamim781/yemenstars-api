<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::middleware('admin')->prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::put('/orders/{id}/status', [DashboardController::class, 'updateOrderStatus'])->name('admin.orders.status');

    Route::post('/categories', [DashboardController::class, 'storeCategory'])->name('admin.categories.store');
    Route::get('/categories/{id}/edit', [DashboardController::class, 'editCategory'])->name('admin.categories.edit');
    Route::put('/categories/{id}', [DashboardController::class, 'updateCategory'])->name('admin.categories.update');
    Route::delete('/categories/{id}', [DashboardController::class, 'deleteCategory'])->name('admin.categories.delete');

    Route::post('/products', [DashboardController::class, 'storeProduct'])->name('admin.products.store');
    Route::get('/products/{id}/edit', [DashboardController::class, 'editProduct'])->name('admin.products.edit');
    Route::put('/products/{id}', [DashboardController::class, 'updateProduct'])->name('admin.products.update');
    Route::patch('/products/{id}/availability', [DashboardController::class, 'toggleProductAvailability'])->name('admin.products.availability');
    Route::delete('/products/{id}', [DashboardController::class, 'deleteProduct'])->name('admin.products.delete');

    Route::post('/offers', [DashboardController::class, 'storeOffer'])->name('admin.offers.store');
    Route::put('/offers/{id}', [DashboardController::class, 'updateOffer'])->name('admin.offers.update');
    Route::delete('/offers/{id}', [DashboardController::class, 'deleteOffer'])->name('admin.offers.delete');

    Route::put('/settings', [DashboardController::class, 'updateSettings'])->name('admin.settings.update');
});
