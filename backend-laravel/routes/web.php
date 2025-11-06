<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Routes for Google OAuth
Route::prefix('auth/google')->group(function () {
    Route::get('/', [\App\Http\Controllers\Auth\GoogleController::class, 'redirectToGoogle']);
    Route::get('/callback', [\App\Http\Controllers\Auth\GoogleController::class, 'handleGoogleCallback']);
});
