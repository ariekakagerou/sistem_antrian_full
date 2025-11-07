# 🚂 Setup PostgreSQL dengan Railway

Panduan lengkap menggunakan PostgreSQL Railway untuk Sistem Antrian Rumah Sakit.

---

## 📋 Apa itu Railway?

**Railway** adalah platform cloud yang menyediakan database PostgreSQL gratis untuk development dengan fitur:
- ✅ **Gratis**: 500 jam/bulan, 512MB storage
- ✅ **Cloud Database**: Bisa diakses dari mana saja
- ✅ **Auto Backups**: Data aman dengan backup otomatis
- ✅ **Easy Setup**: Setup dalam 5 menit
- ✅ **Monitoring**: Dashboard untuk monitoring database

**Website:** https://railway.app/

---

## 🚀 Setup Railway (Step-by-Step)

### 1️⃣ Buat Akun Railway

1. Kunjungi https://railway.app/
2. Klik **"Start a New Project"**
3. Login dengan:
   - GitHub (Recommended)
   - Google
   - Email

**Gratis tanpa kartu kredit!**

### 2️⃣ Provision PostgreSQL Database

1. Setelah login, klik **"New Project"**
2. Pilih **"Provision PostgreSQL"**
3. Tunggu beberapa detik, database akan dibuat otomatis
4. Database siap digunakan!

### 3️⃣ Dapatkan Database Credentials

**Cara 1: Copy Individual Variables**

1. Klik database PostgreSQL yang baru dibuat
2. Pilih tab **"Variables"**
3. Copy credentials berikut:

```
PGHOST       → DB_HOST
PGPORT       → DB_PORT (biasanya 5432)
PGDATABASE   → DB_DATABASE (biasanya "railway")
PGUSER       → DB_USERNAME (biasanya "postgres")
PGPASSWORD   → DB_PASSWORD
```

**Cara 2: Copy DATABASE_URL (Lebih Mudah)**

Copy langsung `DATABASE_URL` yang formatnya:
```
postgresql://postgres:password@containers-us-west-xxx.railway.app:5432/railway
```

### 4️⃣ Update File `.env` Backend

**Opsi A: Gunakan Individual Variables**

Edit file `backend-laravel/.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=containers-us-west-xxx.railway.app
DB_PORT=5432
DB_DATABASE=railway
DB_USERNAME=postgres
DB_PASSWORD=xxxxxxxxxxxxxxxxxxxxx
```

**Opsi B: Gunakan DATABASE_URL**

```env
DB_CONNECTION=pgsql
DATABASE_URL=postgresql://postgres:xxxxx@containers-us-west-xxx.railway.app:5432/railway
```

### 5️⃣ Install PostgreSQL Driver untuk PHP

**Windows:**

1. Cari file `php.ini`:
   ```bash
   php --ini
   ```

2. Edit `php.ini`, uncomment baris berikut:
   ```ini
   extension=pdo_pgsql
   extension=pgsql
   ```

3. Restart server/terminal

**Linux/Mac:**

```bash
# Ubuntu/Debian
sudo apt-get install php-pgsql

# Mac (Homebrew)
brew install php-pgsql
```

**Cek apakah sudah terinstall:**
```bash
php -m | grep pgsql
```

Harus muncul:
```
pdo_pgsql
pgsql
```

### 6️⃣ Test Koneksi Database

```bash
cd backend-laravel

# Test koneksi
php artisan tinker
>>> DB::connection()->getPdo();
>>> exit

# Jika berhasil, jalankan migration
php artisan migrate:fresh --seed
```

**Output yang diharapkan:**
```
Migration table created successfully.
Migrating: 2024_xx_xx_create_lokets_table
Migrated:  2024_xx_xx_create_lokets_table
...
Database seeding completed successfully.
```

### 7️⃣ Verifikasi di Railway Dashboard

1. Buka Railway dashboard
2. Klik database PostgreSQL
3. Pilih tab **"Data"**
4. Lihat tabel yang sudah dibuat:
   - `lokets`
   - `antrians`
   - `users`
   - dll.

---

## 🔧 Konfigurasi Tambahan

### Update Frontend `.env`

Frontend tidak perlu koneksi langsung ke Railway, cukup ke backend API:

```env
# frontend-livewire/.env
API_BASE_URL=http://localhost:8000/api

# Frontend bisa pakai SQLite untuk session/cache
DB_CONNECTION=sqlite
```

### Environment Variables Railway

Jika deploy backend ke Railway juga, set environment variables:

```
DB_CONNECTION=pgsql
DB_HOST=${PGHOST}
DB_PORT=${PGPORT}
DB_DATABASE=${PGDATABASE}
DB_USERNAME=${PGUSER}
DB_PASSWORD=${PGPASSWORD}
```

Railway akan otomatis inject variables ini.

---

## 📊 Monitoring Database

### Railway Dashboard

**Metrics yang bisa dilihat:**
- 📈 CPU Usage
- 💾 Memory Usage
- 📦 Storage Usage
- 🔌 Active Connections
- 📊 Query Performance

**Cara akses:**
1. Klik database di Railway dashboard
2. Pilih tab **"Metrics"**

### Query Database Manual

**Gunakan Railway CLI:**

```bash
# Install Railway CLI
npm i -g @railway/cli

# Login
railway login

# Connect ke database
railway connect postgres

# Sekarang bisa jalankan SQL query
SELECT * FROM lokets;
SELECT * FROM antrians;
```

**Atau gunakan TablePlus/pgAdmin:**
- Host: `containers-us-west-xxx.railway.app`
- Port: `5432`
- Database: `railway`
- Username: `postgres`
- Password: `[dari Railway]`

---

## ⚠️ Troubleshooting

### Error: "could not find driver"

**Solusi:**
```bash
# Pastikan extension PostgreSQL sudah enable
php -m | grep pgsql

# Jika tidak ada, edit php.ini:
extension=pdo_pgsql
extension=pgsql

# Restart server
```

### Error: "SQLSTATE[08006] Connection refused"

**Penyebab:**
- Credentials salah
- Database Railway sedang down
- Firewall block koneksi

**Solusi:**
```bash
# 1. Cek credentials di Railway dashboard
# 2. Copy ulang DATABASE_URL
# 3. Test koneksi:
php artisan tinker
>>> DB::connection()->getPdo();
```

### Error: "SQLSTATE[42P01] relation does not exist"

**Penyebab:** Tabel belum dibuat

**Solusi:**
```bash
cd backend-laravel
php artisan migrate:fresh --seed
```

### Database Penuh (Storage Limit)

**Railway Free Tier:** 512MB storage

**Solusi:**
```bash
# 1. Hapus data lama
php artisan tinker
>>> DB::table('antrians')->where('status', 'selesai')->delete();

# 2. Atau upgrade ke paid plan Railway
# 3. Atau pindah ke database lain
```

### Koneksi Lambat

**Penyebab:** Server Railway jauh dari lokasi Anda

**Solusi:**
- Gunakan region Railway yang lebih dekat
- Atau gunakan database lokal untuk development
- Railway untuk production/testing saja

---

## 💡 Tips & Best Practices

### 1. Gunakan Connection Pooling

Edit `config/database.php`:

```php
'pgsql' => [
    'driver' => 'pgsql',
    // ... other config
    'options' => [
        PDO::ATTR_PERSISTENT => true,
    ],
],
```

### 2. Backup Database Secara Manual

```bash
# Install Railway CLI
railway login

# Backup database
railway run pg_dump > backup.sql

# Restore
railway run psql < backup.sql
```

### 3. Monitoring Query Performance

```php
// Tambahkan di AppServiceProvider.php
use Illuminate\Support\Facades\DB;

public function boot()
{
    DB::listen(function ($query) {
        if ($query->time > 1000) { // Query lebih dari 1 detik
            \Log::warning('Slow query: ' . $query->sql);
        }
    });
}
```

### 4. Index untuk Performance

```php
// Migration
Schema::table('antrians', function (Blueprint $table) {
    $table->index('loket_id');
    $table->index('status');
    $table->index(['loket_id', 'status']);
});
```

### 5. Environment-Specific Config

```php
// .env.local (development)
DB_CONNECTION=sqlite

// .env.production (production)
DB_CONNECTION=pgsql
DATABASE_URL=postgresql://...
```

---

## 📈 Upgrade ke Paid Plan

**Jika butuh lebih:**

| Feature | Free | Hobby ($5/mo) | Pro ($20/mo) |
|---------|------|---------------|--------------|
| Storage | 512MB | 8GB | 100GB |
| RAM | 512MB | 8GB | 32GB |
| Hours | 500h | Unlimited | Unlimited |
| Backups | Manual | Auto | Auto + Point-in-time |

**Cara upgrade:**
1. Railway Dashboard → Settings
2. Pilih plan yang sesuai
3. Masukkan payment method

---

## 🔗 Resources

- **Railway Docs:** https://docs.railway.app/
- **PostgreSQL Docs:** https://www.postgresql.org/docs/
- **Laravel Database:** https://laravel.com/docs/database
- **Railway Discord:** https://discord.gg/railway

---

## ✅ Checklist Setup Railway

- [ ] Buat akun Railway
- [ ] Provision PostgreSQL database
- [ ] Copy credentials dari dashboard
- [ ] Update `.env` backend dengan credentials
- [ ] Enable extension `pdo_pgsql` dan `pgsql` di PHP
- [ ] Test koneksi dengan `php artisan tinker`
- [ ] Jalankan `php artisan migrate:fresh --seed`
- [ ] Verifikasi data di Railway dashboard
- [ ] Test aplikasi berjalan normal

---

**Selamat! Database PostgreSQL Railway sudah siap digunakan! 🎉**

**Version:** 1.0  
**Last Updated:** 7 November 2025
