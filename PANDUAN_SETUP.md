# 🏥 Panduan Setup Sistem Antrian Rumah Sakit

Panduan lengkap untuk menjalankan sistem antrian rumah sakit (Backend + Frontend).

## 📁 Struktur Project

```
sistem antrian rumah sakit/
├── backend-laravel/          # Backend API Laravel
└── frontend-livewire/        # Frontend Livewire
```

## 🚀 Setup Backend (Laravel API)

### 1. Masuk ke Folder Backend
```bash
cd "c:\Users\LENOVO\sistem antrian rumah sakit\backend-laravel"
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Konfigurasi Environment
```bash
# Copy file .env.example ke .env
copy .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Setup Database
```bash
# Jalankan migrasi
php artisan migrate

# (Opsional) Jalankan seeder untuk data dummy
php artisan db:seed
```

### 5. Jalankan Backend Server
```bash
php artisan serve
```

Backend akan berjalan di: **http://localhost:8000**

---

## 🎨 Setup Frontend (Livewire)

### 1. Buka Terminal Baru & Masuk ke Folder Frontend
```bash
cd "c:\Users\LENOVO\sistem antrian rumah sakit\frontend-livewire"
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Konfigurasi Environment
```bash
# Copy file .env.example ke .env
copy .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Edit File .env
Buka file `.env` dan pastikan konfigurasi berikut:

```env
APP_NAME="Sistem Antrian RS"
APP_URL=http://localhost:8001

# Database (SQLite)
DB_CONNECTION=sqlite

# Backend API URL
API_BASE_URL=http://localhost:8000/api
```

### 5. Setup Database Frontend
```bash
# Buat file database SQLite
type nul > database\database.sqlite

# Jalankan migrasi
php artisan migrate
```

### 6. Jalankan Frontend Server

**Terminal 1 - Laravel Server:**
```bash
php artisan serve --port=8001
```

**Terminal 2 - Vite (Assets):**
```bash
npm run dev
```

Frontend akan berjalan di: **http://localhost:8001**

---

## ✅ Verifikasi Setup

### 1. Cek Backend API
Buka browser dan akses: `http://localhost:8000/api/loket`

Jika berhasil, akan muncul response JSON dengan daftar loket.

### 2. Cek Frontend
Buka browser dan akses: `http://localhost:8001`

Jika berhasil, akan muncul halaman form pendaftaran antrian.

---

## 🎯 Cara Menggunakan Sistem

### A. Untuk Pasien (Pendaftaran Antrian)

1. Buka: `http://localhost:8001`
2. Pilih loket yang diinginkan
3. Isi form:
   - Nama lengkap
   - Nomor HP/WhatsApp
   - Alamat
   - Keluhan
4. Klik **"Daftar Antrian"**
5. Catat nomor antrian yang muncul
6. Tunggu nomor dipanggil

### B. Untuk Petugas (Mengelola Antrian)

1. Buka: `http://localhost:8001/petugas`
2. Login dengan kredensial petugas:
   - Email: (sesuai database)
   - Password: (sesuai database)
3. Pilih loket yang akan dikelola
4. Lihat daftar antrian
5. Klik **"Panggil"** untuk memanggil antrian berikutnya
6. Layani pasien
7. Klik **"Selesai"** setelah selesai melayani

### C. Display Antrian (Monitor/TV)

1. Buka: `http://localhost:8001/display`
2. Tampilkan di monitor/TV
3. Display akan otomatis refresh setiap 5 detik
4. Menampilkan nomor antrian yang sedang dipanggil

---

## 🔧 Troubleshooting

### Problem: Backend tidak bisa diakses
**Solusi:**
```bash
# Pastikan backend server berjalan
cd "c:\Users\LENOVO\sistem antrian rumah sakit\backend-laravel"
php artisan serve
```

### Problem: Frontend tidak bisa connect ke backend
**Solusi:**
1. Pastikan backend sudah berjalan di `http://localhost:8000`
2. Cek file `.env` di frontend, pastikan `API_BASE_URL=http://localhost:8000/api`
3. Restart frontend server

### Problem: CORS Error
**Solusi:**
Edit file `backend-laravel/config/cors.php`:
```php
'allowed_origins' => ['http://localhost:8001'],
```

### Problem: Database error
**Solusi:**
```bash
# Backend
cd backend-laravel
php artisan migrate:fresh --seed

# Frontend
cd frontend-livewire
php artisan migrate:fresh
```

---

## 📝 Catatan Penting

1. **Jalankan Backend Terlebih Dahulu:** Backend harus berjalan sebelum frontend
2. **Port yang Berbeda:** 
   - Backend: `http://localhost:8000`
   - Frontend: `http://localhost:8001`
3. **Jangan Tutup Terminal:** Biarkan terminal backend dan frontend tetap berjalan
4. **Browser Cache:** Jika ada masalah tampilan, clear cache browser (Ctrl + Shift + Delete)

---

## 🔄 Menjalankan Ulang Sistem

Setiap kali ingin menjalankan sistem:

### Terminal 1 - Backend
```bash
cd "c:\Users\LENOVO\sistem antrian rumah sakit\backend-laravel"
php artisan serve
```

### Terminal 2 - Frontend Laravel
```bash
cd "c:\Users\LENOVO\sistem antrian rumah sakit\frontend-livewire"
php artisan serve --port=8001
```

### Terminal 3 - Frontend Vite
```bash
cd "c:\Users\LENOVO\sistem antrian rumah sakit\frontend-livewire"
npm run dev
```

---

## 📊 Endpoint API Backend

| Method | Endpoint | Deskripsi | Auth |
|--------|----------|-----------|------|
| GET | `/api/loket` | Ambil semua loket | No |
| GET | `/api/loket/{id}` | Detail loket | No |
| GET | `/api/antrian` | Ambil semua antrian | No |
| GET | `/api/antrian/loket/{id}` | Antrian per loket | No |
| POST | `/api/antrian` | Buat antrian baru | Yes |
| POST | `/api/antrian/{id}/panggil` | Panggil antrian | Yes |
| POST | `/api/antrian/{id}/selesai` | Selesaikan antrian | Yes |
| POST | `/api/login` | Login petugas | No |
| POST | `/api/logout` | Logout | Yes |

---

## 🎨 Halaman Frontend

| URL | Deskripsi | Akses |
|-----|-----------|-------|
| `/` | Form pendaftaran pasien | Public |
| `/petugas` | Dashboard petugas | Login Required |
| `/display` | Display monitor antrian | Public |

---

## 💡 Tips

1. **Gunakan 2-3 Terminal:** Satu untuk backend, satu untuk frontend Laravel, satu untuk Vite
2. **Bookmark URL:** Simpan URL yang sering diakses di bookmark browser
3. **Fullscreen Display:** Tekan F11 di browser untuk fullscreen mode pada display antrian
4. **Auto-refresh:** Display antrian otomatis refresh, tidak perlu manual refresh

---

## 📞 Bantuan

Jika mengalami masalah:
1. Pastikan semua dependencies sudah terinstall
2. Cek apakah port 8000 dan 8001 tidak digunakan aplikasi lain
3. Restart semua terminal dan coba lagi
4. Clear cache browser

---

**Selamat menggunakan Sistem Antrian Rumah Sakit! 🏥**
