<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Antrian extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database
     */
    protected $table = 'antrians';

    /**
     * Kolom yang dapat diisi secara massal (mass assignment)
     * Kolom-kolom ini dapat diisi melalui method create() atau update()
     */
    protected $fillable = [
        'loket_id',
        'nomor_antrian',
        'nama_pasien',
        'nomor_hp',
        'alamat',
        'keluhan',
        'status',
        'tanggal',
        'waktu_panggil',
    ];

    /**
     * Kolom yang dilindungi dari mass assignment
     * Kolom-kolom ini tidak dapat diisi secara massal untuk keamanan
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];
    
    /**
     * Konversi tipe data kolom
     * Memastikan data yang diambil dari database memiliki tipe yang sesuai
     */
    protected $casts = [
        'loket_id' => 'integer',
        'waktu_panggil' => 'datetime',
        'tanggal' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Nilai default untuk kolom-kolom tertentu
     * Nilai ini akan digunakan saat membuat instance model baru
     */
    protected $attributes = [
        'status' => 'menunggu',
        'tanggal' => null,
    ];

    /**
     * Method boot untuk menangani event model
     * Dijalankan saat model pertama kali dimuat
     */
    protected static function boot()
    {
        parent::boot();
        
        // Event: Sebelum antrian baru disimpan ke database
        // Otomatis mengisi kolom tanggal dengan tanggal hari ini
        static::creating(function ($antrian) {
            $antrian->tanggal = now()->toDateString();
        });
    }
    
    /**
     * Mengubah status antrian menjadi 'dipanggil'
     * Method ini hanya dapat dijalankan jika status antrian saat ini adalah 'menunggu'
     * 
     * @return void
     * @throws \Exception Jika status antrian bukan 'menunggu'
     */
    public function panggil()
    {
        // Validasi: Hanya antrian dengan status 'menunggu' yang dapat dipanggil
        if ($this->status !== 'menunggu') {
            throw new \Exception('Hanya antrian dengan status menunggu yang dapat dipanggil');
        }
        
        // Update status menjadi 'dipanggil' dan catat waktu pemanggilan
        $this->update([
            'status' => 'dipanggil',
            'waktu_panggil' => now(),
        ]);
    }

    /**
     * Mengubah status antrian menjadi 'selesai'
     * Method ini hanya dapat dijalankan jika status antrian adalah 'menunggu' atau 'dipanggil'
     * 
     * @return void
     * @throws \Exception Jika status antrian tidak valid
     */
    public function selesai(): void
    {
        // Validasi: Hanya antrian dengan status 'menunggu' atau 'dipanggil' yang dapat diselesaikan
        if (!in_array($this->status, ['menunggu', 'dipanggil'])) {
            throw new \Exception('Antrian harus dalam status menunggu atau dipanggil untuk diselesaikan');
        }
        
        // Update status menjadi 'selesai'
        $this->update(['status' => 'selesai']);
    }

    /**
     * Relasi ke model Loket (Many to One)
     * Setiap antrian terhubung ke satu loket
     * 
     * @return BelongsTo
     */
    public function loket(): BelongsTo
    {
        return $this->belongsTo(Loket::class);
    }

    /**
     * Query scope untuk mengambil antrian yang masih aktif
     * Antrian aktif adalah antrian dengan status 'menunggu' atau 'dipanggil'
     * 
     * Contoh penggunaan: Antrian::antrianAktif()->get()
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAntrianAktif($query)
    {
        return $query->whereIn('status', ['menunggu', 'dipanggil']);
    }

    /**
     * Query scope untuk mengambil antrian hari ini
     * Filter berdasarkan tanggal pembuatan antrian (created_at)
     * 
     * Contoh penggunaan: Antrian::hariIni()->get()
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHariIni($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Query scope untuk memfilter antrian berdasarkan status tertentu
     * 
     * Contoh penggunaan: Antrian::status('menunggu')->get()
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status Status yang ingin difilter (menunggu/dipanggil/selesai)
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}