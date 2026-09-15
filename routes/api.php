<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StaffController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::get('/categories', [ApiController::class, 'getCategories']);
Route::get('/products', [ApiController::class, 'getProducts']);
Route::get('/offers', [ApiController::class, 'getOffers']);
Route::get('/payment-methods', [ApiController::class, 'getPaymentMethods']);
Route::get('/settings', [ApiController::class, 'getSettings']);

Route::post('/orders', [ApiController::class, 'createOrder']);
Route::get('/orders', [ApiController::class, 'getOrders']);
Route::get('/orders/{order}', [ApiController::class, 'getOrder']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::get('/favorites', [ApiController::class, 'getFavorites']);
    Route::post('/favorites/{product}', [ApiController::class, 'addFavorite']);
    Route::delete('/favorites/{product}', [ApiController::class, 'removeFavorite']);

    Route::get('/addresses', [ApiController::class, 'getAddresses']);
    Route::post('/addresses', [ApiController::class, 'createAddress']);
    Route::put('/addresses/{address}', [ApiController::class, 'updateAddress']);
    Route::delete('/addresses/{address}', [ApiController::class, 'deleteAddress']);

    Route::post('/orders/{order}/claim', [ApiController::class, 'claimOrder']);
    Route::put('/orders/{order}/status', [ApiController::class, 'updateOrderStatus']);

    Route::prefix('admin')->group(function () {
        Route::get('/stats', [ApiController::class, 'getAdminStats']);
        Route::post('/upload-image', [ApiController::class, 'uploadAdminImage']);
        Route::post('/products', [ApiController::class, 'storeAdminProduct']);
        Route::put('/products/{product}', [ApiController::class, 'updateAdminProduct']);
        Route::patch('/products/{product}/availability', [ApiController::class, 'toggleAdminProductAvailability']);
        Route::delete('/products/{product}', [ApiController::class, 'deleteAdminProduct']);

        Route::post('/categories', [ApiController::class, 'storeAdminCategory']);
        Route::put('/categories/{category}', [ApiController::class, 'updateAdminCategory']);
        Route::delete('/categories/{category}', [ApiController::class, 'deleteAdminCategory']);

        Route::get('/staff', [StaffController::class, 'index']);
        Route::post('/staff', [StaffController::class, 'store']);
        Route::put('/staff/{user}', [StaffController::class, 'update']);
        Route::delete('/staff/{user}', [StaffController::class, 'destroy']);
    });

    Route::get('/occasions', [ApiController::class, 'getOccasions']);
    Route::post('/occasions', [ApiController::class, 'createOccasion']);
    Route::put('/occasions/{occasion}', [ApiController::class, 'updateOccasion']);
    Route::delete('/occasions/{occasion}', [ApiController::class, 'deleteOccasion']);
});
