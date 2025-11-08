# Troubleshooting Display Antrian

## ❌ Error: Maximum execution time of 60 seconds exceeded

### Penyebab
Error ini terjadi ketika halaman `/display` tidak bisa mengakses backend API dalam waktu yang ditentukan (60 detik).

### Kemungkinan Penyebab:
1. **Backend Laravel tidak berjalan**
2. **Backend berjalan di port yang salah**
3. **Koneksi timeout ke backend**
4. **Backend terlalu lambat merespon**

---

## ✅ Solusi yang Sudah Diterapkan

### 1. **Perbaikan di `DisplayAntrian.php`**

File: `frontend-livewire/app/Livewire/DisplayAntrian.php`

**Perubahan:**
- ✅ Tambah try-catch di method `mount()`
- ✅ Tambah try-catch per loket di `loadData()`
- ✅ Tambah error logging
- ✅ Set default values jika API gagal
- ✅ Tidak throw exception, hanya log error

**Hasil:**
- Halaman tidak crash meskipun backend tidak tersedia
- Menampilkan pesan error yang informatif
- Tetap bisa refresh manual

---

### 2. **Perbaikan di `ApiService.php`**

File: `frontend-livewire/app/Services/ApiService.php`

**Perubahan:**
```php
// SEBELUM
->timeout(30)
->retry(3, 100)

// SESUDAH
->timeout(10)          // Kurangi timeout ke 10 detik
->connectTimeout(5)    // Timeout koneksi 5 detik
->retry(2, 100)        // Retry 2x saja
```

**Hasil:**
- Request lebih cepat timeout (10 detik vs 60 detik)
- Tidak menunggu terlalu lama
- Lebih cepat menampilkan error

---

### 3. **Perbaikan di View `display-antrian.blade.php`**

File: `frontend-livewire/resources/views/livewire/display-antrian.blade.php`

**Perubahan:**
- ✅ Tampilkan pesan error yang jelas
- ✅ Tampilkan URL backend yang digunakan
- ✅ Tambah tombol "Coba Lagi"

**Hasil:**
- User tahu kenapa display tidak muncul
- User tahu harus jalankan backend di mana
- User bisa refresh manual

---

## 🔧 Cara Mengatasi Error

### Step 1: Pastikan Backend Berjalan

```bash
# Buka terminal di folder backend-laravel
cd backend-laravel

# Jalankan backend
php artisan serve
```

**Expected Output:**
```
Server running on [http://127.0.0.1:8000]
```

---

### Step 2: Cek Konfigurasi Frontend

File: `frontend-livewire/.env`

```env
API_BASE_URL=http://127.0.0.1:8000/api
```

**Pastikan:**
- ✅ URL sesuai dengan backend yang berjalan
- ✅ Port sesuai (default: 8000)
- ✅ Tidak ada typo

---

### Step 3: Test Backend API

Buka browser atau Postman:

```
GET http://127.0.0.1:8000/api/loket
```

**Expected Response:**
```json
{
  "success": true,
  "data": [...]
}
```

Jika error 404 atau connection refused → Backend tidak berjalan!

---

### Step 4: Restart Frontend

```bash
# Buka terminal di folder frontend-livewire
cd frontend-livewire

# Stop server (Ctrl+C)
# Jalankan ulang
php artisan serve --port=8001
```

---

### Step 5: Clear Cache

```bash
cd frontend-livewire

# Clear semua cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

---

### Step 6: Akses Display

Buka browser:
```
http://127.0.0.1:8001/display
```

**Jika Berhasil:**
- ✅ Tampil loket dan antrian
- ✅ Auto refresh setiap 5 detik
- ✅ Waktu update real-time

**Jika Masih Error:**
- ❌ Tampil pesan "Backend API Tidak Tersedia"
- ❌ Cek log di `storage/logs/laravel.log`

---

## 🐛 Debug Mode

### Cek Log Error

File: `frontend-livewire/storage/logs/laravel.log`

Cari error terbaru:
```
[2024-11-07 20:55:00] local.ERROR: DisplayAntrian loadData error: ...
```

### Common Errors:

#### 1. Connection refused
```
cURL error 7: Failed to connect to 127.0.0.1 port 8000
```
**Solusi:** Backend tidak berjalan, jalankan `php artisan serve`

#### 2. Connection timeout
```
cURL error 28: Operation timed out after 10000 milliseconds
```
**Solusi:** Backend terlalu lambat, cek database atau query

#### 3. 404 Not Found
```
404 Not Found
```
**Solusi:** Route API tidak ada, cek `backend-laravel/routes/api.php`

---

## 📊 Monitoring

### Cek Status Backend

```bash
# Di terminal backend
php artisan route:list --path=api

# Expected output:
GET|HEAD  api/loket ..................... loket.index
GET|HEAD  api/antrian ................... antrian.index
```

### Cek Status Frontend

```bash
# Di terminal frontend
php artisan route:list --path=display

# Expected output:
GET|HEAD  display ...................... Livewire\DisplayAntrian
```

---

## ⚡ Performance Tips

### 1. Kurangi Polling Interval

File: `display-antrian.blade.php`

```javascript
// SEBELUM (5 detik)
setInterval(() => { 
    if (autoRefresh) {
        $wire.refresh();
    }
}, 5000);

// SESUDAH (10 detik) - Lebih ringan
setInterval(() => { 
    if (autoRefresh) {
        $wire.refresh();
    }
}, 10000);
```

### 2. Cache Loket Data

Jika loket jarang berubah, bisa di-cache:

```php
// Di DisplayAntrian.php
$this->lokets = Cache::remember('lokets', 60, function() use ($apiService) {
    $response = $apiService->getLokets();
    return $response['data'] ?? [];
});
```

---

## 🎯 Checklist Troubleshooting

```
✅ Backend berjalan di http://127.0.0.1:8000
✅ Frontend berjalan di http://127.0.0.1:8001
✅ API_BASE_URL di .env sudah benar
✅ Test API /loket berhasil
✅ Test API /antrian berhasil
✅ Cache sudah di-clear
✅ Log tidak ada error
✅ Display bisa diakses
```

---

## 📞 Bantuan Lebih Lanjut

Jika masih error setelah semua langkah di atas:

1. **Cek log lengkap:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Test manual API:**
   ```bash
   curl http://127.0.0.1:8000/api/loket
   ```

3. **Restart semua service:**
   ```bash
   # Stop backend & frontend (Ctrl+C)
   # Jalankan ulang keduanya
   ```

---

## ✅ Summary

**Error Fixed:**
- ✅ Maximum execution time exceeded → Solved
- ✅ Timeout handling → Implemented
- ✅ Error message → User-friendly
- ✅ Fallback mechanism → Added

**Best Practice:**
- ✅ Always run backend before frontend
- ✅ Check API_BASE_URL configuration
- ✅ Monitor logs for errors
- ✅ Use appropriate timeout values

**Next Steps:**
- Test display dengan data real
- Monitor performance
- Adjust polling interval jika perlu
