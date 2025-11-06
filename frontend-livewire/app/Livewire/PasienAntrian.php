<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\ApiService;
use Livewire\Attributes\Layout;

class PasienAntrian extends Component
{
    public $lokets = [];
    public $loket_id = '';
    public $nama_pasien = '';
    public $nomor_hp = '';
    public $alamat = '';
    public $keluhan = '';
    public $showSuccess = false;
    public $antrianBaru = null;
    
    // Pagination
    public $currentPage = 1;
    public $perPage = 6;
    public $totalPages = 1;

    protected $rules = [
        'loket_id' => 'required',
        'nama_pasien' => 'required|min:3',
        'nomor_hp' => 'required|min:10',
        'alamat' => 'required|min:5',
        'keluhan' => 'required|min:5',
    ];

    protected $messages = [
        'loket_id.required' => 'Pilih loket terlebih dahulu',
        'nama_pasien.required' => 'Nama pasien harus diisi',
        'nama_pasien.min' => 'Nama pasien minimal 3 karakter',
        'nomor_hp.required' => 'Nomor HP harus diisi',
        'nomor_hp.min' => 'Nomor HP minimal 10 digit',
        'alamat.required' => 'Alamat harus diisi',
        'alamat.min' => 'Alamat minimal 5 karakter',
        'keluhan.required' => 'Keluhan harus diisi',
        'keluhan.min' => 'Keluhan minimal 5 karakter',
    ];

    public function mount(ApiService $apiService)
    {
        try {
            $response = $apiService->getLokets();
            $this->lokets = $response['data'] ?? [];
            $this->calculateTotalPages();
        } catch (\Exception $e) {
            $this->dispatch('notification', [
                'message' => 'Gagal memuat data loket: ' . $e->getMessage(),
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

    public function daftar(ApiService $apiService)
    {
        $this->validate();

        try {
            $response = $apiService->createAntrian([
                'loket_id' => $this->loket_id,
                'nama_pasien' => $this->nama_pasien,
                'nomor_hp' => $this->nomor_hp,
                'alamat' => $this->alamat,
                'keluhan' => $this->keluhan,
            ]);

            $this->antrianBaru = $response['data'] ?? null;
            $this->showSuccess = true;

            // Reset form
            $this->reset(['loket_id', 'nama_pasien', 'nomor_hp', 'alamat', 'keluhan']);

            $this->dispatch('notification', [
                'message' => 'Pendaftaran berhasil! Nomor antrian Anda: ' . ($this->antrianBaru['nomor_antrian'] ?? ''),
                'type' => 'success'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notification', [
                'message' => 'Gagal mendaftar: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function closeSuccess()
    {
        $this->showSuccess = false;
        $this->antrianBaru = null;
    }

    #[Layout('components.layout')]
    public function render()
    {
        return view('livewire.pasien-antrian');
    }
}
