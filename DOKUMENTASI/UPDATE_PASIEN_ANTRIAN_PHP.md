# 🔧 Update PasienAntrian.php Component

File yang perlu diupdate: `frontend-livewire/app/Livewire/PasienAntrian.php`

## 📝 Property Baru

Tambahkan property berikut ke class `PasienAntrian`:

```php
<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\ApiService;
use Livewire\Attributes\Layout;

class PasienAntrian extends Component
{
    // Existing properties
    public $lokets = [];
    public $loket_id = null;
    public $showSuccess = false;
    public $antrianBaru = null;
    
    // Pagination properties
    public $currentPage = 1;
    public $perPage = 6;
    public $totalPages = 1;
    
    // NEW: Form properties - Data Pasien Lengkap
    public $nama_pasien = '';
    public $nik = '';
    public $no_rekam_medis = '';
    public $jenis_kelamin = '';
    public $tanggal_lahir = '';
    public $nomor_hp = '';
    public $poli_tujuan = '';
    public $alamat = '';
    public $keluhan = '';
    
    // NEW: Riwayat & Estimasi
    public $riwayatAntrian = [];
    public $estimasiWaktu = null;
    public $jumlahAntrian = 0;

    // Validation rules
    protected $rules = [
        'loket_id' => 'required',
        'nama_pasien' => 'required|min:3',
        'nik' => 'required|digits:16',
        'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        'tanggal_lahir' => 'required|date|before:today',
        'nomor_hp' => 'required|regex:/^08[0-9]{9,11}$/',
        'poli_tujuan' => 'required',
        'alamat' => 'required|min:10',
        'keluhan' => 'required|min:10',
    ];

    protected $messages = [
        'loket_id.required' => 'Silakan pilih loket terlebih dahulu',
        'nama_pasien.required' => 'Nama pasien wajib diisi',
        'nama_pasien.min' => 'Nama pasien minimal 3 karakter',
        'nik.required' => 'NIK/KTP wajib diisi',
        'nik.digits' => 'NIK harus 16 digit',
        'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
        'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
        'tanggal_lahir.before' => 'Tanggal lahir tidak valid',
        'nomor_hp.required' => 'Nomor HP wajib diisi',
        'nomor_hp.regex' => 'Format nomor HP tidak valid (contoh: 08123456789)',
        'poli_tujuan.required' => 'Poli tujuan wajib dipilih',
        'alamat.required' => 'Alamat wajib diisi',
        'alamat.min' => 'Alamat minimal 10 karakter',
        'keluhan.required' => 'Keluhan wajib diisi',
        'keluhan.min' => 'Keluhan minimal 10 karakter',
    ];
```

---

## 🔄 Update Method mount()

```php
public function mount(ApiService $apiService)
{
    $this->loadLokets($apiService);
    $this->loadRiwayatAntrian($apiService);
    $this->calculateTotalPages();
}
```

---

## ➕ Method Baru

### 1. Load Riwayat Antrian

```php
public function loadRiwayatAntrian(ApiService $apiService)
{
    try {
        // Ambil semua antrian aktif (status: menunggu, dipanggil)
        $response = $apiService->get('/antrian/aktif');
        $this->riwayatAntrian = $response['data'] ?? [];
    } catch (\Exception $e) {
        $this->riwayatAntrian = [];
        \Log::error('Error loading riwayat antrian: ' . $e->getMessage());
    }
}
```

### 2. Hitung Estimasi Waktu

```php
public function hitungEstimasi()
{
    if (!$this->loket_id) {
        $this->estimasiWaktu = null;
        $this->jumlahAntrian = 0;
        return;
    }

    // Hitung jumlah antrian yang menunggu di loket yang dipilih
    $this->jumlahAntrian = collect($this->riwayatAntrian)
        ->where('loket_id', $this->loket_id)
        ->whereIn('status', ['menunggu', 'dipanggil'])
        ->count();
    
    // Estimasi: 5 menit per pasien
    $this->estimasiWaktu = $this->jumlahAntrian * 5;
}
```

### 3. Livewire Updated Hook

```php
public function updated($propertyName)
{
    // Validasi real-time untuk field tertentu
    $this->validateOnly($propertyName);
    
    // Hitung estimasi saat loket dipilih
    if ($propertyName === 'loket_id' && $this->loket_id) {
        $this->hitungEstimasi();
    }
}
```

### 4. Method Daftar Pasien (Update)

```php
public function daftarPasien(ApiService $apiService)
{
    // Validasi semua input
    $this->validate();

    try {
        // Prepare data
        $data = [
            'loket_id' => $this->loket_id,
            'nama_pasien' => $this->nama_pasien,
            'nik' => $this->nik,
            'no_rekam_medis' => $this->no_rekam_medis,
            'jenis_kelamin' => $this->jenis_kelamin,
            'tanggal_lahir' => $this->tanggal_lahir,
            'nomor_hp' => $this->nomor_hp,
            'poli_tujuan' => $this->poli_tujuan,
            'alamat' => $this->alamat,
            'keluhan' => $this->keluhan,
        ];

        // Kirim ke API
        $response = $apiService->createAntrian($data);

        if (isset($response['data'])) {
            $this->antrianBaru = $response['data'];
            $this->showSuccess = true;
            
            // Reset form
            $this->reset([
                'nama_pasien', 'nik', 'no_rekam_medis', 
                'jenis_kelamin', 'tanggal_lahir', 'nomor_hp',
                'poli_tujuan', 'alamat', 'keluhan'
            ]);
            
            // Reload riwayat antrian
            $this->loadRiwayatAntrian($apiService);
            
            // Dispatch success event
            $this->dispatch('notification', [
                'message' => 'Pendaftaran berhasil! Nomor antrian: ' . $this->antrianBaru['nomor_antrian'],
                'type' => 'success'
            ]);
        }
        
    } catch (\Exception $e) {
        $this->dispatch('notification', [
            'message' => 'Gagal mendaftar: ' . $e->getMessage(),
            'type' => 'error'
        ]);
        
        \Log::error('Error daftar pasien: ' . $e->getMessage());
    }
}
```

### 5. Cetak Tiket

```php
public function cetakTiket()
{
    // Trigger print dialog
    $this->dispatch('print-tiket');
    
    // Log activity
    \Log::info('Tiket dicetak untuk antrian: ' . ($this->antrianBaru['nomor_antrian'] ?? 'unknown'));
}
```

### 6. Download Tiket PDF

```php
public function downloadTiket()
{
    if (!$this->antrianBaru) {
        $this->dispatch('notification', [
            'message' => 'Data antrian tidak ditemukan',
            'type' => 'error'
        ]);
        return;
    }

    try {
        // Generate PDF menggunakan DomPDF atau TCPDF
        // Contoh dengan DomPDF:
        
        $pdf = \PDF::loadView('pdf.tiket-antrian', [
            'antrian' => $this->antrianBaru
        ]);
        
        $filename = 'Tiket-Antrian-' . $this->antrianBaru['nomor_antrian'] . '.pdf';
        
        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->output();
        }, $filename);
        
    } catch (\Exception $e) {
        $this->dispatch('notification', [
            'message' => 'Gagal download tiket: ' . $e->getMessage(),
            'type' => 'error'
        ]);
    }
}
```

### 7. Kirim WhatsApp

```php
public function kirimWhatsApp()
{
    if (!$this->antrianBaru || !isset($this->antrianBaru['nomor_hp'])) {
        $this->dispatch('notification', [
            'message' => 'Nomor HP tidak ditemukan',
            'type' => 'error'
        ]);
        return;
    }

    try {
        $nomor = preg_replace('/[^0-9]/', '', $this->antrianBaru['nomor_hp']);
        
        // Format nomor untuk WhatsApp (62xxx)
        if (substr($nomor, 0, 1) === '0') {
            $nomor = '62' . substr($nomor, 1);
        }
        
        // Buat pesan
        $pesan = "🏥 *Rumah Sakit Sehat Selalu*\n\n";
        $pesan .= "✅ Pendaftaran Berhasil!\n\n";
        $pesan .= "📋 *Nomor Antrian:* " . $this->antrianBaru['nomor_antrian'] . "\n";
        $pesan .= "👤 *Nama:* " . $this->antrianBaru['nama_pasien'] . "\n";
        $pesan .= "🚪 *Loket:* " . ($this->antrianBaru['loket']['nama_loket'] ?? '-') . "\n";
        
        if (isset($this->antrianBaru['poli_tujuan'])) {
            $pesan .= "🏥 *Poli:* " . $this->antrianBaru['poli_tujuan'] . "\n";
        }
        
        $pesan .= "📅 *Tanggal:* " . date('d F Y, H:i') . "\n\n";
        $pesan .= "⏰ Estimasi waktu tunggu: " . ($this->estimasiWaktu ?? 'N/A') . " menit\n\n";
        $pesan .= "ℹ️ Silakan tunggu nomor Anda dipanggil.\n";
        $pesan .= "Terima kasih! 🙏";
        
        // WhatsApp URL
        $url = "https://wa.me/" . $nomor . "?text=" . urlencode($pesan);
        
        // Redirect ke WhatsApp
        return redirect()->away($url);
        
    } catch (\Exception $e) {
        $this->dispatch('notification', [
            'message' => 'Gagal mengirim WhatsApp: ' . $e->getMessage(),
            'type' => 'error'
        ]);
    }
}
```

### 8. Close Success Modal

```php
public function closeSuccess()
{
    $this->showSuccess = false;
    $this->antrianBaru = null;
    
    // Reload riwayat untuk update terbaru
    $this->dispatch('refresh-riwayat');
}
```

---

## 📋 Complete Updated File

Berikut adalah file lengkap yang sudah diupdate:

```php
<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\ApiService;
use Livewire\Attributes\Layout;

class PasienAntrian extends Component
{
    // Loket data
    public $lokets = [];
    public $loket_id = null;
    
    // Pagination
    public $currentPage = 1;
    public $perPage = 6;
    public $totalPages = 1;
    
    // Form data - Lengkap
    public $nama_pasien = '';
    public $nik = '';
    public $no_rekam_medis = '';
    public $jenis_kelamin = '';
    public $tanggal_lahir = '';
    public $nomor_hp = '';
    public $poli_tujuan = '';
    public $alamat = '';
    public $keluhan = '';
    
    // Riwayat & Estimasi
    public $riwayatAntrian = [];
    public $estimasiWaktu = null;
    public $jumlahAntrian = 0;
    
    // Modal
    public $showSuccess = false;
    public $antrianBaru = null;

    protected $rules = [
        'loket_id' => 'required',
        'nama_pasien' => 'required|min:3',
        'nik' => 'required|digits:16',
        'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        'tanggal_lahir' => 'required|date|before:today',
        'nomor_hp' => 'required|regex:/^08[0-9]{9,11}$/',
        'poli_tujuan' => 'required',
        'alamat' => 'required|min:10',
        'keluhan' => 'required|min:10',
    ];

    protected $messages = [
        'loket_id.required' => 'Silakan pilih loket terlebih dahulu',
        'nama_pasien.required' => 'Nama pasien wajib diisi',
        'nama_pasien.min' => 'Nama pasien minimal 3 karakter',
        'nik.required' => 'NIK/KTP wajib diisi',
        'nik.digits' => 'NIK harus 16 digit',
        'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
        'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
        'tanggal_lahir.before' => 'Tanggal lahir tidak valid',
        'nomor_hp.required' => 'Nomor HP wajib diisi',
        'nomor_hp.regex' => 'Format nomor HP tidak valid (contoh: 08123456789)',
        'poli_tujuan.required' => 'Poli tujuan wajib dipilih',
        'alamat.required' => 'Alamat wajib diisi',
        'alamat.min' => 'Alamat minimal 10 karakter',
        'keluhan.required' => 'Keluhan wajib diisi',
        'keluhan.min' => 'Keluhan minimal 10 karakter',
    ];

    public function mount(ApiService $apiService)
    {
        $this->loadLokets($apiService);
        $this->loadRiwayatAntrian($apiService);
        $this->calculateTotalPages();
    }

    public function loadLokets(ApiService $apiService)
    {
        try {
            $response = $apiService->getLokets();
            $this->lokets = $response['data'] ?? [];
            $this->calculateTotalPages();
        } catch (\Exception $e) {
            $this->lokets = [];
        }
    }

    public function loadRiwayatAntrian(ApiService $apiService)
    {
        try {
            $response = $apiService->get('/antrian/aktif');
            $this->riwayatAntrian = $response['data'] ?? [];
        } catch (\Exception $e) {
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

    public function hitungEstimasi()
    {
        if (!$this->loket_id) {
            $this->estimasiWaktu = null;
            $this->jumlahAntrian = 0;
            return;
        }

        $this->jumlahAntrian = collect($this->riwayatAntrian)
            ->where('loket_id', $this->loket_id)
            ->whereIn('status', ['menunggu', 'dipanggil'])
            ->count();
        
        $this->estimasiWaktu = $this->jumlahAntrian * 5;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
        
        if ($propertyName === 'loket_id' && $this->loket_id) {
            $this->hitungEstimasi();
        }
    }

    public function daftarPasien(ApiService $apiService)
    {
        $this->validate();

        try {
            $data = [
                'loket_id' => $this->loket_id,
                'nama_pasien' => $this->nama_pasien,
                'nik' => $this->nik,
                'no_rekam_medis' => $this->no_rekam_medis,
                'jenis_kelamin' => $this->jenis_kelamin,
                'tanggal_lahir' => $this->tanggal_lahir,
                'nomor_hp' => $this->nomor_hp,
                'poli_tujuan' => $this->poli_tujuan,
                'alamat' => $this->alamat,
                'keluhan' => $this->keluhan,
            ];

            $response = $apiService->createAntrian($data);

            if (isset($response['data'])) {
                $this->antrianBaru = $response['data'];
                $this->showSuccess = true;
                
                $this->reset([
                    'nama_pasien', 'nik', 'no_rekam_medis', 
                    'jenis_kelamin', 'tanggal_lahir', 'nomor_hp',
                    'poli_tujuan', 'alamat', 'keluhan'
                ]);
                
                $this->loadRiwayatAntrian($apiService);
                
                $this->dispatch('notification', [
                    'message' => 'Pendaftaran berhasil!',
                    'type' => 'success'
                ]);
            }
            
        } catch (\Exception $e) {
            $this->dispatch('notification', [
                'message' => 'Gagal mendaftar: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function cetakTiket()
    {
        $this->dispatch('print-tiket');
    }

    public function downloadTiket()
    {
        // Implementation for PDF download
    }

    public function kirimWhatsApp()
    {
        if (!$this->antrianBaru || !isset($this->antrianBaru['nomor_hp'])) {
            return;
        }

        $nomor = preg_replace('/[^0-9]/', '', $this->antrianBaru['nomor_hp']);
        if (substr($nomor, 0, 1) === '0') {
            $nomor = '62' . substr($nomor, 1);
        }
        
        $pesan = "Pendaftaran berhasil!\n\n";
        $pesan .= "Nomor Antrian: " . $this->antrianBaru['nomor_antrian'] . "\n";
        $pesan .= "Nama: " . $this->antrianBaru['nama_pasien'] . "\n";
        $pesan .= "Loket: " . ($this->antrianBaru['loket']['nama_loket'] ?? '-') . "\n\n";
        $pesan .= "Silakan tunggu nomor Anda dipanggil.";
        
        $url = "https://wa.me/" . $nomor . "?text=" . urlencode($pesan);
        return redirect()->away($url);
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
```

---

## ✅ Checklist Update

- [ ] Copy property baru ke PasienAntrian.php
- [ ] Update method mount()
- [ ] Tambahkan method loadRiwayatAntrian()
- [ ] Tambahkan method hitungEstimasi()
- [ ] Tambahkan method updated()
- [ ] Update method daftarPasien()
- [ ] Tambahkan method cetakTiket()
- [ ] Tambahkan method downloadTiket()
- [ ] Tambahkan method kirimWhatsApp()
- [ ] Update method closeSuccess()
- [ ] Test semua fitur

---

**File Location:** `frontend-livewire/app/Livewire/PasienAntrian.php`  
**Status:** Ready to implement  
**Version:** 2.0.0
