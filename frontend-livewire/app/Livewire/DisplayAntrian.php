<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\ApiService;
use Livewire\Attributes\Layout;

class DisplayAntrian extends Component
{
    public $lokets = [];
    public $antriansPerLoket = [];
    public $currentTime;

    public function mount(ApiService $apiService)
    {
        $this->loadData($apiService);
        $this->currentTime = now()->format('H:i:s');
    }

    public function loadData(ApiService $apiService)
    {
        try {
            // Load semua loket
            $response = $apiService->getLokets();
            $this->lokets = $response['data'] ?? [];

            // Load antrian untuk setiap loket
            $this->antriansPerLoket = [];
            foreach ($this->lokets as $loket) {
                $antrianResponse = $apiService->getAntrianByLoket($loket['id']);
                $antrians = $antrianResponse['data'] ?? [];
                
                // Filter hanya antrian yang dipanggil
                $antrianDipanggil = collect($antrians)
                    ->where('status', 'dipanggil')
                    ->first();
                
                // Hitung antrian menunggu
                $jumlahMenunggu = collect($antrians)
                    ->where('status', 'menunggu')
                    ->count();

                $this->antriansPerLoket[$loket['id']] = [
                    'loket' => $loket,
                    'antrian_aktif' => $antrianDipanggil,
                    'jumlah_menunggu' => $jumlahMenunggu,
                ];
            }

            $this->currentTime = now()->format('H:i:s');
        } catch (\Exception $e) {
            // Silent fail untuk display
        }
    }

    public function refresh(ApiService $apiService)
    {
        $this->loadData($apiService);
    }

    #[Layout('components.layout')]
    public function render()
    {
        return view('livewire.display-antrian');
    }
}
