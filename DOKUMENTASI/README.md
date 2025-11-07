# 🏥 Sistem Antrian Rumah Sakit

Sistem manajemen antrian rumah sakit berbasis web dengan Laravel (Backend API) dan Livewire (Frontend).

## 📋 Deskripsi

Sistem ini memungkinkan:
- **Pasien** mendaftar antrian secara online
- **Petugas** mengelola dan memanggil antrian
- **Display Monitor** menampilkan nomor antrian yang sedang dipanggil

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

## 📄 License

Project ini dibuat untuk keperluan internal rumah sakit.

---

**Dibuat dengan ❤️ menggunakan Laravel & Livewire**

**Version:** 1.0.0  
**Last Updated:** 2024
