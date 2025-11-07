# 🏥 Sistem Antrian Rumah Sakit

Sistem manajemen antrian rumah sakit berbasis web dengan Laravel (Backend API) dan Livewire (Frontend).

## 📋 Deskripsi

Sistem ini memungkinkan:
- **Pasien** mendaftar antrian secara online
- **Petugas** mengelola dan memanggil antrian
- **Display Monitor** menampilkan nomor antrian yang sedang dipanggil

---

## 🆕 Setup Pertama Kali di Laptop/PC Baru

### ✅ Checklist Setup Cepat

Ikuti langkah-langkah ini secara berurutan:

- [ ] **Step 1**: Clone repository dari GitHub
- [ ] **Step 2**: Install prasyarat (PHP, Composer, Node.js, Git)
- [ ] **Step 3**: Setup Backend Laravel
- [ ] **Step 4**: Setup Frontend Livewire
- [ ] **Step 5**: (Opsional) Setup Google OAuth
- [ ] **Step 6**: Jalankan aplikasi
- [ ] **Step 7**: Akses aplikasi di browser
- [ ] **Step 8**: Login dengan akun default

**⏱️ Estimasi waktu:** 15-30 menit (tergantung kecepatan internet)

---

### 1️⃣ Clone Repository dari GitHub

```bash
# Clone repository
git clone https://github.com/ariekakagerou/sistem_antrian_full.git

# Masuk ke folder project
cd sistem_antrian_full
```

### 2️⃣ Prasyarat yang Harus Diinstall

Pastikan sudah terinstall:
- ✅ **PHP >= 8.1** - [Download PHP](https://www.php.net/downloads)
- ✅ **Composer** - [Download Composer](https://getcomposer.org/download/)
- ✅ **Node.js & NPM** - [Download Node.js](https://nodejs.org/)
- ✅ **MySQL** atau **SQLite** - [Download MySQL](https://dev.mysql.com/downloads/)
- ✅ **Git** - [Download Git](https://git-scm.com/downloads)

**Cek versi yang terinstall:**
```bash
php --version
composer --version
node --version
npm --version
git --version
```

### 3️⃣ Setup Backend Laravel

```bash
# Masuk ke folder backend
cd backend-laravel

# Install dependencies PHP
composer install

# Copy file environment
copy .env.example .env
# Untuk Linux/Mac: cp .env.example .env

# Generate application key
php artisan key:generate

# Jalankan migration database
php artisan migrate

# Seed data awal (loket & user)
php artisan db:seed
```

**Edit file `.env` backend jika perlu:**
```env
DB_CONNECTION=sqlite
# Atau jika pakai MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=antrian_rs
# DB_USERNAME=root
# DB_PASSWORD=
```

### 4️⃣ Setup Frontend Livewire

```bash
# Kembali ke root folder
cd ..

# Masuk ke folder frontend
cd frontend-livewire

# Install dependencies PHP
composer install

# Install dependencies Node.js
npm install

# Copy file environment
copy .env.example .env
# Untuk Linux/Mac: cp .env.example .env

# Generate application key
php artisan key:generate

# Jalankan migration (jika ada)
php artisan migrate
```

**Edit file `.env` frontend:**
```env
APP_NAME="Sistem Antrian RS"
APP_URL=http://localhost:8001

# Pastikan API_BASE_URL mengarah ke backend
API_BASE_URL=http://localhost:8000/api

# Database (bisa pakai SQLite atau MySQL)
DB_CONNECTION=sqlite
```

### 5️⃣ Setup Google OAuth (Opsional)

Jika ingin menggunakan login Google:

1. Buat project di [Google Cloud Console](https://console.cloud.google.com/)
2. Enable Google+ API
3. Buat OAuth 2.0 credentials
4. Tambahkan ke `.env` frontend:

```env
GOOGLE_CLIENT_ID=your-client-id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your-client-secret
GOOGLE_REDIRECT_URI=http://localhost:8001/auth/google/callback
```

**Lihat [GOOGLE_OAUTH_SETUP.md](GOOGLE_OAUTH_SETUP.md) untuk panduan lengkap.**

### 6️⃣ Jalankan Aplikasi

**Cara Termudah - Gunakan Batch File:**

Kembali ke root folder dan double-click:
```
START_ALL.bat
```

**Atau Manual (3 Terminal):**

**Terminal 1 - Backend:**
```bash
cd backend-laravel
php artisan serve
```

**Terminal 2 - Frontend:**
```bash
cd frontend-livewire
php artisan serve --port=8001
```

**Terminal 3 - Vite (Asset Compiler):**
```bash
cd frontend-livewire
npm run dev
```

### 7️⃣ Akses Aplikasi

Buka browser dan akses:
- 🏠 **Homepage/Pendaftaran**: http://localhost:8001
- 👨‍⚕️ **Dashboard Petugas**: http://localhost:8001/petugas
- 📺 **Display Monitor**: http://localhost:8001/display
- 🔌 **Backend API**: http://localhost:8000/api

### 8️⃣ Login Petugas (Default)

```
Email: petugas@example.com
Password: password
```

### ⚠️ Troubleshooting Setup Awal

**❌ Error: "composer: command not found"**
```bash
# Install Composer terlebih dahulu
# Download dari: https://getcomposer.org/download/
```

**❌ Error: "npm: command not found"**
```bash
# Install Node.js terlebih dahulu
# Download dari: https://nodejs.org/
```

**❌ Error: "SQLSTATE[HY000] [1045] Access denied"**
```bash
# Jika pakai MySQL, pastikan username & password benar di .env
# Atau gunakan SQLite (lebih mudah):
DB_CONNECTION=sqlite
```

**❌ Error: "Port 8000 already in use"**
```bash
# Gunakan port lain
php artisan serve --port=8080

# Jangan lupa update .env frontend:
API_BASE_URL=http://localhost:8080/api
```

**❌ Error: "Class 'ApiService' not found"**
```bash
# Clear cache dan autoload
cd frontend-livewire
composer dump-autoload
php artisan cache:clear
php artisan config:clear
```

**❌ Frontend tidak bisa connect ke Backend**
```bash
# 1. Pastikan backend sudah running di port 8000
# 2. Cek file frontend-livewire/.env:
API_BASE_URL=http://localhost:8000/api

# 3. Clear cache
php artisan cache:clear
php artisan config:clear
```

**❌ Error: "Vite manifest not found"**
```bash
# Pastikan Vite sudah running
cd frontend-livewire
npm run dev

# Atau build untuk production:
npm run build
```

---

## 🎯 Fitur Utama

### 1. Pendaftaran Antrian (Pasien)
- Form pendaftaran online
- Pilih loket sesuai kebutuhan
- Mendapatkan nomor antrian otomatis
- Notifikasi nomor antrian

### 2. Dashboard Petugas
- Login untuk petugas (Email/Password atau Google OAuth)
- Kelola antrian per loket
- Panggil antrian berikutnya
- Selesaikan antrian
- Statistik real-time

### 3. Display Antrian
- Tampilan untuk monitor/TV
- Menampilkan antrian aktif per loket
- Auto-refresh setiap 5 detik
- Desain modern dan responsif

## 🏗️ Struktur Project

```
sistem antrian rumah sakit/
│
├── backend-laravel/          # Backend API Laravel
│   ├── app/
│   │   ├── Http/Controllers/Api/
│   │   ├── Models/
│   │   └── Services/
│   ├── database/
│   └── routes/api.php
│
├── frontend-livewire/        # Frontend Livewire
│   ├── app/
│   │   ├── Livewire/
│   │   │   ├── PasienAntrian.php
│   │   │   ├── PetugasLoket.php
│   │   │   └── DisplayAntrian.php
│   │   └── Services/
│   │       └── ApiService.php
│   └── resources/views/
│       ├── components/layout.blade.php
│       └── livewire/
│
├── START_ALL.bat             # Jalankan semua service sekaligus
├── start-backend.bat         # Jalankan backend saja
├── start-frontend.bat        # Jalankan frontend saja
├── start-vite.bat            # Jalankan vite saja
└── PANDUAN_SETUP.md          # Panduan lengkap setup
```

## 🚀 Quick Start

### Cara Termudah (Recommended)

**Double-click file:** `START_ALL.bat`

File ini akan otomatis:
1. Menjalankan Backend API (Port 8000)
2. Menjalankan Frontend Livewire (Port 8001)
3. Menjalankan Vite Dev Server
4. Membuka browser ke `http://localhost:8001`

### Cara Manual

**Terminal 1 - Backend:**
```bash
cd backend-laravel
php artisan serve
```

**Terminal 2 - Frontend:**
```bash
cd frontend-livewire
php artisan serve --port=8001
```

**Terminal 3 - Vite:**
```bash
cd frontend-livewire
npm run dev
```

## 📦 Instalasi

### Prasyarat
- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL/SQLite

### Setup Backend
```bash
cd backend-laravel
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
```

### Setup Frontend
```bash
cd frontend-livewire
composer install
npm install
copy .env.example .env
php artisan key:generate
php artisan migrate
```

**Lihat [PANDUAN_SETUP.md](PANDUAN_SETUP.md) untuk panduan lengkap.**

## 🌐 URL Akses

| Service | URL | Deskripsi |
|---------|-----|-----------|
| Backend API | http://localhost:8000 | REST API |
| Frontend | http://localhost:8001 | Aplikasi Web |
| Pendaftaran | http://localhost:8001 | Form pasien |
| Dashboard Petugas | http://localhost:8001/petugas | Kelola antrian |
| Display Monitor | http://localhost:8001/display | Tampilan TV |

## 🔧 Teknologi

### Backend
- Laravel 11
- MySQL/SQLite
- Laravel Sanctum (Authentication)
- RESTful API

### Frontend
- Laravel 11
- Livewire 3
- Tailwind CSS
- Alpine.js
- Font Awesome

## 📱 Screenshot

### Halaman Pendaftaran Pasien
Form modern untuk pendaftaran antrian dengan validasi real-time.

### Dashboard Petugas
Interface lengkap untuk mengelola antrian dengan statistik real-time.

### Display Monitor
Tampilan fullscreen untuk monitor/TV dengan auto-refresh.

## 🔐 Default Credentials

Untuk login petugas (setelah seeding):
```
Email: petugas@example.com
Password: password
```

## 📖 Dokumentasi

- [PANDUAN_SETUP.md](PANDUAN_SETUP.md) - Panduan setup lengkap
- [GOOGLE_OAUTH_SETUP.md](GOOGLE_OAUTH_SETUP.md) - Setup Google OAuth login
- [frontend-livewire/README_SISTEM_ANTRIAN.md](frontend-livewire/README_SISTEM_ANTRIAN.md) - Dokumentasi frontend

## 🐛 Troubleshooting

### Backend tidak bisa diakses
```bash
# Pastikan port 8000 tidak digunakan
netstat -ano | findstr :8000

# Restart backend
cd backend-laravel
php artisan serve
```

### Frontend tidak connect ke backend
1. Pastikan backend sudah berjalan
2. Cek `frontend-livewire/.env`:
   ```
   API_BASE_URL=http://localhost:8000/api
   ```
3. Clear cache:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   ```

### CORS Error
Edit `backend-laravel/config/cors.php`:
```php
'allowed_origins' => ['http://localhost:8001'],
```

## 🔄 Development

### Update Dependencies
```bash
# Backend
cd backend-laravel
composer update

# Frontend
cd frontend-livewire
composer update
npm update
```

### Database Migration
```bash
# Backend
cd backend-laravel
php artisan migrate:fresh --seed

# Frontend
cd frontend-livewire
php artisan migrate:fresh
```

## 📝 API Endpoints

### Loket
- `GET /api/loket` - List semua loket
- `GET /api/loket/{id}` - Detail loket

### Antrian
- `GET /api/antrian` - List semua antrian
- `GET /api/antrian/loket/{id}` - Antrian per loket
- `POST /api/antrian` - Buat antrian baru
- `POST /api/antrian/{id}/panggil` - Panggil antrian (auth)
- `POST /api/antrian/{id}/selesai` - Selesaikan antrian (auth)

### Authentication
- `POST /api/login` - Login petugas
- `POST /api/logout` - Logout
- `GET /api/user` - Get user info

## 🎯 Workflow

1. **Pasien** mendaftar via form → Dapat nomor antrian
2. **Petugas** login → Pilih loket → Panggil antrian
3. **Display** menampilkan nomor yang dipanggil
4. **Petugas** selesaikan antrian → Panggil berikutnya

## 💡 Tips

- Gunakan `START_ALL.bat` untuk kemudahan
- Bookmark URL yang sering diakses
- Gunakan F11 untuk fullscreen display
- Display auto-refresh setiap 5 detik

## 📞 Support

Jika ada masalah atau pertanyaan, silakan:
1. Baca [PANDUAN_SETUP.md](PANDUAN_SETUP.md)
2. Cek bagian Troubleshooting
3. Hubungi tim development

## 🔄 Update & Changelog

### Version 1.1.0 (7 November 2025)

**🎯 Perbaikan Navigasi Menu Petugas**

**Perubahan Backend (`PetugasLoket.php`):**
- ✅ Fix dependency injection untuk `ApiService`
- ✅ Tambah method `changeMenu()` untuk navigasi menu
- ✅ Auto-load data antrian saat pilih loket
- ✅ Perbaikan semua method untuk tidak menerima parameter `ApiService`

**Perubahan Frontend:**
- ✅ Ubah navigasi dari `<a>` tag ke `<button>` dengan `wire:click`
- ✅ Implementasi `@switch` untuk render konten menu (lebih efisien)
- ✅ Tambah loading indicator saat menu berubah
- ✅ Tambah `wire:key` untuk tracking Livewire yang lebih baik
- ✅ Perbaikan event listener Livewire v3
- ✅ Hapus dependency Alpine.js yang tidak digunakan
- ✅ Tambah debug info untuk troubleshooting

**Struktur Folder:**
- 📁 Semua dokumentasi dipindahkan ke folder `DOKUMENTASI/`
- 📁 Struktur project lebih rapi dan terorganisir

**File yang Dimodifikasi:**
1. `frontend-livewire/app/Livewire/PetugasLoket.php` (+46 baris)
2. `frontend-livewire/resources/views/components/layout.blade.php` (+9 baris)
3. `frontend-livewire/resources/views/components/petugas/sidebar.blade.php` (+30 baris)
4. `frontend-livewire/resources/views/livewire/petugas-loket.blade.php` (+116 baris)
5. `frontend-livewire/package-lock.json` (file baru)

**Cara Update dari Versi Sebelumnya:**
```bash
# Pull update terbaru
git pull origin main

# Update dependencies frontend
cd frontend-livewire
composer update
npm install

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Restart server
```

---

## 📄 License

Project ini dibuat untuk keperluan internal rumah sakit.

---

**Dibuat dengan ❤️ menggunakan Laravel & Livewire**

**Version:** 1.1.0  
**Last Updated:** 7 November 2025  
**Repository:** https://github.com/ariekakagerou/sistem_antrian_full.git
