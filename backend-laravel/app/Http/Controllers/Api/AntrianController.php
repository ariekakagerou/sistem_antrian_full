<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Antrian;
use App\Models\Loket;
use App\Models\User;
use App\Services\NomorAntrianService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AntrianController extends Controller
{
    /**
     * Menampilkan semua antrian
     * GET /api/antrian
     */
    public function index()
    {
        $antrians = Antrian::with(['loket', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $antrians
        ]);
    }

    /**
     * Mengambil nomor antrian baru
     * Endpoint: POST /api/antrian
     * 
     * Proses:
     * 1. Validasi input (loket_id, nama_pasien, keluhan)
     * 2. Generate nomor antrian otomatis
     * 3. Simpan data antrian ke database
     * 4. Hitung estimasi waktu tunggu
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validasi input dari request
        // loket_id: harus ada, berupa angka, dan loket harus terdaftar di database
        // nama_pasien: harus ada, maksimal 255 karakter
        // keluhan: harus ada, maksimal 500 karakter
        $validator = Validator::make($request->all(), [
            'loket_id' => 'required|integer|exists:lokets,id',
            'nama_pasien' => 'required|string|max:255',
            'keluhan' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Konversi loket_id ke integer dan cari data loket
            $loket_id = (int)$request->loket_id;
            $loket = Loket::findOrFail($loket_id);
            
            // Generate nomor antrian otomatis (contoh: A001, B002, dst)
            $nomorAntrian = NomorAntrianService::generateNomorAntrian($loket_id);

            // Simpan data antrian baru ke database
            $antrian = Antrian::create([
                'loket_id' => $loket_id,
                'nomor_antrian' => $nomorAntrian,
                'nama_pasien' => $request->nama_pasien,
                'keluhan' => $request->keluhan,
                'status' => 'menunggu',
                'tanggal' => now()->toDateString(),
                'nomor_hp' => null,  // Opsional, diisi null jika tidak ada
                'alamat' => null     // Opsional, diisi null jika tidak ada
            ]);

            // Hitung estimasi waktu tunggu berdasarkan jumlah antrian yang menunggu
            $jumlahAntrianMenunggu = Antrian::where('loket_id', $loket->id)
                ->where('status', 'menunggu')
                ->count();
            
            // Asumsi: setiap antrian membutuhkan waktu 10 menit
            $estimasiMenit = ($jumlahAntrianMenunggu + 1) * 10;

            return response()->json([
                'success' => true,
                'message' => 'Nomor antrian berhasil diambil',
                'data' => [
                    'id' => $antrian->id,
                    'nomor_antrian' => $antrian->nomor_antrian,
                    'nama_pasien' => $antrian->nama_pasien,
                    'loket' => $loket->nama_loket,
                    'status' => $antrian->status,
                    'estimasi_waktu_tunggu' => $estimasiMenit . ' menit',
                    'perkiraan_dipanggil' => now()->addMinutes($estimasiMenit)->format('H:i') . ' WIB',
                    'waktu_pendaftaran' => $antrian->created_at->format('Y-m-d H:i:s'),
                    'cetak_url' => url("/api/antrian/cetak/" . $antrian->id)
                ]
            ], 201);
            
        } catch (\Exception $e) {
            \Log::error('Error saat mengambil antrian: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil nomor antrian',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update status antrian
     * Endpoint: PUT /api/antrian/{id}/status
     * 
     * Status yang dapat diubah:
     * - menunggu: Antrian kembali ke status menunggu
     * - dipanggil: Antrian sedang dipanggil
     * - selesai: Antrian telah selesai dilayani
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id ID antrian
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, $id)
    {
        $antrian = Antrian::find($id);
        if (!$antrian) {
            return response()->json([
                'success' => false,
                'message' => 'Antrian tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'status' => 'required|in:menunggu,dipanggil,selesai'
        ]);

        // Update status antrian sesuai request
        // Gunakan method khusus untuk status 'dipanggil' dan 'selesai'
        if ($validated['status'] === 'dipanggil') {
            $antrian->panggil();  // Method panggil() akan set waktu_panggil
        } elseif ($validated['status'] === 'selesai') {
            $antrian->selesai();  // Method selesai() akan validasi status
        } else {
            $antrian->update(['status' => 'menunggu']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status antrian diperbarui',
            'data' => $antrian
        ]);
    }

    /**
     * Panggil antrian (ubah status menjadi 'dipanggil')
     * Endpoint: POST /api/antrian/{id}/panggil
     * 
     * Hanya user dengan role 'petugas' atau 'admin' yang dapat memanggil antrian
     * Antrian harus berstatus 'menunggu' untuk dapat dipanggil
     * 
     * @param  int  $id ID antrian
     * @return \Illuminate\Http\JsonResponse
     */
    public function panggil($id)
    {
        try {
            // Log request untuk debugging
            \Log::info('Request memanggil antrian', [
                'antrian_id' => $id,
                'user' => auth()->user() ? auth()->user()->only(['id', 'name', 'email', 'role']) : null,
                'request' => request()->all(),
                'headers' => request()->headers->all()
            ]);

            // Ambil data antrian beserta relasi loket
            $antrian = Antrian::with('loket')->find($id);
            
            // Validasi: Cek apakah antrian ditemukan
            if (!$antrian) {
                \Log::warning('Antrian tidak ditemukan', ['antrian_id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Antrian tidak ditemukan',
                    'debug' => ['antrian_id' => $id]
                ], 404);
            }

            // Validasi: Cek apakah user memiliki izin (role petugas atau admin)
            if (auth()->user()->role !== 'petugas' && auth()->user()->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk memanggil antrian',
                    'required_role' => 'petugas atau admin',
                    'your_role' => auth()->user()->role
                ], 403);
            }

            // Panggil antrian (ubah status menjadi 'dipanggil' dan set waktu_panggil)
            $antrian->panggil();

            // Refresh model untuk mendapatkan data terbaru dari database
            $antrian->refresh();

            // Log keberhasilan
            \Log::info('Antrian berhasil dipanggil', [
                'antrian_id' => $antrian->id,
                'status' => $antrian->status,
                'waktu_panggil' => $antrian->waktu_panggil
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Antrian berhasil dipanggil',
                'data' => $antrian
            ]);

        } catch (\Exception $e) {
            // Log the full error with stack trace
            \Log::error('Error in panggil() method: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : 'Disabled in production',
                'antrian_id' => $id,
                'user' => auth()->user() ? auth()->user()->only(['id', 'name', 'email', 'role']) : null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses permintaan',
                'error' => $e->getMessage(),
                'debug' => config('app.debug') ? [
                    'class' => get_class($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ] : null
            ], 500);
        }
    }

    /**
     * Tandai antrian selesai (ubah status menjadi 'selesai')
     * Endpoint: POST /api/antrian/{id}/selesai
     * 
     * Hanya user dengan role 'petugas' atau 'admin' yang dapat menyelesaikan antrian
     * Antrian harus berstatus 'menunggu' atau 'dipanggil' untuk dapat diselesaikan
     * 
     * @param  int  $id ID antrian
     * @return \Illuminate\Http\JsonResponse
     */
    public function selesai($id)
    {
        try {
            // Ambil data antrian beserta relasi loket
            $antrian = Antrian::with('loket')->find($id);
            
            // Validasi: Cek apakah antrian ditemukan
            if (!$antrian) {
                \Log::warning('Antrian tidak ditemukan', ['antrian_id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Antrian tidak ditemukan',
                    'debug' => ['antrian_id' => $id]
                ], 404);
            }

            // Validasi: Cek apakah user memiliki izin (role petugas atau admin)
            if (auth()->user()->role !== 'petugas' && auth()->user()->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk menyelesaikan antrian',
                    'required_role' => 'petugas atau admin',
                    'your_role' => auth()->user()->role
                ], 403);
            }

            // Tandai antrian selesai (ubah status menjadi 'selesai')
            $antrian->selesai();

            // Refresh model untuk mendapatkan data terbaru dari database
            $antrian->refresh();

            // Log keberhasilan
            \Log::info('Antrian berhasil diselesaikan', [
                'antrian_id' => $antrian->id,
                'status' => $antrian->status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Antrian berhasil diselesaikan',
                'data' => $antrian
            ]);

        } catch (\Exception $e) {
            // Log error lengkap dengan stack trace untuk debugging
            \Log::error('Error saat menyelesaikan antrian: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : 'Disabled in production',
                'antrian_id' => $id,
                'user' => auth()->user() ? auth()->user()->only(['id', 'name', 'email', 'role']) : null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses permintaan',
                'error' => $e->getMessage(),
                'debug' => config('app.debug') ? [
                    'class' => get_class($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ] : null
            ], 500);
        }
    }

    /**
     * Menampilkan daftar antrian yang sedang dipanggil dari semua loket
     * Endpoint: GET /api/antrian/dipanggil
     * 
     * Digunakan untuk menampilkan antrian di layar display
     * Diurutkan berdasarkan waktu panggil terbaru
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function sedangDipanggil()
    {
        $data = Antrian::with('loket')
            ->where('status', 'dipanggil')
            ->orderBy('waktu_panggil', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Menampilkan daftar antrian yang menunggu di loket tertentu
     * Endpoint: GET /api/antrian/menunggu/{loket_id}
     * 
     * Digunakan untuk menampilkan antrian yang menunggu di loket tertentu
     * Diurutkan berdasarkan waktu pendaftaran (FIFO - First In First Out)
     * 
     * @param  int  $loket_id ID loket
     * @return \Illuminate\Http\JsonResponse
     */
    public function menungguPerLoket($loket_id)
    {
        $data = Antrian::where('loket_id', $loket_id)
            ->where('status', 'menunggu')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
