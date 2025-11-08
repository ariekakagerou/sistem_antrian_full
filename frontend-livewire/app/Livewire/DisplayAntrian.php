<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\ApiService;

class DisplayAntrian extends Component
{
    public $lokets = [];
    public $antriansPerLoket = [];
    public $currentTime;
    public $lastAntrianDipanggil = [];
    public $showNotification = false;
    public $notificationMessage = '';
    
    // Cache untuk mengurangi API call
    private $lastLoadTime = null;
    private $cacheTimeout = 2; // 2 detik cache

    public function mount(ApiService $apiService)
    {
        try {
            $this->loadData($apiService);
        } catch (\Exception $e) {
            \Log::error('DisplayAntrian mount error: ' . $e->getMessage());
            // Set default values jika gagal
            $this->lokets = [];
            $this->antriansPerLoket = [];
        }
        $this->currentTime = now()->format('H:i:s');
    }

    public function loadData(ApiService $apiService)
    {
        // Cek cache untuk menghindari API call yang terlalu sering
        $currentTime = now();
        if ($this->lastLoadTime && $currentTime->diffInSeconds($this->lastLoadTime) < $this->cacheTimeout) {
            \Log::info('Using cached data, skipping API call');
            return;
        }
        
        $this->lastLoadTime = $currentTime;
        
        try {
            // Load semua loket dengan timeout protection
            $response = $apiService->getLokets();
            $this->lokets = $response['data'] ?? [];
            
            // Debug log untuk melihat jumlah loket
            \Log::info('Jumlah loket yang dimuat: ' . count($this->lokets));

            // Gunakan endpoint yang lebih spesifik untuk menghindari timeout
            // Ambil data antrian yang sedang dipanggil
            $dipanggilResponse = $apiService->getAntrianDipanggil();
            $antrianDipanggilList = collect($dipanggilResponse['data'] ?? []);
            
            // Debug log untuk melihat antrian yang dipanggil
            \Log::info('Jumlah antrian yang dipanggil: ' . $antrianDipanggilList->count());

            // Simpan data lama untuk deteksi perubahan
            $oldAntrianDipanggil = $this->lastAntrianDipanggil;
            $newAntrianDipanggil = [];

            // Group data per loket
            $this->antriansPerLoket = [];
            
            // Batasi jumlah loket yang diproses untuk menghindari timeout
            $maxLokets = min(count($this->lokets), 6); // Maksimal 6 loket
            
            foreach (array_slice($this->lokets, 0, $maxLokets) as $loket) {
                // Cari antrian yang sedang dipanggil untuk loket ini
                $antrianDipanggil = $antrianDipanggilList
                    ->where('loket_id', $loket['id'])
                    ->first();
                
                if ($antrianDipanggil) {
                    $newAntrianDipanggil[$loket['id']] = $antrianDipanggil['id'];
                    
                    // Deteksi apakah ada antrian baru yang dipanggil
                    if (!isset($oldAntrianDipanggil[$loket['id']]) || 
                        $oldAntrianDipanggil[$loket['id']] !== $antrianDipanggil['id']) {
                        $this->showNotification = true;
                        $this->notificationMessage = "Antrian {$antrianDipanggil['nomor_antrian']} - {$antrianDipanggil['nama_pasien']} dipanggil di {$loket['nama_loket']}";
                        
                        // Auto hide notification after 5 seconds
                        $this->dispatch('hideNotification');
                    }
                }
                
                // Ambil daftar antrian menunggu untuk loket ini
                try {
                    $menungguResponse = $apiService->getAntrianMenungguPerLoket($loket['id']);
                    $antrianMenunggu = collect($menungguResponse['data'] ?? [])
                        ->sortBy('nomor_antrian')
                        ->values()
                        ->all();
                    
                    // Debug log untuk melihat antrian menunggu
                    \Log::info("Loket {$loket['id']} ({$loket['nama_loket']}): " . count($antrianMenunggu) . " antrian menunggu");
                } catch (\Exception $e) {
                    // Jika gagal ambil data menunggu, gunakan array kosong
                    $antrianMenunggu = [];
                    \Log::warning("Gagal ambil antrian menunggu untuk loket {$loket['id']}: " . $e->getMessage());
                }
                
                $jumlahMenunggu = count($antrianMenunggu);

                // Tampilkan loket yang ada antriannya (dipanggil atau menunggu)
                // Logic ini untuk memastikan hanya antrian aktif yang ditampilkan
                if ($antrianDipanggil || $jumlahMenunggu > 0) {
                    $this->antriansPerLoket[$loket['id']] = [
                        'loket' => $loket,
                        'antrian_aktif' => $antrianDipanggil,
                        'antrian_menunggu' => $antrianMenunggu,
                        'jumlah_menunggu' => $jumlahMenunggu,
                        'status' => $antrianDipanggil ? 'dipanggil' : 'menunggu',
                    ];
                } else {
                    // Jika tidak ada antrian aktif (menunggu/dipanggil), loket tidak ditampilkan
                    // Ini akan otomatis menyembunyikan loket jika semua antrian selesai
                    \Log::info("Loket {$loket['nama_loket']} tidak memiliki antrian aktif, tidak ditampilkan");
                }
            }

            // Update last antrian dipanggil
            $this->lastAntrianDipanggil = $newAntrianDipanggil;
            $this->currentTime = now()->format('H:i:s');
            
            // Debug log untuk melihat total loket yang ditampilkan
            \Log::info('Total loket yang ditampilkan: ' . count($this->antriansPerLoket));
        } catch (\Exception $e) {
            // Log error tapi jangan throw exception
            \Log::error('DisplayAntrian loadData error: ' . $e->getMessage());
            
            // Set default values
            $this->lokets = [];
            $this->antriansPerLoket = [];
            $this->currentTime = now()->format('H:i:s');
        }
    }

    public function refresh()
    {
        // Reset cache untuk memaksa reload data
        $this->lastLoadTime = null;
        $apiService = app(ApiService::class);
        $this->loadData($apiService);
    }
    
    public function forceRefresh()
    {
        // Method ini untuk refresh manual yang melewati cache
        $this->lastLoadTime = null;
        $this->lastAntrianDipanggil = [];
        $apiService = app(ApiService::class);
        
        try {
            $this->loadData($apiService);
            \Log::info('Force refresh completed successfully');
        } catch (\Exception $e) {
            \Log::error('Force refresh failed: ' . $e->getMessage());
            // Set default values
            $this->lokets = [];
            $this->antriansPerLoket = [];
            $this->currentTime = now()->format('H:i:s');
        }
    }

    public function render()
    {
        return view('livewire.display-antrian');
    }
}
