# Sistem Antrian Rumah Sakit - Frontend Livewire

Aplikasi frontend untuk sistem antrian rumah sakit menggunakan Laravel Livewire yang terhubung dengan backend API Laravel.

## 🚀 Fitur

### 1. **Halaman Pasien** (`/`)
- Form pendaftaran antrian
- Pilih loket yang tersedia
- Input data pasien (nama, nomor HP, alamat, keluhan)
- Notifikasi nomor antrian setelah berhasil mendaftar
- Validasi form real-time

### 2. **Dashboard Petugas** (`/petugas`)
- Login untuk petugas
- Pilih loket yang akan dikelola
- Lihat daftar antrian per loket
- Panggil antrian berikutnya
- Selesaikan antrian yang sedang dilayani
- Statistik antrian (menunggu, dipanggil, selesai)
- Auto-refresh data

### 3. **Display Antrian** (`/display`)
- Tampilan untuk monitor/TV
- Menampilkan antrian yang sedang dipanggil di setiap loket
- Jumlah antrian yang menunggu
- Auto-refresh setiap 5 detik
- Tampilan fullscreen dengan desain modern

## 📋 Prasyarat

- PHP >= 8.1
- Composer
- Node.js & NPM
- Backend Laravel API sudah berjalan

## 🔧 Instalasi

### 1. Clone atau Copy Project

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

### 4. Konfigurasi API Backend

Edit file `.env` dan sesuaikan URL backend API:

```env
API_BASE_URL=http://localhost:8000/api
```

**Catatan:** Pastikan backend Laravel API sudah berjalan di `http://localhost:8000`

### 5. Setup Database (SQLite)

```bash
# Buat file database
type nul > database\database.sqlite

# Jalankan migrasi
php artisan migrate
```

### 6. Jalankan Aplikasi

**Terminal 1 - Laravel Server:**
```bash
php artisan serve --port=8001
```

**Terminal 2 - Vite (untuk assets):**
```bash
npm run dev
```

Aplikasi akan berjalan di: `http://localhost:8001`

## 🌐 Endpoint & Halaman

| URL | Deskripsi | Akses |
|-----|-----------|-------|
| `/` | Form pendaftaran antrian pasien | Public |
| `/petugas` | Dashboard petugas untuk mengelola antrian | Login Required |
| `/display` | Display antrian untuk monitor/TV | Public |

## 🔌 Integrasi dengan Backend API

Aplikasi ini menggunakan `ApiService` untuk berkomunikasi dengan backend Laravel API.

### Endpoint API yang Digunakan:

#### Loket
- `GET /api/loket` - Ambil semua loket
- `GET /api/loket/{id}` - Ambil detail loket

#### Antrian
- `GET /api/antrian` - Ambil semua antrian
- `GET /api/antrian/loket/{loketId}` - Ambil antrian per loket
- `POST /api/antrian` - Buat antrian baru
- `POST /api/antrian/{id}/panggil` - Panggil antrian (perlu auth)
- `POST /api/antrian/{id}/selesai` - Selesaikan antrian (perlu auth)

#### Authentication
- `POST /api/login` - Login petugas
- `POST /api/logout` - Logout petugas
- `GET /api/user` - Get user yang sedang login

## 🎨 Teknologi yang Digunakan

- **Laravel 11** - PHP Framework
- **Livewire 3** - Full-stack framework untuk Laravel
- **Tailwind CSS** - Utility-first CSS framework
- **Alpine.js** - Lightweight JavaScript framework
- **Font Awesome** - Icon library

## 📱 Komponen Livewire

### 1. PasienAntrian
**File:**
- `app/Livewire/PasienAntrian.php`
- `resources/views/livewire/pasien-antrian.blade.php`

**Fungsi:**
- Menampilkan form pendaftaran
- Validasi input
- Kirim data ke API
- Tampilkan modal sukses dengan nomor antrian

### 2. PetugasLoket
**File:**
- `app/Livewire/PetugasLoket.php`
- `resources/views/livewire/petugas-loket.blade.php`

**Fungsi:**
- Login petugas
- Pilih loket
- Kelola antrian (panggil, selesai)
- Refresh data antrian

### 3. DisplayAntrian
**File:**
- `app/Livewire/DisplayAntrian.php`
- `resources/views/livewire/display-antrian.blade.php`

**Fungsi:**
- Tampilkan antrian aktif per loket
- Auto-refresh setiap 5 detik
- Tampilan untuk monitor/TV

## 🔐 Autentikasi

Aplikasi menggunakan token-based authentication dengan Laravel Sanctum:

1. Login melalui `/petugas`
2. Token disimpan di session
3. Token dikirim di header `Authorization: Bearer {token}` untuk setiap request yang memerlukan autentikasi

## 🎯 Alur Penggunaan

### Untuk Pasien:
1. Buka halaman utama (`/`)
2. Pilih loket yang diinginkan
3. Isi form pendaftaran (nama, nomor HP, alamat, keluhan)
4. Klik "Daftar Antrian"
5. Catat nomor antrian yang diberikan
6. Tunggu nomor dipanggil di display

### Untuk Petugas:
1. Buka halaman petugas (`/petugas`)
2. Login dengan email dan password
3. Pilih loket yang akan dikelola
4. Lihat daftar antrian
5. Klik "Panggil" untuk memanggil antrian berikutnya
6. Klik "Selesai" setelah melayani pasien

### Untuk Display:
1. Buka halaman display (`/display`) di monitor/TV
2. Display akan otomatis refresh setiap 5 detik
3. Menampilkan nomor antrian yang sedang dipanggil di setiap loket

## 🐛 Troubleshooting

### API Connection Error
```
Gagal memuat data loket: Connection refused
```
**Solusi:** Pastikan backend API sudah berjalan di `http://localhost:8000`

### CORS Error
**Solusi:** Pastikan backend sudah mengkonfigurasi CORS dengan benar di `config/cors.php`

### Token Expired
**Solusi:** Logout dan login kembali di halaman petugas

## 📝 Catatan Penting

1. **Backend API Harus Berjalan:** Pastikan backend Laravel API sudah berjalan sebelum menggunakan aplikasi ini
2. **Port yang Berbeda:** Frontend berjalan di port 8001, backend di port 8000
3. **Auto-refresh:** Display antrian akan auto-refresh setiap 5 detik
4. **Session Storage:** Token autentikasi disimpan di session browser

## 🔄 Update & Maintenance

### Update Dependencies
```bash
composer update
npm update
```

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## 📞 Support

Jika ada pertanyaan atau masalah, silakan hubungi tim development.

---

**Dibuat dengan ❤️ menggunakan Laravel Livewire**
