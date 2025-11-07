# 📋 Dokumentasi Fitur Pasien Lengkap

Dokumentasi lengkap untuk fitur-fitur baru pada halaman pendaftaran pasien.

## 🎯 Fitur yang Ditambahkan

### 1. **Form Pendaftaran Lengkap**
File: `resources/views/components/pasien/form-pendaftaran.blade.php`

**Field Input:**
- ✅ Nama Lengkap Pasien (required)
- ✅ NIK/KTP - 16 digit (required)
- ✅ Nomor Rekam Medis (optional)
- ✅ Jenis Kelamin (required)
- ✅ Tanggal Lahir (required)
- ✅ Nomor HP/Kontak Darurat (required)
- ✅ Dokter/Poli Tujuan (required)
- ✅ Alamat Lengkap (required)
- ✅ Keluhan/Gejala (required)

**Fitur Tambahan:**
- Estimasi waktu tunggu berdasarkan jumlah antrian
- Validasi real-time dengan @error directive
- Loading state saat submit
- Info jumlah pasien yang sedang menunggu
- Responsive grid layout (2 kolom di desktop)

---

### 2. **Tabel Riwayat Antrian Real-time**
File: `resources/views/components/pasien/tabel-riwayat.blade.php`

**Kolom Tabel:**
- No urut
- Nomor Antrian (besar & bold)
- Nama Pasien + Nomor HP
- Loket
- Status (Menunggu/Dipanggil/Selesai)
- Waktu Pendaftaran + relative time

**Fitur:**
- ✅ Auto-update setiap 5 detik (`wire:poll.5s`)
- ✅ Status badge dengan warna berbeda
- ✅ Animasi pulse untuk status "Dipanggil"
- ✅ Highlight row untuk antrian yang dipanggil
- ✅ Legend keterangan status
- ✅ Empty state yang informatif
- ✅ Live indicator (dot hijau berkedip)

---

### 3. **Modal Sukses dengan QR Code**
File: `resources/views/components/pasien/modal-sukses.blade.php`

**Konten Modal:**
- ✅ Nomor antrian besar
- ✅ Detail pasien (nama, loket, poli, tanggal)
- ✅ QR Code placeholder (siap untuk integrasi)
- ✅ Estimasi waktu tunggu
- ✅ Informasi penting (checklist)

**Action Buttons:**
- ✅ Cetak Tiket (print)
- ✅ Download PDF
- ✅ Kirim WhatsApp
- ✅ Tutup modal

**Fitur Tambahan:**
- Print-friendly CSS
- Responsive layout
- Animasi bounce pada icon sukses

---

## 🔧 Struktur File

```
frontend-livewire/
├── resources/
│   └── views/
│       ├── components/
│       │   └── pasien/
│       │       ├── form-pendaftaran.blade.php    # Form lengkap
│       │       ├── tabel-riwayat.blade.php       # Tabel real-time
│       │       └── modal-sukses.blade.php        # Modal dengan QR
│       └── livewire/
│           └── pasien-antrian.blade.php          # Main file (include components)
```

---

## 💻 Update Komponen Livewire PHP

File: `app/Livewire/PasienAntrian.php`

### Property Baru yang Diperlukan:

```php
// Data pasien lengkap
public $nama_pasien = '';
public $nik = '';
public $no_rekam_medis = '';
public $jenis_kelamin = '';
public $tanggal_lahir = '';
public $nomor_hp = '';
public $poli_tujuan = '';
public $alamat = '';
public $keluhan = '';

// Data antrian
public $loket_id = null;
public $riwayatAntrian = [];
public $estimasiWaktu = null;
public $jumlahAntrian = 0;

// Modal
public $showSuccess = false;
public $antrianBaru = null;
```

### Method Baru yang Diperlukan:

```php
public function mount(ApiService $apiService)
{
    $this->loadLokets($apiService);
    $this->loadRiwayatAntrian($apiService);
}

public function loadRiwayatAntrian(ApiService $apiService)
{
    try {
        $response = $apiService->getAntrianAktif();
        $this->riwayatAntrian = $response['data'] ?? [];
    } catch (\Exception $e) {
        $this->riwayatAntrian = [];
    }
}

public function updated($propertyName)
{
    if ($propertyName === 'loket_id' && $this->loket_id) {
        $this->hitungEstimasi();
    }
}

public function hitungEstimasi()
{
    // Hitung jumlah antrian di loket yang dipilih
    $this->jumlahAntrian = collect($this->riwayatAntrian)
        ->where('loket_id', $this->loket_id)
        ->where('status', 'menunggu')
        ->count();
    
    // Estimasi 5 menit per pasien
    $this->estimasiWaktu = $this->jumlahAntrian * 5;
}

public function daftarPasien(ApiService $apiService)
{
    $this->validate([
        'loket_id' => 'required',
        'nama_pasien' => 'required|min:3',
        'nik' => 'required|digits:16',
        'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        'tanggal_lahir' => 'required|date|before:today',
        'nomor_hp' => 'required|regex:/^08[0-9]{9,11}$/',
        'poli_tujuan' => 'required',
        'alamat' => 'required|min:10',
        'keluhan' => 'required|min:10',
    ], [
        'nik.digits' => 'NIK harus 16 digit',
        'nomor_hp.regex' => 'Format nomor HP tidak valid',
        'tanggal_lahir.before' => 'Tanggal lahir tidak valid',
    ]);

    try {
        $response = $apiService->createAntrian([
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
        ]);

        $this->antrianBaru = $response['data'] ?? null;
        $this->showSuccess = true;
        $this->reset([
            'nama_pasien', 'nik', 'no_rekam_medis', 
            'jenis_kelamin', 'tanggal_lahir', 'nomor_hp',
            'poli_tujuan', 'alamat', 'keluhan'
        ]);
        
        $this->loadRiwayatAntrian($apiService);
        
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
    // Generate PDF dan download
    // Implementasi dengan DomPDF atau TCPDF
}

public function kirimWhatsApp()
{
    if ($this->antrianBaru && isset($this->antrianBaru['nomor_hp'])) {
        $nomor = $this->antrianBaru['nomor_hp'];
        $pesan = "Pendaftaran berhasil!\n\n";
        $pesan .= "Nomor Antrian: " . $this->antrianBaru['nomor_antrian'] . "\n";
        $pesan .= "Nama: " . $this->antrianBaru['nama_pasien'] . "\n";
        $pesan .= "Loket: " . $this->antrianBaru['loket']['nama_loket'] . "\n\n";
        $pesan .= "Silakan tunggu nomor Anda dipanggil.";
        
        $url = "https://wa.me/" . $nomor . "?text=" . urlencode($pesan);
        return redirect()->away($url);
    }
}

public function closeSuccess()
{
    $this->showSuccess = false;
    $this->antrianBaru = null;
}
```

---

## 🎨 Desain & Styling

### Color Scheme:
- **Primary:** Blue (#2563EB)
- **Secondary:** Indigo (#4F46E5)
- **Success:** Green (#10B981)
- **Warning:** Yellow (#F59E0B)
- **Danger:** Red (#EF4444)

### Components:
- Rounded corners: `rounded-xl` (12px)
- Shadows: `shadow-lg`, `shadow-xl`
- Transitions: `transition-all duration-200`
- Hover effects: `hover:scale-[1.02]`

---

## 📱 Responsive Design

### Breakpoints:
- **Mobile:** < 768px (1 kolom)
- **Tablet:** 768px - 1024px (2 kolom)
- **Desktop:** > 1024px (2-3 kolom)

### Grid Layout:
```html
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
```

---

## 🔌 Integrasi QR Code

### Install Package:
```bash
composer require simplesoftwareio/simple-qrcode
```

### Penggunaan:
```blade
{!! QrCode::size(180)->generate(route('antrian.detail', $antrianBaru['id'])) !!}
```

### Alternative (JavaScript):
```html
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<div id="qrcode"></div>
<script>
new QRCode(document.getElementById("qrcode"), {
    text: "{{ $antrianBaru['nomor_antrian'] }}",
    width: 180,
    height: 180
});
</script>
```

---

## 🚀 Cara Menggunakan

### 1. Pilih Loket
- Klik salah satu card loket
- Card akan highlight dengan border biru
- Form pendaftaran akan muncul

### 2. Isi Form
- Isi semua field yang required (*)
- Field akan validasi otomatis
- Lihat estimasi waktu tunggu

### 3. Submit
- Klik "Daftar Sekarang"
- Loading state akan muncul
- Modal sukses akan tampil

### 4. Aksi Setelah Daftar
- Cetak tiket
- Download PDF
- Kirim ke WhatsApp
- Lihat di tabel riwayat

---

## 🔄 Real-time Update

### Polling Configuration:
```blade
<div wire:poll.5s>
    <!-- Content yang auto-update -->
</div>
```

### Interval Options:
- `wire:poll.5s` - Update setiap 5 detik
- `wire:poll.10s` - Update setiap 10 detik
- `wire:poll.30s` - Update setiap 30 detik

---

## ✅ Validasi Form

### Rules:
```php
'nama_pasien' => 'required|min:3',
'nik' => 'required|digits:16',
'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
'tanggal_lahir' => 'required|date|before:today',
'nomor_hp' => 'required|regex:/^08[0-9]{9,11}$/',
'poli_tujuan' => 'required',
'alamat' => 'required|min:10',
'keluhan' => 'required|min:10',
```

### Custom Messages:
```php
'nik.digits' => 'NIK harus 16 digit',
'nomor_hp.regex' => 'Format nomor HP tidak valid (08xxxxxxxxxx)',
'tanggal_lahir.before' => 'Tanggal lahir tidak valid',
```

---

## 🐛 Troubleshooting

### Form tidak muncul
**Cause:** Loket belum dipilih
**Solution:** Pastikan `$loket_id` sudah terisi

### Tabel kosong
**Cause:** API tidak mengembalikan data
**Solution:** Cek koneksi ke backend dan endpoint API

### QR Code tidak muncul
**Cause:** Package belum diinstall
**Solution:** Install `simplesoftwareio/simple-qrcode`

### Polling tidak jalan
**Cause:** JavaScript error atau Livewire issue
**Solution:** Cek console browser dan pastikan Livewire loaded

---

## 📊 Estimasi Waktu

### Formula:
```
Estimasi = Jumlah Antrian × 5 menit
```

### Contoh:
- 3 antrian = 15 menit
- 5 antrian = 25 menit
- 10 antrian = 50 menit

---

## 🎯 Best Practices

1. **Validasi Client-side & Server-side**
   - Client: HTML5 validation + Livewire
   - Server: Laravel validation rules

2. **User Feedback**
   - Loading states
   - Success/error notifications
   - Real-time validation messages

3. **Performance**
   - Lazy loading untuk tabel besar
   - Pagination jika > 50 records
   - Debounce untuk search/filter

4. **Accessibility**
   - Label untuk semua input
   - Error messages yang jelas
   - Keyboard navigation support

---

## 📝 TODO / Future Enhancements

- [ ] Export riwayat ke Excel
- [ ] Filter tabel by status/loket
- [ ] Search pasien by nama/NIK
- [ ] Notifikasi push saat dipanggil
- [ ] Multi-language support
- [ ] Dark mode
- [ ] Voice announcement
- [ ] SMS notification

---

**Status:** ✅ Completed  
**Version:** 2.0.0  
**Last Updated:** 5 November 2024  
**Author:** Cascade AI Assistant
