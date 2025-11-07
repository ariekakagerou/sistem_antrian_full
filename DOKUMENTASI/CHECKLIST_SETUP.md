# ✅ Checklist Setup Sistem Antrian Rumah Sakit

Gunakan checklist ini untuk memastikan sistem sudah siap digunakan.

## 📦 Prasyarat

- [ ] PHP >= 8.1 terinstall
- [ ] Composer terinstall
- [ ] Node.js & NPM terinstall
- [ ] Git terinstall (opsional)

### Cara Cek Versi:
```bash
php --version
composer --version
node --version
npm --version
```

---

## 🔧 Setup Backend

### 1. Dependencies
- [ ] Masuk ke folder `backend-laravel`
- [ ] Jalankan `composer install`
- [ ] Tunggu hingga selesai (tanpa error)

### 2. Environment
- [ ] Copy `.env.example` ke `.env`
- [ ] Jalankan `php artisan key:generate`
- [ ] Cek file `.env` sudah ada `APP_KEY`

### 3. Database
- [ ] Jalankan `php artisan migrate`
- [ ] Jalankan `php artisan db:seed` (opsional, untuk data dummy)
- [ ] Pastikan tidak ada error

### 4. Test Backend
- [ ] Jalankan `php artisan serve`
- [ ] Buka browser: `http://localhost:8000/api/loket`
- [ ] Harus muncul response JSON (bukan error)

---

## 🎨 Setup Frontend

### 1. Dependencies
- [ ] Masuk ke folder `frontend-livewire`
- [ ] Jalankan `composer install`
- [ ] Jalankan `npm install`
- [ ] Tunggu hingga selesai (tanpa error)

### 2. Environment
- [ ] Copy `.env.example` ke `.env`
- [ ] Jalankan `php artisan key:generate`
- [ ] Edit `.env`, pastikan:
  ```
  API_BASE_URL=http://localhost:8000/api
  ```

### 3. Database Frontend
- [ ] Buat file `database/database.sqlite` (jika belum ada)
- [ ] Jalankan `php artisan migrate`
- [ ] Pastikan tidak ada error

### 4. Test Frontend
- [ ] Jalankan `php artisan serve --port=8001` (Terminal 1)
- [ ] Jalankan `npm run dev` (Terminal 2)
- [ ] Buka browser: `http://localhost:8001`
- [ ] Harus muncul halaman form pendaftaran

---

## 🧪 Test Integrasi

### Test 1: Lihat Loket
- [ ] Buka `http://localhost:8001`
- [ ] Dropdown "Pilih Loket" harus terisi dengan data loket
- [ ] Jika kosong, cek koneksi ke backend

### Test 2: Daftar Antrian
- [ ] Isi form pendaftaran lengkap
- [ ] Klik "Daftar Antrian"
- [ ] Harus muncul modal dengan nomor antrian
- [ ] Jika error, cek console browser (F12)

### Test 3: Login Petugas
- [ ] Buka `http://localhost:8001/petugas`
- [ ] Login dengan:
  - Email: `petugas@example.com`
  - Password: `password`
- [ ] Harus berhasil masuk ke dashboard
- [ ] Jika gagal, pastikan sudah jalankan `php artisan db:seed` di backend

### Test 4: Display Antrian
- [ ] Buka `http://localhost:8001/display`
- [ ] Harus muncul tampilan display dengan loket-loket
- [ ] Cek apakah auto-refresh berjalan (lihat indicator hijau)

---

## 🚀 Test Batch Files

### Test START_ALL.bat
- [ ] Double-click `START_ALL.bat`
- [ ] Harus muncul 3 terminal window
- [ ] Browser otomatis terbuka ke `http://localhost:8001`
- [ ] Halaman berhasil dimuat tanpa error

### Test Individual Batch Files
- [ ] `start-backend.bat` → Backend berjalan di port 8000
- [ ] `start-frontend.bat` → Frontend berjalan di port 8001
- [ ] `start-vite.bat` → Vite compile assets

---

## 🔍 Troubleshooting Checklist

### Backend Tidak Bisa Diakses
- [ ] Cek apakah `php artisan serve` berjalan tanpa error
- [ ] Cek port 8000 tidak digunakan aplikasi lain
- [ ] Cek firewall tidak memblokir port 8000

### Frontend Tidak Connect ke Backend
- [ ] Backend sudah berjalan di `http://localhost:8000`
- [ ] File `.env` frontend sudah benar: `API_BASE_URL=http://localhost:8000/api`
- [ ] Cek file `config/cors.php` di backend sudah benar

### Dropdown Loket Kosong
- [ ] Backend sudah seeding: `php artisan db:seed`
- [ ] Cek response API: `http://localhost:8000/api/loket`
- [ ] Cek console browser (F12) untuk error

### Error 500 / 404
- [ ] Clear cache: `php artisan cache:clear`
- [ ] Clear config: `php artisan config:clear`
- [ ] Restart semua service

### Vite Error
- [ ] Hapus folder `node_modules`
- [ ] Jalankan ulang `npm install`
- [ ] Jalankan `npm run dev`

---

## ✨ Final Check

### Semua Service Berjalan
- [ ] Backend API: `http://localhost:8000` ✅
- [ ] Frontend: `http://localhost:8001` ✅
- [ ] Vite Dev Server: Running ✅

### Semua Halaman Berfungsi
- [ ] Halaman Pasien (`/`) ✅
- [ ] Dashboard Petugas (`/petugas`) ✅
- [ ] Display Antrian (`/display`) ✅

### Fitur Berfungsi
- [ ] Pasien bisa daftar antrian ✅
- [ ] Petugas bisa login ✅
- [ ] Petugas bisa panggil antrian ✅
- [ ] Petugas bisa selesaikan antrian ✅
- [ ] Display menampilkan antrian aktif ✅
- [ ] Auto-refresh berjalan ✅

---

## 🎉 Selesai!

Jika semua checklist di atas sudah ✅, maka sistem sudah siap digunakan!

### Next Steps:
1. Buat user petugas tambahan (jika perlu)
2. Tambah data loket sesuai kebutuhan
3. Test dengan skenario real
4. Deploy ke production (jika sudah siap)

---

**Catatan:** Simpan checklist ini untuk referensi troubleshooting di masa depan.
