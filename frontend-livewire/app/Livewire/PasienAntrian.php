<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\ApiService;

class PasienAntrian extends Component
{
    public $lokets = [];
    public $loket_id = '';
    public $nama_pasien = '';
    public $nik = '';
    public $no_rekam_medis = '';
    public $jenis_kelamin = '';
    public $tanggal_lahir = '';
    public $nomor_hp = '';
    public $alamat = '';
    public $keluhan = '';
    public $poli_tujuan = '';
    public $showSuccess = false;
    public $antrianBaru = null;
    public $riwayatAntrian = [];
    
    // Pagination
    public $currentPage = 1;
    public $perPage = 6;
    public $totalPages = 1;

    protected $rules = [
        'loket_id' => 'required',
        'nama_pasien' => 'required|min:3',
        'nik' => 'required|min:16|max:16',
        'jenis_kelamin' => 'required',
        'tanggal_lahir' => 'required|date|before:today',
        'nomor_hp' => 'required|min:10',
        'alamat' => 'required|min:5',
        'keluhan' => 'required|min:5',
        'poli_tujuan' => 'required',
    ];

    protected $messages = [
        'loket_id.required' => 'Pilih loket terlebih dahulu',
        'nama_pasien.required' => 'Nama pasien harus diisi',
        'nama_pasien.min' => 'Nama pasien minimal 3 karakter',
        'nik.required' => 'NIK harus diisi',
        'nik.min' => 'NIK harus 16 digit',
        'nik.max' => 'NIK harus 16 digit',
        'jenis_kelamin.required' => 'Pilih jenis kelamin',
        'tanggal_lahir.required' => 'Tanggal lahir harus diisi',
        'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini',
        'nomor_hp.required' => 'Nomor HP harus diisi',
        'nomor_hp.min' => 'Nomor HP minimal 10 digit',
        'alamat.required' => 'Alamat harus diisi',
        'alamat.min' => 'Alamat minimal 5 karakter',
        'keluhan.required' => 'Keluhan harus diisi',
        'keluhan.min' => 'Keluhan minimal 5 karakter',
        'poli_tujuan.required' => 'Pilih poli tujuan',
    ];

    public function mount(ApiService $apiService)
    {
        try {
            $response = $apiService->getLokets();
            $this->lokets = $response['data'] ?? [];
            $this->calculateTotalPages();
            $this->loadRiwayatAntrian($apiService);
        } catch (\Exception $e) {
            $this->dispatch('notification', [
                'message' => 'Gagal memuat data loket: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function loadRiwayatAntrian(ApiService $apiService)
    {
        try {
            // Ambil antrian aktif (status: menunggu atau dipanggil) hari ini
            $params = [
                'status' => 'menunggu,dipanggil',
                'tanggal' => date('Y-m-d')
            ];
            
            $response = $apiService->getAntrians($params);
            
            // Debug
            \Log::info('API Request', ['params' => $params]);
            \Log::info('API Response', ['response' => $response]);
            
            $this->riwayatAntrian = $response['data'] ?? [];
            
            // Debug: Log jumlah data yang diterima
            \Log::info('Riwayat Antrian loaded', [
                'count' => count($this->riwayatAntrian),
                'tanggal_filter' => date('Y-m-d'),
                'data' => $this->riwayatAntrian
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading riwayat antrian', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $this->riwayatAntrian = [];
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
        \Log::info('Method daftar() dipanggil');
        $this->validate();

        try {
            $response = $apiService->createAntrian([
                'loket_id' => $this->loket_id,
                'nama_pasien' => $this->nama_pasien,
                'nik' => $this->nik,
                'no_rekam_medis' => $this->no_rekam_medis,
                'jenis_kelamin' => $this->jenis_kelamin,
                'tanggal_lahir' => $this->tanggal_lahir,
                'nomor_hp' => $this->nomor_hp,
                'alamat' => $this->alamat,
                'keluhan' => $this->keluhan,
                'poli_tujuan' => $this->poli_tujuan,
            ]);

            $antrian = $response['data'] ?? null;
            \Log::info('createAntrian response', ['data' => $antrian]);
            if (is_object($antrian)) {
                $antrian = (array) $antrian;
            }
            if (is_array($antrian)) {
                if (isset($antrian['loket']) && is_object($antrian['loket'])) {
                    $antrian['loket'] = (array) $antrian['loket'];
                }
                if (!isset($antrian['nomor_antrian'])) {
                    $antrian['nomor_antrian'] = $antrian['no_antrian'] ?? ($antrian['nomorAntrian'] ?? null);
                }
            }
            $this->antrianBaru = $antrian;
            $this->showSuccess = (bool) $this->antrianBaru;
            
            // Debug log untuk modal sukses
            \Log::info('Modal sukses data', [
                'showSuccess' => $this->showSuccess,
                'antrianBaru' => $this->antrianBaru,
                'nomor_antrian' => $this->antrianBaru['nomor_antrian'] ?? 'null'
            ]);

            // Reset form
            $this->reset(['loket_id', 'nama_pasien', 'nik', 'no_rekam_medis', 'jenis_kelamin', 'tanggal_lahir', 'nomor_hp', 'alamat', 'keluhan', 'poli_tujuan']);

            // Refresh riwayat antrian
            $this->loadRiwayatAntrian($apiService);

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

    // Alias method untuk form-pendaftaran.blade.php
    public function daftarPasien(ApiService $apiService)
    {
        return $this->daftar($apiService);
    }

    public function closeSuccess()
    {
        $this->showSuccess = false;
        $this->antrianBaru = null;
        \Log::info('Modal sukses ditutup');
    }

    // Method untuk test modal secara manual
    public function testModal()
    {
        $this->antrianBaru = [
            'nomor_antrian' => 'T001',
            'nama_pasien' => 'Test Patient',
            'loket' => 'Loket Test',
            'poli_tujuan' => 'Poli Test',
            'waktu_pendaftaran' => now()->format('H:i:s'),
            'estimasi_waktu_tunggu' => '30 menit',
            'perkiraan_dipanggil' => now()->addMinutes(30)->format('H:i') . ' WIB'
        ];
        $this->showSuccess = true;
        \Log::info('Test modal dipanggil');
    }

    public function cetakTiket()
    {
        // Trigger print dialog menggunakan JavaScript
        $this->dispatch('print-tiket');
    }

    public function downloadTiket()
    {
        if (!$this->antrianBaru) {
            $this->dispatch('notification', [
                'message' => 'Data antrian tidak ditemukan',
                'type' => 'error'
            ]);
            return;
        }

        // Redirect ke endpoint download PDF di backend
        $antrianId = $this->antrianBaru['id'] ?? null;
        if ($antrianId) {
            return redirect()->to(config('app.backend_url') . "/api/antrian/cetak/{$antrianId}");
        }
    }

    public function kirimWhatsApp()
    {
        if (!$this->antrianBaru) {
            $this->dispatch('notification', [
                'message' => 'Data antrian tidak ditemukan',
                'type' => 'error'
            ]);
            return;
        }

        // Format pesan WhatsApp
        $nomorAntrian = $this->antrianBaru['nomor_antrian'] ?? '-';
        $namaPasien = $this->antrianBaru['nama_pasien'] ?? '-';
        $loket = $this->antrianBaru['loket']['nama_loket'] ?? '-';
        $estimasi = $this->antrianBaru['estimasi_waktu_tunggu'] ?? '-';
        
        $pesan = "*TIKET ANTRIAN RUMAH SAKIT*\n\n";
        $pesan .= "Nomor Antrian: *{$nomorAntrian}*\n";
        $pesan .= "Nama Pasien: {$namaPasien}\n";
        $pesan .= "Loket: {$loket}\n";
        $pesan .= "Estimasi Tunggu: {$estimasi}\n\n";
        $pesan .= "Harap datang 15 menit sebelum waktu estimasi.\n";
        $pesan .= "Terima kasih.";

        // Encode pesan untuk URL
        $pesanEncoded = urlencode($pesan);
        
        // Buka WhatsApp dengan pesan
        $this->dispatch('open-whatsapp', ['message' => $pesanEncoded]);
    }

    // Method untuk auto-refresh data antrian (dipanggil oleh wire:poll)
    public function refreshRiwayat()
    {
        $apiService = app(ApiService::class);
        $this->loadRiwayatAntrian($apiService);
    }

    public function render()
    {
        return view('livewire.pasien-antrian');
    }
}
