# Backend API - Sistem Antrian Rumah Sakit

Backend REST API untuk sistem antrian rumah sakit yang dibangun dengan Laravel 12 dan menggunakan Laravel Sanctum untuk autentikasi.

## 📋 Daftar Isi

- [Teknologi yang Digunakan](#teknologi-yang-digunakan)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Instalasi dan Setup](#instalasi-dan-setup)
- [Konfigurasi Database](#konfigurasi-database)
- [Menjalankan Aplikasi](#menjalankan-aplikasi)
- [Struktur Database](#struktur-database)
- [API Endpoints](#api-endpoints)
- [Testing dengan Postman](#testing-dengan-postman)
- [Troubleshooting](#troubleshooting)

## 🚀 Teknologi yang Digunakan

- **Laravel 12** - Framework PHP
- **PHP 8.2+** - Bahasa pemrograman
- **SQLite** - Database (default)
- **Laravel Sanctum** - Autentikasi API
- **Laravel Socialite** - OAuth Google (opsional)

## 📦 Persyaratan Sistem

Pastikan sistem Anda memiliki:

- PHP >= 8.2
- Composer
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

### 1. Clone atau Download Project

```bash
cd backend-laravel
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Setup Environment

Salin file `.env.example` menjadi `.env`:

```bash
copy .env.example .env
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Buat Database SQLite

```bash
# Windows
type nul > database\database.sqlite

# Linux/Mac
touch database/database.sqlite
```

### 6. Jalankan Migration

```bash
php artisan migrate
```

Migration akan membuat tabel-tabel berikut:
- `users` - Data pengguna dan petugas
- `lokets` - Data loket pelayanan
- `antrians` - Data antrian pasien
- `personal_access_tokens` - Token autentikasi Sanctum
- `cache`, `jobs`, `sessions` - Tabel sistem Laravel

### 7. (Opsional) Seed Data Dummy

Jika Anda ingin membuat data dummy untuk testing:

```bash
php artisan db:seed
```

## 🗄️ Konfigurasi Database

### Menggunakan SQLite (Default)

File `.env` sudah dikonfigurasi untuk SQLite:

```env
DB_CONNECTION=sqlite
```

### Menggunakan MySQL (Opsional)

Jika ingin menggunakan MySQL, ubah konfigurasi di `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=root
DB_PASSWORD=
```

Kemudian jalankan migration ulang:

```bash
php artisan migrate:fresh
```

## ▶️ Menjalankan Aplikasi

### Development Server

Jalankan server development Laravel:

```bash
php artisan serve
```

Server akan berjalan di `http://127.0.0.1:8000`

### Menggunakan Port Berbeda

```bash
php artisan serve --port=8080
```

## 🗂️ Struktur Database

### Tabel Users

| Field | Type | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| name | varchar | Nama pengguna |
| email | varchar | Email (unique) |
| password | varchar | Password (hashed) |
| role | enum | 'pasien' atau 'petugas' |
| google_id | varchar | ID Google OAuth (nullable) |

### Tabel Lokets

| Field | Type | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| nama_loket | varchar | Nama loket (contoh: "Loket 1") |
| kode_loket | varchar | Kode loket (contoh: "A") |
| status | enum | 'aktif' atau 'nonaktif' |

### Tabel Antrians

| Field | Type | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| nomor_antrian | varchar | Nomor antrian (contoh: "A001") |
| loket_id | bigint | Foreign key ke lokets |
| user_id | bigint | Foreign key ke users |
| nama_pasien | varchar | Nama pasien |
| nomor_hp | varchar | Nomor HP (nullable) |
| alamat | text | Alamat (nullable) |
| status | enum | 'menunggu', 'dipanggil', 'selesai', 'batal' |
| waktu_daftar | timestamp | Waktu pendaftaran |
| waktu_panggil | timestamp | Waktu dipanggil (nullable) |
| waktu_selesai | timestamp | Waktu selesai (nullable) |

## 🔌 API Endpoints

### Base URL

```
http://127.0.0.1:8000/api
```

### Authentication Endpoints

#### 1. Register

**POST** `/api/register`

Request Body:
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "pasien"
}
```

Response (201):
```json
{
  "message": "User registered successfully",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "pasien"
  },
  "token": "1|abcdefghijklmnopqrstuvwxyz"
}
```

#### 2. Login

**POST** `/api/login`

Request Body:
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

Response (200):
```json
{
  "message": "Login successful",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "pasien"
  },
  "token": "2|abcdefghijklmnopqrstuvwxyz"
}
```

#### 3. Get Current User

**GET** `/api/user`

Headers:
```
Authorization: Bearer {token}
```

Response (200):
```json
{
  "id": 1,
  "name": "John Doe",
  "email": "john@example.com",
  "role": "pasien"
}
```

#### 4. Logout

**POST** `/api/logout`

Headers:
```
Authorization: Bearer {token}
```

Response (200):
```json
{
  "message": "Logged out successfully"
}
```

### Loket Endpoints

#### 1. Get All Lokets (Public)

**GET** `/api/loket`

Response (200):
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "nama_loket": "Loket 1",
      "kode_loket": "A",
      "status": "aktif"
    }
  ]
}
```

#### 2. Get Loket by ID (Public)

**GET** `/api/loket/{id}`

Response (200):
```json
{
  "success": true,
  "data": {
    "id": 1,
    "nama_loket": "Loket 1",
    "kode_loket": "A",
    "status": "aktif"
  }
}
```

#### 3. Create Loket (Protected - Petugas Only)

**POST** `/api/loket`

Headers:
```
Authorization: Bearer {token}
Content-Type: application/json
```

Request Body:
```json
{
  "nama_loket": "Loket 1",
  "kode_loket": "A",
  "status": "aktif"
}
```

Response (201):
```json
{
  "success": true,
  "message": "Loket berhasil dibuat",
  "data": {
    "id": 1,
    "nama_loket": "Loket 1",
    "kode_loket": "A",
    "status": "aktif"
  }
}
```

#### 4. Update Loket (Protected - Petugas Only)

**PUT** `/api/loket/{id}`

Headers:
```
Authorization: Bearer {token}
Content-Type: application/json
```

Request Body:
```json
{
  "nama_loket": "Loket 1 Updated",
  "status": "nonaktif"
}
```

#### 5. Delete Loket (Protected - Petugas Only)

**DELETE** `/api/loket/{id}`

Headers:
```
Authorization: Bearer {token}
```

### Antrian Endpoints

#### 1. Get All Antrian (Public)

**GET** `/api/antrian`

Query Parameters (opsional):
- `status` - Filter by status (menunggu, dipanggil, selesai, batal)
- `loket_id` - Filter by loket ID
- `tanggal` - Filter by tanggal (Y-m-d)

Response (200):
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "nomor_antrian": "A001",
      "nama_pasien": "Jane Doe",
      "nomor_hp": "08123456789",
      "alamat": "Jl. Contoh No. 123",
      "status": "menunggu",
      "waktu_daftar": "2024-11-07 10:00:00",
      "loket": {
        "id": 1,
        "nama_loket": "Loket 1",
        "kode_loket": "A"
      }
    }
  ]
}
```

#### 2. Get Antrian by ID (Public)

**GET** `/api/antrian/{id}`

Response (200):
```json
{
  "success": true,
  "data": {
    "id": 1,
    "nomor_antrian": "A001",
    "nama_pasien": "Jane Doe",
    "nomor_hp": "08123456789",
    "alamat": "Jl. Contoh No. 123",
    "status": "menunggu",
    "waktu_daftar": "2024-11-07 10:00:00",
    "loket": {
      "id": 1,
      "nama_loket": "Loket 1",
      "kode_loket": "A"
    }
  }
}
```

#### 3. Get Antrian by Loket (Public)

**GET** `/api/antrian/loket/{loketId}`

Response (200):
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "nomor_antrian": "A001",
      "nama_pasien": "Jane Doe",
      "status": "menunggu"
    }
  ]
}
```

#### 4. Create Antrian (Protected - Authenticated)

**POST** `/api/antrian`

Headers:
```
Authorization: Bearer {token}
Content-Type: application/json
```

Request Body:
```json
{
  "loket_id": 1,
  "nama_pasien": "Jane Doe",
  "nomor_hp": "08123456789",
  "alamat": "Jl. Contoh No. 123"
}
```

Response (201):
```json
{
  "success": true,
  "message": "Antrian berhasil dibuat",
  "data": {
    "id": 1,
    "nomor_antrian": "A001",
    "nama_pasien": "Jane Doe",
    "status": "menunggu",
    "waktu_daftar": "2024-11-07 10:00:00"
  }
}
```

#### 5. Update Antrian (Protected - Petugas Only)

**PUT** `/api/antrian/{id}`

Headers:
```
Authorization: Bearer {token}
Content-Type: application/json
```

Request Body:
```json
{
  "status": "dipanggil"
}
```

#### 6. Panggil Antrian (Protected - Petugas Only)

**POST** `/api/antrian/{id}/panggil`

Headers:
```
Authorization: Bearer {token}
```

Response (200):
```json
{
  "success": true,
  "message": "Antrian berhasil dipanggil",
  "data": {
    "id": 1,
    "nomor_antrian": "A001",
    "status": "dipanggil",
    "waktu_panggil": "2024-11-07 10:05:00"
  }
}
```

#### 7. Selesaikan Antrian (Protected - Petugas Only)

**POST** `/api/antrian/{id}/selesai`

Headers:
```
Authorization: Bearer {token}
```

Response (200):
```json
{
  "success": true,
  "message": "Antrian berhasil diselesaikan",
  "data": {
    "id": 1,
    "nomor_antrian": "A001",
    "status": "selesai",
    "waktu_selesai": "2024-11-07 10:15:00"
  }
}
```

#### 8. Delete Antrian (Protected - Petugas Only)

**DELETE** `/api/antrian/{id}`

Headers:
```
Authorization: Bearer {token}
```

#### 9. Cetak Antrian (Public)

**GET** `/api/antrian/cetak/{id}`

Response: HTML untuk print antrian

## 🧪 Testing dengan Postman

### Setup Postman

1. **Buka Postman** atau download di [postman.com](https://www.postman.com/downloads/)

2. **Buat Collection Baru**
   - Klik "New" → "Collection"
   - Beri nama "Sistem Antrian API"

3. **Set Base URL**
   - Klik collection → Variables
   - Tambahkan variable:
     - Variable: `base_url`
     - Initial Value: `http://127.0.0.1:8000/api`

### Testing Flow Lengkap

#### Step 1: Register User Pasien

1. Buat request baru: **POST** `{{base_url}}/register`
2. Pilih tab "Body" → "raw" → "JSON"
3. Masukkan:
```json
{
  "name": "Pasien Test",
  "email": "pasien@test.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "pasien"
}
```
4. Klik "Send"
5. **Simpan token** dari response untuk digunakan nanti

#### Step 2: Register User Petugas

1. Buat request baru: **POST** `{{base_url}}/register`
2. Body:
```json
{
  "name": "Petugas Test",
  "email": "petugas@test.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "petugas"
}
```
3. Klik "Send"
4. **Simpan token petugas**

#### Step 3: Login

1. Buat request: **POST** `{{base_url}}/login`
2. Body:
```json
{
  "email": "petugas@test.com",
  "password": "password123"
}
```
3. Klik "Send"
4. Copy token dari response

#### Step 4: Set Authorization Token

Untuk request yang memerlukan autentikasi:

1. Pilih tab "Authorization"
2. Type: "Bearer Token"
3. Token: Paste token yang didapat dari login/register
4. Atau tambahkan di Headers:
   - Key: `Authorization`
   - Value: `Bearer {your_token_here}`

#### Step 5: Buat Loket (Sebagai Petugas)

1. Request: **POST** `{{base_url}}/loket`
2. Authorization: Bearer Token (token petugas)
3. Body:
```json
{
  "nama_loket": "Loket 1",
  "kode_loket": "A",
  "status": "aktif"
}
```
4. Klik "Send"
5. **Simpan loket_id** dari response

#### Step 6: Get All Lokets (Public)

1. Request: **GET** `{{base_url}}/loket`
2. Tidak perlu authorization
3. Klik "Send"

#### Step 7: Buat Antrian (Sebagai Pasien)

1. Request: **POST** `{{base_url}}/antrian`
2. Authorization: Bearer Token (token pasien)
3. Body:
```json
{
  "loket_id": 1,
  "nama_pasien": "John Doe",
  "nomor_hp": "08123456789",
  "alamat": "Jl. Contoh No. 123"
}
```
4. Klik "Send"
5. **Simpan antrian_id** dari response

#### Step 8: Get All Antrian

1. Request: **GET** `{{base_url}}/antrian`
2. Tidak perlu authorization
3. Klik "Send"

#### Step 9: Get Antrian by Loket

1. Request: **GET** `{{base_url}}/antrian/loket/1`
2. Ganti `1` dengan loket_id yang sesuai
3. Klik "Send"

#### Step 10: Panggil Antrian (Sebagai Petugas)

1. Request: **POST** `{{base_url}}/antrian/1/panggil`
2. Authorization: Bearer Token (token petugas)
3. Ganti `1` dengan antrian_id
4. Klik "Send"

#### Step 11: Selesaikan Antrian (Sebagai Petugas)

1. Request: **POST** `{{base_url}}/antrian/1/selesai`
2. Authorization: Bearer Token (token petugas)
3. Ganti `1` dengan antrian_id
4. Klik "Send"

#### Step 12: Get Current User

1. Request: **GET** `{{base_url}}/user`
2. Authorization: Bearer Token
3. Klik "Send"

#### Step 13: Logout

1. Request: **POST** `{{base_url}}/logout`
2. Authorization: Bearer Token
3. Klik "Send"

### Tips Testing Postman

1. **Gunakan Environment Variables**
   - Simpan `base_url`, `token_pasien`, `token_petugas`
   - Mudah switch antar environment (dev, staging, production)

2. **Gunakan Tests Tab**
   - Auto-save token setelah login:
   ```javascript
   var jsonData = pm.response.json();
   pm.environment.set("token", jsonData.token);
   ```

3. **Gunakan Pre-request Script**
   - Auto-set headers atau generate data

4. **Organize dengan Folders**
   - Folder "Auth" untuk endpoint autentikasi
   - Folder "Loket" untuk endpoint loket
   - Folder "Antrian" untuk endpoint antrian

### Expected Response Codes

- **200 OK** - Request berhasil
- **201 Created** - Resource berhasil dibuat
- **400 Bad Request** - Validation error
- **401 Unauthorized** - Token tidak valid/tidak ada
- **403 Forbidden** - Tidak punya akses (role tidak sesuai)
- **404 Not Found** - Resource tidak ditemukan
- **422 Unprocessable Entity** - Validation error
- **500 Internal Server Error** - Server error

## 🐛 Troubleshooting

### Error: "SQLSTATE[HY000]: General error: 1 no such table"

**Solusi**: Jalankan migration
```bash
php artisan migrate
```

### Error: "Unauthenticated"

**Solusi**: 
- Pastikan token valid dan belum expired
- Cek header Authorization: `Bearer {token}`
- Login ulang untuk mendapat token baru

### Error: "This action is unauthorized"

**Solusi**:
- Pastikan role user sesuai (petugas untuk aksi tertentu)
- Cek middleware di route

### Error: "The given data was invalid"

**Solusi**:
- Cek validation rules di request
- Pastikan semua field required terisi
- Cek format data (email, nomor HP, dll)

### Port 8000 sudah digunakan

**Solusi**: Gunakan port lain
```bash
php artisan serve --port=8080
```

### Database locked error

**Solusi**: 
- Tutup semua koneksi database
- Restart server
- Atau gunakan MySQL instead of SQLite

### CORS Error

**Solusi**: Install dan konfigurasi Laravel CORS
```bash
composer require fruitcake/laravel-cors
```

## 📝 Catatan Penting

1. **Token Expiration**: Token Sanctum tidak expired secara default. Untuk production, set expiration di `config/sanctum.php`

2. **Rate Limiting**: API memiliki rate limiting default Laravel (60 requests per minute)

3. **Database Backup**: Backup database SQLite secara berkala:
   ```bash
   copy database\database.sqlite database\backup.sqlite
   ```

4. **Environment**: Jangan commit file `.env` ke repository

5. **Security**: 
   - Gunakan HTTPS di production
   - Set `APP_DEBUG=false` di production
   - Generate strong `APP_KEY`

## 📚 Dokumentasi Tambahan

- [Laravel Documentation](https://laravel.com/docs)
- [Laravel Sanctum](https://laravel.com/docs/sanctum)
- [Postman Documentation](https://learning.postman.com/)

## 📧 Support

Jika ada pertanyaan atau masalah, silakan buat issue atau hubungi tim development.

---

**Happy Coding! 🚀**
