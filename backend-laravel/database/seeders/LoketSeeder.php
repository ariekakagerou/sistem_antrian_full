<?php

namespace Database\Seeders;

use App\Models\Loket;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LoketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama jika ada
        DB::table('lokets')->truncate();
        
        $lokets = [
            // Loket Pendaftaran
            [
                'nama_loket' => 'Loket Pendaftaran 1',
                'deskripsi' => 'Pendaftaran Pasien Umum',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_loket' => 'Loket Pendaftaran 2',
                'deskripsi' => 'Pendaftaran Pasien BPJS',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Poli Klinik
            [
                'nama_loket' => 'Poli Umum',
                'deskripsi' => 'Poli Kesehatan Umum',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_loket' => 'Poli Gigi',
                'deskripsi' => 'Poli Kesehatan Gigi dan Mulut',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_loket' => 'Poli Anak',
                'deskripsi' => 'Poli Kesehatan Anak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_loket' => 'Poli Kandungan',
                'deskripsi' => 'Poli Kesehatan Ibu dan Anak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_loket' => 'Loket 3',
                'deskripsi' => 'Pembayaran',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_loket' => 'Loket 4',
                'deskripsi' => 'Farmasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_loket' => 'Loket 5',
                'deskripsi' => 'IGD (Instalasi Gawat Darurat)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_loket' => 'Loket 6',
                'deskripsi' => 'Laboratorium',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_loket' => 'Loket 7',
                'deskripsi' => 'Radiologi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_loket' => 'Loket 8',
                'deskripsi' => 'Rawat Jalan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_loket' => 'Loket 9',
                'deskripsi' => 'Rawat Inap',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_loket' => 'Loket 10',
                'deskripsi' => 'Informasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Loket Poli
            [
                'nama_loket' => 'Poli Umum',
                'deskripsi' => 'Poli Kesehatan Umum',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_loket' => 'Poli Anak',
                'deskripsi' => 'Poli Kesehatan Anak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_loket' => 'Poli Gigi',
                'deskripsi' => 'Poli Kesehatan Gigi dan Mulut',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_loket' => 'Poli Kandungan',
                'deskripsi' => 'Poli Kandungan dan Kebidanan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_loket' => 'Poli Penyakit Dalam',
                'deskripsi' => 'Poli Penyakit Dalam',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_loket' => 'Poli Bedah',
                'deskripsi' => 'Poli Bedah Umum',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_loket' => 'Poli Jantung',
                'deskripsi' => 'Poli Jantung dan Pembuluh Darah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_loket' => 'Poli Saraf',
                'deskripsi' => 'Poli Saraf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_loket' => 'Poli THT',
                'deskripsi' => 'Poli Telinga Hidung Tenggorokan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_loket' => 'Poli Mata',
                'deskripsi' => 'Poli Mata',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_loket' => 'Poli Kulit & Kelamin',
                'deskripsi' => 'Poli Kulit dan Kelamin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_loket' => 'Poli Paru',
                'deskripsi' => 'Poli Paru-Paru',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_loket' => 'Poli Gizi',
                'deskripsi' => 'Poli Gizi dan Klinik',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_loket' => 'Poli Jiwa',
                'deskripsi' => 'Poli Kesehatan Jiwa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_loket' => 'Poli Rehab Medik',
                'deskripsi' => 'Poli Rehabilitasi Medik',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        // Hapus data yang sudah ada (jika ada)
        Loket::truncate();

        // Masukkan data ke database
        foreach ($lokets as $loket) {
            Loket::create($loket);
        }

        $this->command->info('Data loket berhasil ditambahkan!');
    }
}
