# 🔐 Google OAuth2 Setup Guide

Panduan konfigurasi Google OAuth2 untuk login petugas.

## 📋 Fitur

- ✅ Login petugas menggunakan akun Google
- ✅ Auto-create user baru dari Google
- ✅ Redirect otomatis ke dashboard setelah login
- ✅ Token-based authentication dengan Laravel Sanctum

## 🔧 Konfigurasi Backend

### 1. Update File .env

Edit file `backend-laravel/.env` dan tambahkan:

```env
# Frontend URL
FRONTEND_URL=http://localhost:8001

# Google OAuth Configuration
GOOGLE_CLIENT_ID=your_google_client_id_here
GOOGLE_CLIENT_SECRET=your_google_client_secret_here
GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/auth/google/callback
```

### 2. Install Laravel Socialite (Jika Belum)

```bash
cd backend-laravel
composer require laravel/socialite
```

### 3. Konfigurasi Socialite

Edit `backend-laravel/config/services.php` dan tambahkan:

```php
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI'),
],
```

### 4. Migrasi Database (Tambah kolom google_id)

Jika belum ada, buat migration:

```bash
php artisan make:migration add_google_id_to_users_table
```

Edit migration file:

```php
public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('google_id')->nullable()->unique()->after('email');
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('google_id');
    });
}
```

Jalankan migration:

```bash
php artisan migrate
```

## 🎨 Konfigurasi Frontend

Frontend sudah dikonfigurasi otomatis dengan:
- Tombol "Login dengan Google" di halaman `/petugas`
- Halaman callback di `/auth/google/callback`
- Auto-redirect ke dashboard setelah login sukses

## 🔄 Alur Login Google OAuth

### 1. User Klik "Login dengan Google"
```
Frontend: http://localhost:8001/petugas
↓
Klik tombol "Login dengan Google"
```

### 2. Redirect ke Google
```
Backend: http://127.0.0.1:8000/auth/google (WEB ROUTE)
↓
Redirect ke Google Login Page
```

**PENTING:** Route Google OAuth menggunakan `web.php` (bukan `api.php`) karena:
- OAuth redirect memerlukan session
- Socialite bekerja lebih baik dengan web middleware
- Redirect URL lebih clean tanpa prefix `/api`

### 3. User Login di Google
```
Google OAuth Page
↓
User pilih akun Google
↓
User authorize aplikasi
```

### 4. Google Callback ke Backend
```
Backend: http://127.0.0.1:8000/auth/google/callback
↓
Terima data user dari Google
↓
Cek/Buat user di database
↓
Generate token
```

### 5. Redirect ke Frontend dengan Token
```
Frontend: http://localhost:8001/auth/google/callback?token=xxx
↓
Simpan token ke session
↓
Redirect ke /petugas (dashboard)
```

## 📝 File yang Dimodifikasi

### Backend
- ✅ `app/Http/Controllers/Auth/GoogleController.php` - Update callback redirect
- ✅ `routes/web.php` - Tambah route Google OAuth (PENTING!)
- ✅ `routes/api.php` - Hapus route Google OAuth (dipindah ke web.php)
- ✅ `.env.example` - Tambah FRONTEND_URL dan Google credentials

### Frontend
- ✅ `app/Livewire/PetugasLoket.php` - Tambah method loginWithGoogle
- ✅ `app/Livewire/GoogleCallback.php` - NEW - Handle callback
- ✅ `resources/views/livewire/petugas-loket.blade.php` - Tambah tombol Google
- ✅ `resources/views/livewire/google-callback.blade.php` - NEW - View callback
- ✅ `routes/web.php` - Tambah route callback

## 🧪 Testing

### 1. Pastikan Backend Berjalan
```bash
cd backend-laravel
php artisan serve
```

### 2. Pastikan Frontend Berjalan
```bash
cd frontend-livewire
php artisan serve --port=8001
```

### 3. Test Login Google
1. Buka: http://localhost:8001/petugas
2. Klik tombol "Login dengan Google"
3. Pilih akun Google
4. Authorize aplikasi
5. Akan redirect ke dashboard petugas

## 🔐 Keamanan

### User Role
- User baru dari Google otomatis mendapat role `petugas`
- Bisa diubah di `GoogleController.php` line 50:
  ```php
  'role' => 'petugas', // Ubah sesuai kebutuhan
  ```

### Token Management
- Token disimpan di session browser
- Token valid sampai user logout
- Token dihapus saat logout

## 🐛 Troubleshooting

### Error: "Redirect URI mismatch"
**Solusi:**
1. Cek Google Cloud Console
2. Pastikan redirect URI: `http://127.0.0.1:8000/auth/google/callback`
3. Gunakan `127.0.0.1` bukan `localhost`

### Error: "Invalid client"
**Solusi:**
1. Cek GOOGLE_CLIENT_ID di .env
2. Pastikan credentials benar
3. Clear config: `php artisan config:clear`

### Error: "CORS"
**Solusi:**
1. Pastikan `config/cors.php` sudah benar
2. Allowed origins: `http://localhost:8001`

### Callback tidak redirect
**Solusi:**
1. Cek FRONTEND_URL di backend .env
2. Pastikan route `/auth/google/callback` ada di frontend
3. Clear cache: `php artisan route:clear`

## 📊 Google Cloud Console Setup

### 1. Buat Project
1. Buka: https://console.cloud.google.com
2. Buat project baru atau pilih existing

### 2. Enable Google+ API
1. APIs & Services → Library
2. Cari "Google+ API"
3. Enable

### 3. Create Credentials
1. APIs & Services → Credentials
2. Create Credentials → OAuth 2.0 Client ID
3. Application type: Web application
4. Authorized redirect URIs:
   ```
   http://127.0.0.1:8000/auth/google/callback
   ```

### 4. Copy Credentials
- Client ID: `your_google_client_id_here`
- Client Secret: `your_google_client_secret_here`

## 💡 Tips

1. **Development vs Production**
   - Development: `http://127.0.0.1:8000`
   - Production: `https://yourdomain.com`
   - Update redirect URI di Google Console untuk production

2. **Multiple Environments**
   - Buat OAuth credentials berbeda untuk dev dan prod
   - Gunakan .env berbeda untuk setiap environment

3. **User Data**
   - Google memberikan: name, email, avatar
   - Bisa tambah scope untuk data lebih lengkap

## 🔄 Update Existing Users

Jika ada user existing yang ingin link dengan Google:

```php
// User login dengan email/password pertama kali
// Kemudian login dengan Google
// GoogleController akan auto-update google_id
```

## 📞 Support

Jika ada masalah:
1. Cek log Laravel: `storage/logs/laravel.log`
2. Cek console browser (F12)
3. Pastikan semua konfigurasi benar

---

**Status:** ✅ Ready to use  
**Last Updated:** 5 November 2024
