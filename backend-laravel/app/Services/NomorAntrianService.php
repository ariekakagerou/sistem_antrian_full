<?php

namespace App\Services;

use App\Models\Antrian;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Service untuk generate nomor antrian otomatis
 * 
 * Nomor antrian terdiri dari:
 * - Prefix huruf (A, B, C, dst) sesuai dengan loket_id
 * - Nomor urut 3 digit (001, 002, 003, dst)
 * 
 * Contoh: A001, A002, B001, B002, dst
 * Nomor urut akan direset setiap hari
 */
class NomorAntrianService
{
    /**
     * Generate nomor antrian baru untuk loket tertentu
     * 
     * Logika:
     * 1. Cek antrian terakhir hari ini di loket yang sama
     * 2. Jika belum ada, mulai dari nomor 1
     * 3. Jika sudah ada, ambil nomor terakhir dan tambah 1
     * 4. Format: [Huruf Loket][3 digit angka]
     * 
     * @param int $loketId ID loket (1 = A, 2 = B, dst)
     * @return string Nomor antrian (contoh: A001, B002)
     */
    public static function generateNomorAntrian($loketId)
    {
        // Ambil tanggal hari ini dalam format Y-m-d
        $today = now()->format('Y-m-d');
        
        // Cari antrian terakhir yang dibuat hari ini di loket yang sama
        // Diurutkan berdasarkan ID terbesar (antrian paling baru)
        $lastAntrian = Antrian::whereDate('created_at', $today)
            ->where('loket_id', $loketId)
            ->orderBy('id', 'desc')
            ->first();

        // Tentukan nomor urut berikutnya
        if (!$lastAntrian) {
            // Jika belum ada antrian hari ini, mulai dari nomor 1
            $nomorUrut = 1;
        } else {
            // Jika sudah ada, ambil nomor urut dari nomor antrian terakhir
            // Contoh: dari "A005" ambil "005", konversi ke integer (5), lalu tambah 1
            $nomorUrut = (int)substr($lastAntrian->nomor_antrian, 1) + 1;
        }

        // Generate prefix huruf berdasarkan loket_id
        // loket_id 1 = A (ASCII 65), loket_id 2 = B (ASCII 66), dst
        $prefix = chr(64 + $loketId);
        
        // Format nomor urut menjadi 3 digit dengan padding 0 di depan
        // Contoh: 1 -> "001", 25 -> "025", 100 -> "100"
        $nomorUrutFormatted = str_pad($nomorUrut, 3, '0', STR_PAD_LEFT);
        
        // Gabungkan prefix dan nomor urut
        // Contoh: A + 001 = A001
        return $prefix . $nomorUrutFormatted;
    }
}
