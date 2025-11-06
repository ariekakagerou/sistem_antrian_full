<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoketController;
use App\Http\Controllers\Api\AntrianController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public Loket Routes (tidak perlu login)
Route::prefix('loket')->group(function () {
    Route::get('/', [LoketController::class, 'index']);
    Route::get('/{id}', [LoketController::class, 'show']);
});

// Protected Loket Routes (perlu login)
Route::middleware('auth:sanctum')->prefix('loket')->group(function () {
    Route::post('/', [LoketController::class, 'store']);
    Route::put('/{id}', [LoketController::class, 'update']);
    Route::delete('/{id}', [LoketController::class, 'destroy']);
});

// Public Antrian Routes (tidak perlu login)
Route::prefix('antrian')->group(function () {
    Route::get('/', [AntrianController::class, 'index']);
    Route::get('/{id}', [AntrianController::class, 'show']);
    Route::get('/cetak/{id}', [\App\Http\Controllers\Api\CetakAntrianController::class, 'cetak']);
    Route::get('/loket/{loketId}', [AntrianController::class, 'getByLoket']);
    
    // Route untuk membuat antrian baru (perlu login)
    Route::middleware('auth:sanctum')->post('/', [AntrianController::class, 'store']);
});

// Protected Routes (hanya untuk petugas)
Route::middleware(['auth:sanctum', 'role.petugas'])->prefix('antrian')->group(function () {
    Route::put('/{id}', [AntrianController::class, 'update']);
    Route::delete('/{id}', [AntrianController::class, 'destroy']);
    Route::post('/{id}/panggil', [AntrianController::class, 'panggil']);
    Route::post('/{id}/selesai', [AntrianController::class, 'selesai']);
});

// Test route to check middleware and authentication
Route::get('/test-auth', function (Request $request) {
    return response()->json([
        'authenticated' => auth()->check(),
        'user' => auth()->user(),
        'role' => auth()->check() ? auth()->user()->role : null,
        'is_petugas' => auth()->check() && auth()->user()->role === 'petugas',
        'headers' => $request->headers->all()
    ]);
})->middleware('auth:sanctum');

// Public auth routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Get current user
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);
});
