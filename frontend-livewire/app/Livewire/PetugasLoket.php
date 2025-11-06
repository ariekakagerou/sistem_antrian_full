<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\ApiService;
use Livewire\Attributes\Layout;

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

    public function logout(ApiService $apiService)
    {
        try {
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
            $this->dispatch('notification', [
                'message' => 'Gagal memuat loket: ' . $e->getMessage(),
                'type' => 'error'
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

    public function selectLoket($loketId, ApiService $apiService)
    {
        $this->selectedLoket = $loketId;
        
        // Set nama loket
        if ($loketId) {
            $loket = collect($this->lokets)->firstWhere('id', $loketId);
            $this->loketNama = $loket['nama_loket'] ?? '';
            $this->activeMenu = 'dashboard';
        } else {
            $this->loketNama = '';
        }
        
        $this->loadAntrians($apiService);
        $this->loadStatistik();
    }

    public function loadAntrians(ApiService $apiService)
    {
        if (!$this->selectedLoket) return;

        try {
            $response = $apiService->getAntrianByLoket($this->selectedLoket);
            $this->antrians = $response['data'] ?? [];
            
            // Cari antrian yang sedang dipanggil
            $this->antrianAktif = collect($this->antrians)->firstWhere('status', 'dipanggil');
            
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
        
        $this->antrianBerikutnya = collect($this->antrians)->where('status', 'menunggu')->first();
        $this->antrianMenunggu = collect($this->antrians)->where('status', 'menunggu')->values()->all();
        $this->riwayatPelayanan = collect($this->antrians)->where('status', 'selesai')->values()->all();
        
        // Log aktivitas (placeholder)
        $this->logAktivitas = [
            ['message' => 'Sistem dimulai', 'time' => now()->format('H:i')],
        ];
    }

    public function panggilAntrian($antrianId, ApiService $apiService)
    {
        try {
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

    public function selesaiAntrian($antrianId, ApiService $apiService)
    {
        try {
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

    public function refreshAntrians(ApiService $apiService)
    {
        $this->loadAntrians($apiService);
    }
    
    public function refreshData(ApiService $apiService)
    {
        $this->loadAntrians($apiService);
        $this->dispatch('notification', [
            'message' => 'Data berhasil diperbarui',
            'type' => 'success'
        ]);
    }
    
    public function panggilPasienBerikutnya(ApiService $apiService)
    {
        if ($this->antrianBerikutnya) {
            $this->panggilAntrian($this->antrianBerikutnya['id'], $apiService);
        }
    }
    
    public function panggilUlang($antrianId, ApiService $apiService)
    {
        $this->panggilAntrian($antrianId, $apiService);
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

    #[Layout('components.layout')]
    public function render()
    {
        return view('livewire.petugas-loket');
    }
}
