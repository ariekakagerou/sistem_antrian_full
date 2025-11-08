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
    Route::post('/', [AntrianController::class, 'store']); // Buat antrian tanpa login
    Route::get('/cetak/{id}', [\App\Http\Controllers\Api\CetakAntrianController::class, 'cetak']);
    Route::get('/loket/{loketId}', [AntrianController::class, 'getByLoket']);
    Route::get('/dipanggil', [AntrianController::class, 'sedangDipanggil']); // Display antrian yang dipanggil
    Route::get('/menunggu/{loket_id}', [AntrianController::class, 'menungguPerLoket']); // Antrian menunggu per loket
    Route::get('/riwayat', [AntrianController::class, 'riwayat']); // Riwayat antrian selesai
    Route::get('/{id}', [AntrianController::class, 'show']); // PINDAH KE BAWAH
    Route::post('/{id}/panggil', [AntrianController::class, 'panggil']); // Panggil tanpa auth
    Route::post('/{id}/selesai', [AntrianController::class, 'selesai']); // Selesai tanpa auth
});

// Protected Routes (hanya untuk petugas)
Route::middleware(['auth:sanctum', 'role.petugas'])->prefix('antrian')->group(function () {
    Route::put('/{id}', [AntrianController::class, 'update']);
    Route::delete('/{id}', [AntrianController::class, 'destroy']);
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
