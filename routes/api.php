<?php

use App\Http\Controllers\Api\MobileAuthController;
use App\Http\Controllers\Api\WarehouseController;
use App\Http\Controllers\Api\CustomerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    // Public Routes
    Route::post('/login', [MobileAuthController::class, 'login']);

    // Protected Routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [MobileAuthController::class, 'me']);
        Route::post('/logout', [MobileAuthController::class, 'logout']);

        // Operator/Staff: Warehouse Operations
        Route::prefix('warehouse')->group(function () {
            Route::get('/list', [WarehouseController::class, 'warehouses']);
            Route::post('/scan', [WarehouseController::class, 'scan']);
            Route::post('/bulk-receive', [WarehouseController::class, 'bulkReceive']);
        });

        // Customer Operations
        Route::prefix('customer')->group(function () {
            Route::get('/profile', [CustomerController::class, 'profile']);
            Route::get('/packages', [CustomerController::class, 'packages']);
            Route::get('/packages/{id}', [CustomerController::class, 'packageDetail']);
            Route::get('/invoices', [CustomerController::class, 'invoices']);
            Route::get('/balance', [CustomerController::class, 'balance']);

            // New: Assisted Purchases
            Route::get('/assisted-purchases', [CustomerController::class, 'assistedPurchases']);
            Route::post('/assisted-purchases', [CustomerController::class, 'storeAssistedPurchase']);
        });
    });
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
