<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PetugasSeeder extends Seeder
{
    public function run()
    {
        // Cek apakah user sudah ada
        if (!User::where('email', 'petugas@example.com')->exists()) {
            User::create([
                'name' => 'Petugas 1',
                'email' => 'petugas@example.com',
                'password' => Hash::make('password'),
                'role' => 'petugas',
                'whatsapp' => '6281234567890' // Nomor WhatsApp opsional
            ]);
        }

        // Buat juga akun admin
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'whatsapp' => '6281234567891' // Nomor WhatsApp opsional
            ]);
        }
    }
}
