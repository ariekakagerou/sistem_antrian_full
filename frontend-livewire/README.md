# Frontend Livewire - Sistem Antrian Rumah Sakit

Frontend aplikasi sistem antrian rumah sakit yang dibangun dengan Laravel 12, Livewire 3, dan Tailwind CSS 4. Aplikasi ini berkomunikasi dengan Backend API untuk mengelola antrian pasien.

## 📋 Daftar Isi

- [Teknologi yang Digunakan](#teknologi-yang-digunakan)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Instalasi dan Setup](#instalasi-dan-setup)
- [Konfigurasi](#konfigurasi)
- [Menjalankan Aplikasi](#menjalankan-aplikasi)
- [Fitur Aplikasi](#fitur-aplikasi)
- [Struktur Aplikasi](#struktur-aplikasi)
- [Halaman dan Komponen](#halaman-dan-komponen)
- [Testing Manual](#testing-manual)
- [Troubleshooting](#troubleshooting)

## 🚀 Teknologi yang Digunakan

- **Laravel 12** - Framework PHP
- **Livewire 3.6** - Framework full-stack untuk Laravel
- **Tailwind CSS 4** - Framework CSS utility-first
- **Vite** - Build tool dan dev server
- **Axios** - HTTP client untuk API calls
- **PHP 8.2+** - Bahasa pemrograman
- **SQLite** - Database (untuk session storage)

## 📦 Persyaratan Sistem

Pastikan sistem Anda memiliki:

- PHP >= 8.2
- Composer
- Node.js >= 18.x
- NPM atau Yarn
- SQLite3 extension untuk PHP
- Extension PHP yang diperlukan:
  - OpenSSL
  - PDO
  - Mbstring
  - Tokenizer
  - XML
  - Ctype
  - JSON
  - BCMath

## 🔧 Instalasi dan Setup

### 1. Pastikan Backend API Sudah Berjalan

Sebelum menjalankan frontend, pastikan backend API sudah berjalan di `http://localhost:8000`. Lihat dokumentasi backend untuk setup.

### 2. Masuk ke Direktori Frontend

```bash
cd frontend-livewire
```

### 3. Install Dependencies PHP

```bash
composer install
```

### 4. Install Dependencies Node.js

```bash
npm install
```

### 5. Setup Environment

Salin file `.env.example` menjadi `.env`:

```bash
copy .env.example .env
```

### 6. Generate Application Key

```bash
php artisan key:generate
```

### 7. Buat Database SQLite untuk Session

```bash
# Windows
type nul > database\database.sqlite

# Linux/Mac
touch database/database.sqlite
```

### 8. Jalankan Migration

```bash
php artisan migrate
```

Migration akan membuat tabel untuk:
- `sessions` - Session storage
- `cache` - Cache storage
- `jobs` - Queue jobs

## ⚙️ Konfigurasi

### Konfigurasi Backend API

Edit file `.env` dan sesuaikan URL backend API:

```env
API_BASE_URL=http://localhost:8000/api
```

**Penting**: Pastikan URL ini sesuai dengan URL backend API yang sedang berjalan.

### Konfigurasi App URL

```env
APP_URL=http://localhost:8001
```

### Konfigurasi Database

Default menggunakan SQLite untuk session storage:

```env
DB_CONNECTION=sqlite
SESSION_DRIVER=database
```

## ▶️ Menjalankan Aplikasi

### Development Mode

Ada 2 cara untuk menjalankan aplikasi:

#### Cara 1: Menjalankan Server dan Vite Secara Terpisah

**Terminal 1** - Jalankan Laravel Server:
```bash
php artisan serve --port=8001
```

**Terminal 2** - Jalankan Vite Dev Server:
```bash
npm run dev
```

#### Cara 2: Menjalankan Semuanya Sekaligus (Recommended)

```bash
composer dev
```

Command ini akan menjalankan:
- Laravel server di port 8000
- Vite dev server
- Queue listener
- Log viewer (Pail)

### Production Build

Untuk production, build assets terlebih dahulu:

```bash
npm run build
```

Kemudian jalankan server:

```bash
php artisan serve --port=8001
```

### Menggunakan Port Berbeda

```bash
php artisan serve --port=9000
```

Jangan lupa update `APP_URL` di `.env` jika menggunakan port berbeda.

## 🎯 Fitur Aplikasi

### 1. Halaman Pasien (`/`)

**Fitur:**
- Login dengan Google OAuth
- Pilih loket pelayanan
- Daftar antrian baru
- Input data pasien (nama, nomor HP, alamat)
- Lihat nomor antrian yang didapat
- Cetak tiket antrian
- Lihat status antrian real-time

**Akses:** Public (semua orang bisa akses)

### 2. Halaman Display Antrian (`/display`)

**Fitur:**
- Tampilan layar besar untuk ruang tunggu
- Menampilkan antrian yang sedang dipanggil
- Menampilkan daftar antrian menunggu
- Auto-refresh setiap beberapa detik
- Tampilan per loket

**Akses:** Public (untuk ditampilkan di TV/monitor ruang tunggu)

### 3. Halaman Petugas (`/petugas`)

**Fitur:**
- Login sebagai petugas
- Pilih loket yang akan dikelola
- Dashboard statistik antrian
- Kelola daftar antrian
- Panggil antrian berikutnya
- Selesaikan antrian
- Batalkan antrian
- Lihat riwayat antrian
- Pengaturan loket

**Akses:** Protected (hanya untuk petugas yang sudah login)

**Menu Petugas:**
- **Dashboard** - Statistik dan ringkasan antrian
- **Daftar Antrian** - Kelola semua antrian
- **Pemanggilan** - Interface untuk memanggil antrian
- **Riwayat** - Lihat riwayat antrian selesai
- **Pengaturan** - Kelola data loket

## 📁 Struktur Aplikasi

### Livewire Components

```
app/Livewire/
├── PasienAntrian.php      # Komponen halaman pasien
├── DisplayAntrian.php     # Komponen display antrian
├── PetugasLoket.php       # Komponen halaman petugas
└── GoogleCallback.php     # Handler OAuth Google
```

### Blade Views

```
resources/views/livewire/
├── pasien-antrian.blade.php      # View halaman pasien
├── display-antrian.blade.php     # View display antrian
├── petugas-loket.blade.php       # View halaman petugas
└── google-callback.blade.php     # View callback Google
```

### Routes

```php
// Halaman Utama
Route::get('/', PasienAntrian::class);
Route::get('/display', DisplayAntrian::class);
Route::get('/auth/google/callback', GoogleCallback::class);

// Halaman Petugas
Route::get('/petugas', PetugasLoket::class);
Route::get('/petugas/dashboard/{loket_id}', PetugasLoket::class);
Route::get('/petugas/daftar-antrian/{loket_id}', PetugasLoket::class);
Route::get('/petugas/pemanggilan/{loket_id}', PetugasLoket::class);
Route::get('/petugas/riwayat/{loket_id}', PetugasLoket::class);
Route::get('/petugas/pengaturan/{loket_id}', PetugasLoket::class);
```

## 🧩 Halaman dan Komponen

### 1. Halaman Pasien

**URL:** `http://localhost:8001/`

**Komponen:** `PasienAntrian.php`

**Fungsi Utama:**
- `mount()` - Inisialisasi data loket
- `selectLoket($loketId)` - Pilih loket
- `submitAntrian()` - Daftar antrian baru
- `cetakAntrian($antrianId)` - Cetak tiket antrian
- `loginWithGoogle()` - Redirect ke Google OAuth

**API Endpoints yang Digunakan:**
- `GET /api/loket` - Ambil daftar loket
- `POST /api/antrian` - Buat antrian baru
- `GET /api/antrian/{id}` - Ambil detail antrian

### 2. Halaman Display Antrian

**URL:** `http://localhost:8001/display`

**Komponen:** `DisplayAntrian.php`

**Fungsi Utama:**
- `mount()` - Inisialisasi data
- `loadAntrian()` - Load data antrian
- Auto-refresh dengan Livewire polling

**API Endpoints yang Digunakan:**
- `GET /api/antrian?status=dipanggil` - Antrian yang dipanggil
- `GET /api/antrian?status=menunggu` - Antrian menunggu

**Fitur:**
- Tampilan full-screen
- Auto-refresh setiap 5 detik
- Animasi smooth saat update data
- Responsive design

### 3. Halaman Petugas

**URL:** `http://localhost:8001/petugas`

**Komponen:** `PetugasLoket.php`

**Fungsi Utama:**
- `mount()` - Inisialisasi dan cek autentikasi
- `login()` - Login petugas
- `logout()` - Logout petugas
- `selectLoket($loketId)` - Pilih loket
- `loadAntrian()` - Load data antrian
- `panggilAntrian($antrianId)` - Panggil antrian
- `selesaikanAntrian($antrianId)` - Selesaikan antrian
- `batalkanAntrian($antrianId)` - Batalkan antrian

**API Endpoints yang Digunakan:**
- `POST /api/login` - Login petugas
- `POST /api/logout` - Logout petugas
- `GET /api/loket` - Ambil daftar loket
- `GET /api/antrian/loket/{loketId}` - Antrian per loket
- `POST /api/antrian/{id}/panggil` - Panggil antrian
- `POST /api/antrian/{id}/selesai` - Selesaikan antrian
- `DELETE /api/antrian/{id}` - Batalkan antrian

**Menu:**
- **Dashboard** - Statistik antrian (total, menunggu, dipanggil, selesai)
- **Daftar Antrian** - Tabel semua antrian dengan aksi
- **Pemanggilan** - Interface khusus untuk memanggil antrian
- **Riwayat** - Daftar antrian yang sudah selesai
- **Pengaturan** - Info loket dan pengaturan

## 🧪 Testing Manual

### Persiapan Testing

1. **Pastikan Backend API Berjalan**
   ```bash
   # Di folder backend-laravel
   php artisan serve
   ```
   Backend harus berjalan di `http://localhost:8000`

2. **Jalankan Frontend**
   ```bash
   # Di folder frontend-livewire
   php artisan serve --port=8001
   npm run dev
   ```

3. **Buat Data Loket di Backend**
   
   Gunakan Postman untuk membuat loket:
   - Register sebagai petugas
   - Login dan dapatkan token
   - Buat loket dengan endpoint `POST /api/loket`

### Test Case 1: Halaman Pasien - Daftar Antrian

**Langkah:**
1. Buka browser: `http://localhost:8001/`
2. Klik tombol "Login dengan Google" (opsional, bisa skip)
3. Pilih salah satu loket yang tersedia
4. Isi form pendaftaran:
   - Nama Pasien: "John Doe"
   - Nomor HP: "08123456789"
   - Alamat: "Jl. Contoh No. 123"
5. Klik "Daftar Antrian"

**Verifikasi:**
- ✅ Muncul modal sukses dengan nomor antrian
- ✅ Nomor antrian sesuai format (contoh: A001)
- ✅ Tombol "Cetak Tiket" berfungsi
- ✅ Form ter-reset setelah berhasil

### Test Case 2: Halaman Display - Monitoring Antrian

**Langkah:**
1. Buka tab baru: `http://localhost:8001/display`

**Verifikasi:**
- ✅ Tampilan full-screen
- ✅ Menampilkan antrian yang dipanggil (jika ada)
- ✅ Menampilkan daftar antrian menunggu
- ✅ Data ter-update otomatis setiap 5 detik
- ✅ Tampilan responsive dan menarik

### Test Case 3: Halaman Petugas - Login

**Langkah:**
1. Buka tab baru: `http://localhost:8001/petugas`
2. Isi form login:
   - Email: email petugas yang sudah didaftarkan di backend
   - Password: password petugas
3. Klik "Login"

**Verifikasi:**
- ✅ Redirect ke halaman pilih loket
- ✅ Menampilkan daftar loket yang tersedia
- ✅ Token tersimpan di session

### Test Case 4: Halaman Petugas - Dashboard

**Langkah:**
1. Setelah login, pilih salah satu loket
2. Klik menu "Dashboard"

**Verifikasi:**
- ✅ Menampilkan statistik antrian:
  - Total antrian
  - Antrian menunggu
  - Antrian dipanggil
  - Antrian selesai
- ✅ Data statistik akurat
- ✅ Card statistik dengan warna berbeda

### Test Case 5: Halaman Petugas - Panggil Antrian

**Langkah:**
1. Klik menu "Daftar Antrian"
2. Cari antrian dengan status "Menunggu"
3. Klik tombol "Panggil" pada salah satu antrian

**Verifikasi:**
- ✅ Muncul notifikasi sukses
- ✅ Status antrian berubah menjadi "Dipanggil"
- ✅ Waktu panggil ter-record
- ✅ Tombol berubah menjadi "Selesai"

4. Buka halaman Display (`/display`) di tab lain

**Verifikasi:**
- ✅ Antrian yang dipanggil muncul di bagian atas
- ✅ Data ter-update otomatis

### Test Case 6: Halaman Petugas - Selesaikan Antrian

**Langkah:**
1. Klik tombol "Selesai" pada antrian yang sudah dipanggil

**Verifikasi:**
- ✅ Muncul notifikasi sukses
- ✅ Status antrian berubah menjadi "Selesai"
- ✅ Waktu selesai ter-record
- ✅ Antrian hilang dari daftar aktif

### Test Case 7: Cetak Tiket Antrian

**Langkah:**
1. Setelah daftar antrian di halaman pasien
2. Klik tombol "Cetak Tiket"

**Verifikasi:**
- ✅ Membuka halaman cetak di tab baru
- ✅ Menampilkan informasi lengkap:
  - Nomor antrian
  - Nama pasien
  - Loket tujuan
  - Tanggal dan waktu
- ✅ Format print-friendly
- ✅ Bisa di-print dengan Ctrl+P

### Test Case 8: Responsiveness

**Langkah:**
1. Buka aplikasi di berbagai ukuran layar:
   - Desktop (1920x1080)
   - Tablet (768x1024)
   - Mobile (375x667)

**Verifikasi setiap halaman:**
- ✅ Layout menyesuaikan ukuran layar
- ✅ Tombol dan form tetap accessible
- ✅ Teks terbaca dengan jelas
- ✅ Tidak ada overflow horizontal

### Checklist Testing Lengkap

**Halaman Pasien:**
- [ ] Login dengan Google berfungsi
- [ ] Pilih loket berfungsi
- [ ] Form pendaftaran berfungsi
- [ ] Validasi form berfungsi
- [ ] Daftar antrian berhasil
- [ ] Nomor antrian ter-generate
- [ ] Cetak tiket berfungsi
- [ ] Modal sukses muncul
- [ ] Form ter-reset setelah submit

**Halaman Display:**
- [ ] Tampilan full-screen
- [ ] Menampilkan antrian dipanggil
- [ ] Menampilkan antrian menunggu
- [ ] Auto-refresh berfungsi
- [ ] Animasi smooth
- [ ] Responsive di berbagai ukuran

**Halaman Petugas:**
- [ ] Login berfungsi
- [ ] Logout berfungsi
- [ ] Pilih loket berfungsi
- [ ] Dashboard menampilkan statistik
- [ ] Daftar antrian ter-load
- [ ] Filter status berfungsi
- [ ] Panggil antrian berfungsi
- [ ] Selesaikan antrian berfungsi
- [ ] Batalkan antrian berfungsi
- [ ] Riwayat menampilkan data
- [ ] Pengaturan menampilkan info loket
- [ ] Ganti loket berfungsi
- [ ] Real-time update berfungsi

**General:**
- [ ] Responsive di mobile
- [ ] Responsive di tablet
- [ ] Responsive di desktop
- [ ] Error handling berfungsi
- [ ] Loading state ditampilkan
- [ ] Notifikasi sukses/error muncul

## 🐛 Troubleshooting

### Error: "Connection refused" atau "Network Error"

**Penyebab:** Backend API tidak berjalan atau URL salah

**Solusi:**
1. Pastikan backend API berjalan di `http://localhost:8000`
2. Cek konfigurasi `API_BASE_URL` di `.env`
3. Test backend dengan curl:
   ```bash
   curl http://localhost:8000/api/loket
   ```

### Error: "Unauthenticated" di Halaman Petugas

**Penyebab:** Token tidak valid atau expired

**Solusi:**
1. Logout dan login ulang
2. Clear session:
   ```bash
   php artisan session:flush
   ```
3. Clear cache:
   ```bash
   php artisan cache:clear
   ```

### Livewire Component Tidak Ter-update

**Penyebab:** Vite dev server tidak berjalan atau cache

**Solusi:**
1. Pastikan Vite dev server berjalan (`npm run dev`)
2. Hard refresh browser (Ctrl + Shift + R)
3. Clear browser cache
4. Restart Vite dev server

### Styling Tidak Muncul

**Penyebab:** Tailwind CSS tidak ter-compile

**Solusi:**
1. Pastikan Vite dev server berjalan
2. Rebuild assets:
   ```bash
   npm run build
   ```
3. Clear cache:
   ```bash
   php artisan view:clear
   ```

### Port 8001 Sudah Digunakan

**Solusi:** Gunakan port lain
```bash
php artisan serve --port=9000
```

Jangan lupa update `APP_URL` di `.env`:
```env
APP_URL=http://localhost:9000
```

### Google OAuth Tidak Berfungsi

**Penyebab:** Konfigurasi Google OAuth tidak lengkap

**Solusi:**
1. Pastikan backend sudah dikonfigurasi untuk Google OAuth
2. Cek redirect URI di Google Console
3. Pastikan callback URL benar di backend

### Database Locked Error

**Solusi:**
1. Tutup semua koneksi database
2. Restart server
3. Atau gunakan MySQL instead of SQLite

### Livewire Polling Tidak Berfungsi

**Penyebab:** JavaScript error atau network issue

**Solusi:**
1. Buka browser console (F12) dan cek error
2. Pastikan tidak ada JavaScript error
3. Cek network tab untuk melihat request Livewire
4. Restart browser

### Session Hilang Setelah Refresh

**Penyebab:** Session driver tidak dikonfigurasi dengan benar

**Solusi:**
1. Pastikan `SESSION_DRIVER=database` di `.env`
2. Jalankan migration untuk tabel sessions:
   ```bash
   php artisan migrate
   ```
3. Clear session:
   ```bash
   php artisan session:flush
   ```

## 📝 Catatan Penting

### Development

1. **Hot Module Replacement (HMR):** Vite mendukung HMR, perubahan code akan langsung ter-reflect tanpa refresh

2. **Livewire Polling:** Halaman Display menggunakan polling untuk auto-refresh. Interval bisa diatur di component

3. **API Token:** Token dari backend disimpan di session Laravel, bukan localStorage

4. **CORS:** Pastikan backend mengizinkan request dari frontend URL

### Production

1. **Build Assets:** Selalu build assets sebelum deploy:
   ```bash
   npm run build
   ```

2. **Environment:** Set `APP_ENV=production` dan `APP_DEBUG=false`

3. **HTTPS:** Gunakan HTTPS di production untuk keamanan

4. **Cache:** Enable cache untuk performa:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

5. **Session:** Gunakan Redis atau Memcached untuk session di production

### Best Practices

1. **Separation of Concerns:** Frontend hanya handle UI, business logic di backend

2. **Error Handling:** Selalu handle error dari API dengan graceful

3. **Loading States:** Tampilkan loading indicator saat request API

4. **Validation:** Validasi input di frontend dan backend

5. **Security:** Jangan simpan sensitive data di frontend

## 📚 Dokumentasi Tambahan

- [Laravel Documentation](https://laravel.com/docs)
- [Livewire Documentation](https://livewire.laravel.com/docs)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [Vite Documentation](https://vitejs.dev/guide/)

## 📧 Support

Jika ada pertanyaan atau masalah, silakan buat issue atau hubungi tim development.

---

**Happy Coding! 🚀**
