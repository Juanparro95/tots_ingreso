<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\SpaceController;
use Illuminate\Support\Facades\Route;

// IMPORTANT:
// The routes in routes/api.php are automatically prefixed with '/api'
// by Laravel's RouteServiceProvider. Do NOT add an extra 'api' prefix here,
// otherwise endpoints become '/api/api/...'.

// Public routes (prefix '/api' applied implicitly)
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/spaces', [SpaceController::class, 'index']);
Route::get('/spaces/{space}', [SpaceController::class, 'show']);
Route::get('/reservations/available-slots', [ReservationController::class, 'getAvailableSlots']);

// Protected routes
Route::middleware('auth:api')->group(function () {
    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/refresh', [AuthController::class, 'refresh']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    // Spaces
    Route::post('/spaces', [SpaceController::class, 'store']);
    Route::put('/spaces/{space}', [SpaceController::class, 'update']);
    Route::delete('/spaces/{space}', [SpaceController::class, 'destroy']);

    // Reservations
    Route::get('/my-reservations', [ReservationController::class, 'index']);
    Route::get('/reservations', [ReservationController::class, 'index']);
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show']);
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::put('/reservations/{reservation}', [ReservationController::class, 'update']);
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy']);
});
