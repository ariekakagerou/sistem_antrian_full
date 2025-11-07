# ⚠️ UPDATE WAJIB - PetugasLoket.php

File: `app/Livewire/PetugasLoket.php`

## Property Baru yang WAJIB Ditambahkan:

```php
// Menu navigasi - WAJIB!
public $activeMenu = 'dashboard';

// Data untuk sidebar
public $petugasNama = 'Petugas';
public $loketNama = '';

// Statistik untuk dashboard
public $totalPasienHariIni = 0;
public $jumlahMenunggu = 0;
public $jumlahDilayani = 0;
public $jumlahSelesai = 0;

// Log aktivitas
public $logAktivitas = [];

// Pemanggilan
public $antrianBerikutnya = null;
public $antrianMenunggu = [];

// Riwayat
public $riwayatPelayanan = [];
public $searchRiwayat = '';
public $filterTanggal = '';

// Pengaturan
public $profilNama = '';
public $profilEmail = '';
```

## Method Baru (Minimal):

```php
public function updated($propertyName)
{
    // Update loket name saat loket dipilih
    if ($propertyName === 'selectedLoket' && $this->selectedLoket) {
        $loket = collect($this->lokets)->firstWhere('id', $this->selectedLoket);
        $this->loketNama = $loket['nama_loket'] ?? '';
        $this->activeMenu = 'dashboard';
        $this->loadStatistik();
    }
}

public function loadStatistik()
{
    if (!$this->antrians) return;
    
    $this->totalPasienHariIni = count($this->antrians);
    $this->jumlahMenunggu = collect($this->antrians)->where('status', 'menunggu')->count();
    $this->jumlahDilayani = collect($this->antrians)->where('status', 'dipanggil')->count();
    $this->jumlahSelesai = collect($this->antrians)->where('status', 'selesai')->count();
    
    $this->antrianBerikutnya = collect($this->antrians)
        ->where('status', 'menunggu')
        ->first();
        
    $this->antrianMenunggu = collect($this->antrians)
        ->where('status', 'menunggu')
        ->values()
        ->all();
        
    $this->riwayatPelayanan = collect($this->antrians)
        ->where('status', 'selesai')
        ->values()
        ->all();
}

public function refreshData()
{
    $this->loadAntrians($this->apiService);
    $this->loadStatistik();
}
```

## CARA CEPAT - Copy Paste Ini:

Buka `app/Livewire/PetugasLoket.php` dan tambahkan di bagian property (setelah property yang sudah ada):

```php
// === TAMBAHAN BARU ===
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
```

Dan tambahkan method ini sebelum method `render()`:

```php
public function updated($propertyName)
{
    if ($propertyName === 'selectedLoket' && $this->selectedLoket) {
        $loket = collect($this->lokets)->firstWhere('id', $this->selectedLoket);
        $this->loketNama = $loket['nama_loket'] ?? '';
        $this->activeMenu = 'dashboard';
        $this->loadStatistik();
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
}

public function refreshData()
{
    if (method_exists($this, 'loadAntrians')) {
        $this->loadAntrians(app(\App\Services\ApiService::class));
        $this->loadStatistik();
    }
}
```

## Setelah Update:

1. Save file
2. Refresh browser (Ctrl + F5)
3. Login petugas
4. Pilih loket
5. Sidebar akan muncul dengan menu navigasi!

---

**Status:** ⚠️ WAJIB DIUPDATE  
**Tanpa update ini, sidebar tidak akan berfungsi!**
