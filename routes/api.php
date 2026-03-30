<?php

declare(strict_types=1);

use App\Presentation\Http\Controllers\AuthController;
use App\Presentation\Http\Controllers\HotelController;
use App\Presentation\Http\Controllers\ItemController;
use App\Presentation\Http\Controllers\OperativeController;
use App\Presentation\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1')->group(function (): void {
    // Auth routes
    Route::prefix('auth')->group(function (): void {
        Route::post('login', [AuthController::class, 'login'])->name('api.v1.auth.login');

        Route::middleware('auth')->group(function (): void {
            Route::post('logout', [AuthController::class, 'logout'])->name('api.v1.auth.logout');
            Route::get('me', [AuthController::class, 'me'])->name('api.v1.auth.me');
        });
    });

    Route::middleware('auth')->prefix('hotels')->group(function (): void {
        Route::get('/', [HotelController::class, 'index'])->name('api.v1.hotels.index');
        Route::get('{id}', [HotelController::class, 'show'])->name('api.v1.hotels.show');
        Route::post('/', [HotelController::class, 'store'])->name('api.v1.hotels.store');
        Route::put('{id}', [HotelController::class, 'update'])->name('api.v1.hotels.update');
        Route::patch('{id}', [HotelController::class, 'update'])->name('api.v1.hotels.patch');
        Route::delete('{id}', [HotelController::class, 'destroy'])->name('api.v1.hotels.destroy');
    });

    Route::middleware('auth')->prefix('orders')->group(function (): void {
        Route::get('/', [OrderController::class, 'index'])->name('api.v1.orders.index');
        Route::get('{id}', [OrderController::class, 'show'])->name('api.v1.orders.show');
        Route::post('/', [OrderController::class, 'store'])->name('api.v1.orders.store');
        Route::put('{id}', [OrderController::class, 'update'])->name('api.v1.orders.update');
        Route::patch('{id}', [OrderController::class, 'update'])->name('api.v1.orders.patch');
        Route::post('{id}/assign-operatives', [OrderController::class, 'assignOperatives'])->name('api.v1.orders.assign-operatives');
        Route::post('{id}/cancel', [OrderController::class, 'cancel'])->name('api.v1.orders.cancel');
    });

    Route::middleware('auth')->prefix('items')->group(function (): void {
        Route::get('/', [ItemController::class, 'get'])->name('api.v1.items.get');
    });

    Route::middleware('auth')->prefix('operatives')->group(function (): void {
        Route::get('/', [OperativeController::class, 'get'])->name('api.v1.operatives.get');
    });
});
