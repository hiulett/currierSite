<?php

use App\Http\Controllers\Api\MobileAuthController;
use App\Http\Controllers\Api\WarehouseController;
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

        // Warehouse Operations
        Route::prefix('warehouse')->group(function () {
            Route::post('/scan', [WarehouseController::class, 'scan']);
            Route::post('/receive', [WarehouseController::class, 'receive']);
        });
    });
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
