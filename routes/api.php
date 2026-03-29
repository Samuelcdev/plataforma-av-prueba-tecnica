<?php

declare(strict_types=1);

use App\Presentation\Http\Controllers\AuthController;
use App\Presentation\Http\Controllers\HotelController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1')->group(function (): void {
    // Auth routes
    Route::prefix('auth')->group(function (): void {
        Route::post('login', [AuthController::class, 'login'])->name('api.v1.auth.login');

        Route::middleware('auth:sanctum')->group(function (): void {
            Route::post('logout', [AuthController::class, 'logout'])->name('api.v1.auth.logout');
            Route::get('me', [AuthController::class, 'me'])->name('api.v1.auth.me');
        });
    });

    Route::middleware('auth:sanctum')->prefix('hotels')->group(function (): void {
        Route::get('/', [HotelController::class, 'index'])->name('api.v1.hotels.index');
        Route::get('{id}', [HotelController::class, 'show'])->name('api.v1.hotels.show');
        Route::post('/', [HotelController::class, 'store'])->name('api.v1.hotels.store');
        Route::put('{id}', [HotelController::class, 'update'])->name('api.v1.hotels.update');
        Route::patch('{id}', [HotelController::class, 'update'])->name('api.v1.hotels.patch');
        Route::delete('{id}', [HotelController::class, 'destroy'])->name('api.v1.hotels.destroy');
    });
});
