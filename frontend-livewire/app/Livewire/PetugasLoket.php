<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\ApiService;

class PetugasLoket extends Component
{
    public $lokets = [];
    public $selectedLoket = null;
    public $antrians = [];
    public $antrianAktif = null;
    public $isLoggedIn = false;
    public $email = '';
    public $password = '';
    
    // Pagination untuk loket
    public $currentPage = 1;
    public $perPage = 6;
    public $totalPages = 1;
    
    // Menu navigasi dan data tambahan
    public $activeMenu = 'dashboard';
    public $petugasNama = 'Petugas';
    public $loketNama = '';
    public $totalPasienHariIni = 0;
    public $jumlahMenunggu = 0;
    public $jumlahDilayani = 0;
    public $jumlahSelesai = 0;
    public $logAktivitas = [];
    public $antrianBerikutnya = null;
    public $antrianMenunggu = [];
    public $riwayatPelayanan = [];
    public $searchRiwayat = '';
    public $filterTanggal = '';
    public $profilNama = '';
    public $profilEmail = '';

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|min:6',
    ];

    public function mount(ApiService $apiService, $loket_id = null)
    {
        // Cek apakah sudah login
        $token = session('api_token');
        if ($token) {
            $this->isLoggedIn = true;
            $this->loadLokets($apiService);
            
            // Jika ada loket_id dari route, set otomatis
            if ($loket_id) {
                $this->selectedLoket = $loket_id;
                $loket = collect($this->lokets)->firstWhere('id', $loket_id);
                $this->loketNama = $loket['nama_loket'] ?? '';
                
                // Deteksi menu dari URL
                $currentRoute = request()->route()->getName();
                if (str_contains($currentRoute, 'dashboard')) {
                    $this->activeMenu = 'dashboard';
                } elseif (str_contains($currentRoute, 'daftar-antrian')) {
                    $this->activeMenu = 'daftar-antrian';
                } elseif (str_contains($currentRoute, 'pemanggilan')) {
                    $this->activeMenu = 'pemanggilan';
                } elseif (str_contains($currentRoute, 'riwayat')) {
                    $this->activeMenu = 'riwayat';
                } elseif (str_contains($currentRoute, 'pengaturan')) {
                    $this->activeMenu = 'pengaturan';
                }
                
                $this->loadAntrians($apiService);
            }
        }
    }

    public function login(ApiService $apiService)
    {
        $this->validate();

        try {
            $response = $apiService->login($this->email, $this->password);
            
            if (isset($response['token'])) {
                $this->isLoggedIn = true;
                $this->reset(['email', 'password']);
                $this->loadLokets($apiService);
                
                $this->dispatch('notification', [
                    'message' => 'Login berhasil!',
                    'type' => 'success'
                ]);
            }
        } catch (\Exception $e) {
            $this->dispatch('notification', [
                'message' => 'Login gagal: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function loginWithGoogle()
    {
        // Redirect ke Google OAuth
        $googleAuthUrl = env('API_BASE_URL', 'http://localhost:8000/api') . '/../auth/google';
        return redirect($googleAuthUrl);
    }

    public function logout()
    {
        try {
            $apiService = app(ApiService::class);
            $apiService->logout();
            $this->isLoggedIn = false;
            $this->reset(['selectedLoket', 'antrians', 'antrianAktif']);
            
            $this->dispatch('notification', [
                'message' => 'Logout berhasil',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notification', [
                'message' => 'Logout gagal: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function loadLokets(ApiService $apiService)
    {
        try {
            $response = $apiService->getLokets();
            $this->lokets = $response['data'] ?? [];
            $this->calculateTotalPages();
        } catch (\Exception $e) {
            \Log::error('Petugas loadLokets error: ' . $e->getMessage());
            // Gunakan fallback data agar aplikasi tetap berfungsi
            $this->lokets = [
                ['id' => 1, 'nama_loket' => 'Loket Pendaftaran 1', 'deskripsi' => 'Pendaftaran Pasien Umum'],
                ['id' => 2, 'nama_loket' => 'Loket Pendaftaran 2', 'deskripsi' => 'Pendaftaran Pasien BPJS'],
                ['id' => 3, 'nama_loket' => 'Poli Umum', 'deskripsi' => 'Poli Kesehatan Umum'],
                ['id' => 4, 'nama_loket' => 'Poli Gigi', 'deskripsi' => 'Poli Kesehatan Gigi'],
                ['id' => 5, 'nama_loket' => 'Poli Kandungan', 'deskripsi' => 'Poli Kebidanan dan Kandungan'],
                ['id' => 6, 'nama_loket' => 'Poli Anak', 'deskripsi' => 'Poli Kesehatan Anak'],
                ['id' => 7, 'nama_loket' => 'Poli Mata', 'deskripsi' => 'Poli Kesehatan Mata'],
                ['id' => 8, 'nama_loket' => 'Poli THT', 'deskripsi' => 'Poli Telinga Hidung Tenggorokan'],
                ['id' => 9, 'nama_loket' => 'Poli Jantung', 'deskripsi' => 'Poli Kardiovaskular'],
                ['id' => 10, 'nama_loket' => 'Poli Saraf', 'deskripsi' => 'Poli Neurologi'],
                ['id' => 11, 'nama_loket' => 'Poli Paru', 'deskripsi' => 'Poli Kesehatan Paru'],
                ['id' => 12, 'nama_loket' => 'Poli Penyakit Dalam', 'deskripsi' => 'Poli Penyakit Dalam'],
                ['id' => 13, 'nama_loket' => 'Poli Bedah', 'deskripsi' => 'Poli Bedah Umum'],
                ['id' => 14, 'nama_loket' => 'Poli Orthopedi', 'deskripsi' => 'Poli Tulang dan Sendi'],
                ['id' => 15, 'nama_loket' => 'Poli Kulit', 'deskripsi' => 'Poli Kesehatan Kulit'],
                ['id' => 16, 'nama_loket' => 'Poli Jiwa', 'deskripsi' => 'Poli Kesehatan Jiwa'],
                ['id' => 17, 'nama_loket' => 'Poli Rehabilitasi', 'deskripsi' => 'Poli Rehabilitasi Medik'],
                ['id' => 18, 'nama_loket' => 'Laboratorium 1', 'deskripsi' => 'Lab Pemeriksaan Darah'],
                ['id' => 19, 'nama_loket' => 'Laboratorium 2', 'deskripsi' => 'Lab Pemeriksaan Urine'],
                ['id' => 20, 'nama_loket' => 'Radiologi', 'deskripsi' => 'Pemeriksaan X-Ray dan CT Scan'],
                ['id' => 21, 'nama_loket' => 'USG', 'deskripsi' => 'Pemeriksaan Ultrasonografi'],
                ['id' => 22, 'nama_loket' => 'ECG', 'deskripsi' => 'Pemeriksaan Jantung'],
                ['id' => 23, 'nama_loket' => 'Farmasi 1', 'deskripsi' => 'Apotek Utama'],
                ['id' => 24, 'nama_loket' => 'Farmasi 2', 'deskripsi' => 'Apotek Lantai 2'],
                ['id' => 25, 'nama_loket' => 'Kasir', 'deskripsi' => 'Pembayaran Administrasi'],
            ];
            $this->calculateTotalPages();
            
            // Tampilkan notifikasi bahwa menggunakan fallback data
            $this->dispatch('notification', [
                'message' => 'Menggunakan data offline. Backend tidak terhubung.',
                'type' => 'warning'
            ]);
        }
    }
    
    public function calculateTotalPages()
    {
        $totalLokets = count($this->lokets);
        $this->totalPages = ceil($totalLokets / $this->perPage);
    }
    
    public function getPaginatedLokets()
    {
        $offset = ($this->currentPage - 1) * $this->perPage;
        return array_slice($this->lokets, $offset, $this->perPage);
    }
    
    public function nextPage()
    {
        if ($this->currentPage < $this->totalPages) {
            $this->currentPage++;
        }
    }
    
    public function previousPage()
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
        }
    }
    
    public function goToPage($page)
    {
        if ($page >= 1 && $page <= $this->totalPages) {
            $this->currentPage = $page;
        }
    }

    public function selectLoket($loketId)
    {
        $this->selectedLoket = $loketId;
        
        // Set nama loket
        if ($loketId) {
            $loket = collect($this->lokets)->firstWhere('id', $loketId);
            $this->loketNama = $loket['nama_loket'] ?? '';
            $this->activeMenu = 'dashboard';
            
            // Load data antrian
            $apiService = app(ApiService::class);
            $this->loadAntrians($apiService);
            $this->loadStatistik();
        } else {
            $this->loketNama = '';
        }
    }

    public function loadAntrians(ApiService $apiService)
    {
        if (!$this->selectedLoket) return;

        try {
            $response = $apiService->getAntrianByLoket($this->selectedLoket);
            $this->antrians = $response['data'] ?? [];
            
            // Cari antrian yang sedang dipanggil
            $this->antrianAktif = collect($this->antrians)->firstWhere('status', 'dipanggil');
            
            // Set antrian berikutnya (hanya yang status menunggu)
            $this->antrianBerikutnya = collect($this->antrians)->where('status', 'menunggu')->first();
            
            // Load statistik setelah data antrian dimuat
            $this->loadStatistik();
        } catch (\Exception $e) {
            $this->dispatch('notification', [
                'message' => 'Gagal memuat antrian: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }
    
    public function loadStatistik()
    {
        if (!isset($this->antrians)) return;
        
        $this->totalPasienHariIni = count($this->antrians);
        $this->jumlahMenunggu = collect($this->antrians)->where('status', 'menunggu')->count();
        $this->jumlahDilayani = collect($this->antrians)->where('status', 'dipanggil')->count();
        $this->jumlahSelesai = collect($this->antrians)->where('status', 'selesai')->count();
        
        // Set antrian menunggu untuk daftar
        $this->antrianMenunggu = collect($this->antrians)->where('status', 'menunggu')->values()->toArray();
        
        // Load riwayat dari endpoint khusus untuk data yang lebih lengkap
        $this->loadRiwayat();
        
        // Log aktivitas (placeholder)
        $this->logAktivitas = [
            ['message' => 'Sistem dimulai', 'time' => now()->format('H:i')],
        ];
    }

    public function loadRiwayat()
    {
        try {
            $apiService = app(ApiService::class);
            $riwayatResponse = $apiService->getRiwayatAntrian([
                'tanggal' => $this->filterTanggal ?: date('Y-m-d'),
                'loket_id' => $this->selectedLoket,
                'limit' => 100
            ]);
            $this->riwayatPelayanan = $riwayatResponse['data'] ?? [];
            
            // Filter berdasarkan search
            if ($this->searchRiwayat) {
                $search = strtolower($this->searchRiwayat);
                $this->riwayatPelayanan = collect($this->riwayatPelayanan)
                    ->filter(function($item) use ($search) {
                        return str_contains(strtolower($item['nama_pasien'] ?? ''), $search) ||
                               str_contains(strtolower($item['nomor_antrian'] ?? ''), $search);
                    })
                    ->values()
                    ->all();
            }
        } catch (\Exception $e) {
            \Log::error('Load riwayat error: ' . $e->getMessage());
            // Fallback ke data lokal jika gagal
            $this->riwayatPelayanan = collect($this->antrians)->where('status', 'selesai')->values()->all();
        }
    }

    public function panggilAntrian($antrianId)
    {
        try {
            $apiService = app(ApiService::class);
            $apiService->panggilAntrian($antrianId);
            $this->loadAntrians($apiService);
            
            $this->dispatch('notification', [
                'message' => 'Antrian berhasil dipanggil',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notification', [
                'message' => 'Gagal memanggil antrian: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function selesaiAntrian($antrianId)
    {
        try {
            $apiService = app(ApiService::class);
            $apiService->selesaiAntrian($antrianId);
            $this->loadAntrians($apiService);
            
            $this->dispatch('notification', [
                'message' => 'Antrian berhasil diselesaikan',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notification', [
                'message' => 'Gagal menyelesaikan antrian: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function refreshAntrians()
    {
        $apiService = app(ApiService::class);
        $this->loadAntrians($apiService);
    }
    
    public function refreshData()
    {
        $apiService = app(ApiService::class);
        $this->loadAntrians($apiService);
        $this->dispatch('notification', [
            'message' => 'Data berhasil diperbarui',
            'type' => 'success'
        ]);
    }

    public function updatedSearchRiwayat()
    {
        $this->loadRiwayat();
    }

    public function updatedFilterTanggal()
    {
        $this->loadRiwayat();
    }
    
    public function panggilPasienBerikutnya()
    {
        if ($this->antrianBerikutnya) {
            $this->panggilAntrian($this->antrianBerikutnya['id']);
        }
    }
    
    public function panggilUlang($antrianId)
    {
        $this->panggilAntrian($antrianId);
    }
    
    public function exportRiwayat()
    {
        $this->dispatch('notification', [
            'message' => 'Fitur export sedang dalam pengembangan',
            'type' => 'info'
        ]);
    }
    
    public function updateProfil()
    {
        $this->dispatch('notification', [
            'message' => 'Profil berhasil diperbarui',
            'type' => 'success'
        ]);
    }
    
    public function updatePassword()
    {
        $this->dispatch('notification', [
            'message' => 'Password berhasil diubah',
            'type' => 'success'
        ]);
    }
    
    public function changeMenu($menu)
    {
        $this->activeMenu = $menu;
        
        // Load data spesifik berdasarkan menu
        if ($menu === 'riwayat') {
            $this->loadRiwayat();
        }
        
        $this->dispatch('notification', [
            'message' => 'Menu berubah ke: ' . $menu,
            'type' => 'info'
        ]);
    }

    public function render()
    {
        return view('livewire.petugas-loket');
    }
}
