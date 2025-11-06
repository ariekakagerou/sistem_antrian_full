<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loket extends Model
{
    use HasFactory;

    // Nama tabel (opsional, karena Laravel otomatis plural)
    protected $table = 'lokets';

    // Kolom yang bisa diisi mass-assignment
    protected $fillable = [
        'nama_loket',
        'deskripsi',
    ];

    /**
     * Relasi: 1 Loket punya banyak Antrian
     */
    public function antrians()
    {
        return $this->hasMany(Antrian::class, 'loket_id');
    }

    /**
     * Membuat format kode antrian baru berdasarkan urutan terakhir
     * contoh: A001, A002, dst.
     */
    public function generateNomorAntrian()
    {
        // Ambil antrian terakhir berdasarkan loket ini
        $lastAntrian = $this->antrians()->latest('id')->first();

        // Ambil nomor urut terakhir (misal dari A001 jadi 1)
        $lastNumber = $lastAntrian
            ? intval(substr($lastAntrian->nomor_antrian, 1))
            : 0;

        // Tambah 1 nomor
        $nextNumber = $lastNumber + 1;

        // Ambil huruf awal berdasarkan urutan loket (opsional)
        $prefix = strtoupper(substr($this->nama_loket, 0, 1));

        // Bentuk hasil akhir misal "P003"
        return $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Mendapatkan antrian aktif untuk loket ini
     */
    public function getAntrianAktif()
    {
        return $this->antrians()
            ->where('status', 'dipanggil')
            ->whereDate('waktu_panggil', now()->toDateString())
            ->orderBy('waktu_panggil', 'desc')
            ->first();
    }
}
