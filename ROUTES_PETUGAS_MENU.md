# 🛣️ Routes untuk Menu Petugas

## ✅ Routes yang Sudah Dibuat

### **File:** `routes/web.php`

```php
// Petugas Routes - Main
Route::get('/petugas', PetugasLoket::class)->name('petugas.index');

// Petugas Routes - Menu (dengan parameter loket_id)
Route::get('/petugas/dashboard/{loket_id}', PetugasLoket::class)->name('petugas.dashboard');
Route::get('/petugas/daftar-antrian/{loket_id}', PetugasLoket::class)->name('petugas.daftar-antrian');
Route::get('/petugas/pemanggilan/{loket_id}', PetugasLoket::class)->name('petugas.pemanggilan');
Route::get('/petugas/riwayat/{loket_id}', PetugasLoket::class)->name('petugas.riwayat');
Route::get('/petugas/pengaturan/{loket_id}', PetugasLoket::class)->name('petugas.pengaturan');
```

---

## 📍 URL yang Tersedia

Setelah pilih loket (misalnya loket ID = 1):

| Menu | URL | Route Name |
|------|-----|------------|
| Dashboard | `/petugas/dashboard/1` | `petugas.dashboard` |
| Daftar Antrian | `/petugas/daftar-antrian/1` | `petugas.daftar-antrian` |
| Pemanggilan Pasien | `/petugas/pemanggilan/1` | `petugas.pemanggilan` |
| Riwayat Pelayanan | `/petugas/riwayat/1` | `petugas.riwayat` |
| Pengaturan Akun | `/petugas/pengaturan/1` | `petugas.pengaturan` |

---

## 🎯 Cara Kerja

### **1. Sidebar dengan Routes**

File: `components/petugas/sidebar.blade.php`

```blade
<a href="{{ $selectedLoket ? route('petugas.dashboard', $selectedLoket) : '#' }}" 
   wire:click.prevent="$set('activeMenu', 'dashboard')"
   class="...">
    <i class="fas fa-home w-5"></i>
    <span class="font-semibold">Dashboard</span>
</a>
```

**Penjelasan:**
- `href="{{ route('petugas.dashboard', $selectedLoket) }}"` - Generate URL dengan loket_id
- `wire:click.prevent` - Prevent default link, tetap pakai Livewire (SPA experience)
- URL akan berubah di browser, tapi halaman tidak reload

### **2. Component PHP Menangani Route Parameter**

File: `app/Livewire/PetugasLoket.php`

```php
public function mount(ApiService $apiService, $loket_id = null)
{
    // ...
    
    // Jika ada loket_id dari route, set otomatis
    if ($loket_id) {
        $this->selectedLoket = $loket_id;
        
        // Deteksi menu dari URL
        $currentRoute = request()->route()->getName();
        if (str_contains($currentRoute, 'dashboard')) {
            $this->activeMenu = 'dashboard';
        }
        // ...
    }
}
```

**Penjelasan:**
- Parameter `$loket_id` dari URL otomatis di-inject
- Deteksi menu aktif dari route name
- Set `$activeMenu` sesuai URL

---

## 🔄 Hybrid Approach (Best of Both Worlds)

Sistem ini menggunakan **hybrid approach**:

### ✅ **Keuntungan:**

1. **URL Berubah** - User bisa bookmark halaman tertentu
2. **Browser Back/Forward** - Berfungsi dengan baik
3. **Tidak Reload** - Tetap pakai Livewire (SPA experience)
4. **SEO Friendly** - URL yang jelas dan terstruktur

### 🎯 **Flow:**

```
User klik menu
    ↓
URL berubah (via href)
    ↓
wire:click.prevent mencegah reload
    ↓
$activeMenu berubah
    ↓
Konten berubah tanpa reload
```

---

## 🧪 Testing

### **1. Test Manual:**

1. Login sebagai petugas
2. Pilih loket (misalnya Loket 1)
3. Klik menu "Dashboard" → URL: `/petugas/dashboard/1`
4. Klik menu "Daftar Antrian" → URL: `/petugas/daftar-antrian/1`
5. Tekan tombol Back browser → Kembali ke Dashboard
6. Tekan tombol Forward → Kembali ke Daftar Antrian

### **2. Test Bookmark:**

1. Buka `/petugas/dashboard/1`
2. Bookmark halaman
3. Logout
4. Login lagi
5. Buka bookmark → Langsung ke Dashboard Loket 1

### **3. Test Direct URL:**

```
http://localhost:8001/petugas/dashboard/1
http://localhost:8001/petugas/daftar-antrian/2
http://localhost:8001/petugas/pemanggilan/3
```

---

## 🎨 Highlight Menu Aktif

Sidebar otomatis highlight menu yang aktif berdasarkan URL:

```blade
{{ $activeMenu === 'dashboard' ? 'bg-white text-indigo-700 shadow-lg' : 'text-indigo-100 hover:bg-indigo-800' }}
```

**Contoh:**
- URL: `/petugas/dashboard/1` → Menu "Dashboard" highlight putih
- URL: `/petugas/daftar-antrian/1` → Menu "Daftar Antrian" highlight putih

---

## 🔧 Customization

### **Menambah Menu Baru:**

**1. Tambah Route:**
```php
Route::get('/petugas/menu-baru/{loket_id}', PetugasLoket::class)->name('petugas.menu-baru');
```

**2. Tambah di Sidebar:**
```blade
<li>
    <a href="{{ $selectedLoket ? route('petugas.menu-baru', $selectedLoket) : '#' }}" 
       wire:click.prevent="$set('activeMenu', 'menu-baru')"
       class="...">
        <i class="fas fa-star w-5"></i>
        <span class="font-semibold">Menu Baru</span>
    </a>
</li>
```

**3. Tambah Deteksi di mount():**
```php
elseif (str_contains($currentRoute, 'menu-baru')) {
    $this->activeMenu = 'menu-baru';
}
```

**4. Tambah Konten:**
```blade
@elseif($activeMenu === 'menu-baru')
    @include('components.petugas.menu-baru')
@endif
```

---

## 📊 Perbandingan

| Fitur | Tanpa Routes | Dengan Routes |
|-------|--------------|---------------|
| URL Berubah | ❌ | ✅ |
| Bookmark | ❌ | ✅ |
| Back/Forward | ❌ | ✅ |
| Reload Halaman | ❌ | ❌ (hybrid) |
| SEO | ❌ | ✅ |
| Kompleksitas | Rendah | Sedang |

---

## 🚀 Deployment

Setelah update, jalankan:

```bash
# Clear route cache
php artisan route:clear

# Cache routes (production)
php artisan route:cache

# Restart server
php artisan serve --port=8001
```

---

## 🐛 Troubleshooting

### **Issue 1: Route not found**
**Solution:** Jalankan `php artisan route:clear`

### **Issue 2: Menu tidak highlight**
**Solution:** Cek `$activeMenu` di component PHP

### **Issue 3: URL tidak berubah**
**Solution:** Hapus `wire:click.prevent`, pakai `wire:navigate` (Livewire 3)

### **Issue 4: Halaman reload saat klik menu**
**Solution:** Pastikan ada `wire:click.prevent` di link

---

## 📝 Notes

- Routes ini **opsional** - sistem tetap berfungsi tanpa routes
- Jika tidak butuh URL berubah, bisa tetap pakai `wire:click` saja
- Hybrid approach memberikan UX terbaik

---

**Status:** ✅ Routes Created  
**Version:** 1.0.0  
**Compatible:** Livewire 3.x, Laravel 10.x
