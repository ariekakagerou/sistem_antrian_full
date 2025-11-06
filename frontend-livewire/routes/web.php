<?php

use App\Livewire\PasienAntrian;
use App\Livewire\PetugasLoket;
use App\Livewire\DisplayAntrian;
use App\Livewire\GoogleCallback;

// Halaman Utama
Route::get('/', PasienAntrian::class);
Route::get('/display', DisplayAntrian::class);
Route::get('/auth/google/callback', GoogleCallback::class);

// Petugas Routes - Main
Route::get('/petugas', PetugasLoket::class)->name('petugas.index');

// Petugas Routes - Menu (dengan parameter loket_id)
Route::get('/petugas/dashboard/{loket_id}', PetugasLoket::class)->name('petugas.dashboard');
Route::get('/petugas/daftar-antrian/{loket_id}', PetugasLoket::class)->name('petugas.daftar-antrian');
Route::get('/petugas/pemanggilan/{loket_id}', PetugasLoket::class)->name('petugas.pemanggilan');
Route::get('/petugas/riwayat/{loket_id}', PetugasLoket::class)->name('petugas.riwayat');
Route::get('/petugas/pengaturan/{loket_id}', PetugasLoket::class)->name('petugas.pengaturan');
