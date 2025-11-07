# ⚙️ Environment Configuration Guide

Panduan konfigurasi file `.env` untuk Backend dan Frontend.

## 🔧 Backend Laravel (.env)

### Lokasi File
```
backend-laravel/.env
```

### Konfigurasi Penting

#### Application
```env
APP_NAME="Sistem Antrian RS - Backend"
APP_ENV=local
APP_KEY=base64:xxxxxxxxxxxxx  # Generate dengan: php artisan key:generate
APP_DEBUG=true
APP_URL=http://localhost:8000
```

#### Database
```env
# Untuk MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=antrian_rs
DB_USERNAME=root
DB_PASSWORD=

# Atau untuk SQLite (lebih mudah untuk development)
DB_CONNECTION=sqlite
# DB_HOST, DB_PORT, dll tidak perlu untuk SQLite
```

#### CORS
```env
# Tidak perlu di .env, sudah di config/cors.php
# Tapi pastikan frontend URL sesuai
```

#### Sanctum (Authentication)
```env
SANCTUM_STATEFUL_DOMAINS=localhost:8001,127.0.0.1:8001
SESSION_DRIVER=cookie
SESSION_DOMAIN=localhost
```

### Full Example Backend .env
```env
APP_NAME="Sistem Antrian RS - Backend"
APP_ENV=local
APP_KEY=base64:xxxxxxxxxxxxx
APP_DEBUG=true
APP_URL=http://localhost:8000

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=sqlite

BROADCAST_CONNECTION=log
CACHE_STORE=database
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database
SESSION_DRIVER=cookie
SESSION_LIFETIME=120

SANCTUM_STATEFUL_DOMAINS=localhost:8001,127.0.0.1:8001
```

---

## 🎨 Frontend Livewire (.env)

### Lokasi File
```
frontend-livewire/.env
```

### Konfigurasi Penting

#### Application
```env
APP_NAME="Sistem Antrian RS"
APP_ENV=local
APP_KEY=base64:xxxxxxxxxxxxx  # Generate dengan: php artisan key:generate
APP_DEBUG=true
APP_URL=http://localhost:8001
```

#### Database (SQLite untuk session)
```env
DB_CONNECTION=sqlite
# Tidak perlu DB_HOST, DB_PORT, dll untuk SQLite
```

#### Backend API Connection (PENTING!)
```env
# URL backend API tanpa trailing slash
API_BASE_URL=http://localhost:8000/api
```

#### Session
```env
SESSION_DRIVER=database
SESSION_LIFETIME=120
```

### Full Example Frontend .env
```env
APP_NAME="Sistem Antrian RS"
APP_ENV=local
APP_KEY=base64:xxxxxxxxxxxxx
APP_DEBUG=true
APP_URL=http://localhost:8001

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=sqlite

BROADCAST_CONNECTION=log
CACHE_STORE=database
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

SESSION_DRIVER=database
SESSION_LIFETIME=120

# Backend API Configuration (PENTING!)
API_BASE_URL=http://localhost:8000/api

VITE_APP_NAME="${APP_NAME}"
```

---

## 🔍 Penjelasan Konfigurasi

### APP_NAME
- Nama aplikasi yang ditampilkan
- Bisa berbeda untuk backend dan frontend

### APP_ENV
- `local` untuk development
- `production` untuk production
- Mempengaruhi error display dan caching

### APP_DEBUG
- `true` untuk development (tampilkan error detail)
- `false` untuk production (hide error detail)

### APP_URL
- URL base aplikasi
- Backend: `http://localhost:8000`
- Frontend: `http://localhost:8001`

### DB_CONNECTION
- `mysql` untuk MySQL/MariaDB
- `sqlite` untuk SQLite (recommended untuk development)
- `pgsql` untuk PostgreSQL

### API_BASE_URL (Frontend Only)
- **SANGAT PENTING!**
- URL lengkap ke backend API
- Format: `http://localhost:8000/api`
- Tanpa trailing slash
- Digunakan oleh `ApiService.php`

### SESSION_DRIVER
- `database` - simpan session di database (recommended)
- `file` - simpan session di file
- `cookie` - simpan session di cookie

---

## 🚨 Common Mistakes

### ❌ Salah
```env
# Frontend .env
API_BASE_URL=http://localhost:8000/api/  # Ada trailing slash
API_BASE_URL=localhost:8000/api          # Tidak ada http://
API_BASE_URL=http://localhost:8000      # Tidak ada /api
```

### ✅ Benar
```env
# Frontend .env
API_BASE_URL=http://localhost:8000/api
```

---

## 🔄 Setelah Mengubah .env

### Backend
```bash
cd backend-laravel
php artisan config:clear
php artisan cache:clear
php artisan serve
```

### Frontend
```bash
cd frontend-livewire
php artisan config:clear
php artisan cache:clear
php artisan serve --port=8001
```

---

## 🌐 Production Configuration

### Backend Production .env
```env
APP_NAME="Sistem Antrian RS - Backend"
APP_ENV=production
APP_KEY=base64:xxxxxxxxxxxxx
APP_DEBUG=false  # PENTING: false untuk production
APP_URL=https://api.yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=antrian_rs_prod
DB_USERNAME=prod_user
DB_PASSWORD=strong_password_here

SESSION_DRIVER=database
CACHE_STORE=redis  # Gunakan redis untuk production
QUEUE_CONNECTION=redis

SANCTUM_STATEFUL_DOMAINS=yourdomain.com,www.yourdomain.com
```

### Frontend Production .env
```env
APP_NAME="Sistem Antrian RS"
APP_ENV=production
APP_KEY=base64:xxxxxxxxxxxxx
APP_DEBUG=false  # PENTING: false untuk production
APP_URL=https://yourdomain.com

DB_CONNECTION=sqlite

API_BASE_URL=https://api.yourdomain.com/api  # HTTPS untuk production

SESSION_DRIVER=database
CACHE_STORE=redis
```

---

## 🔐 Security Best Practices

### 1. APP_KEY
- **Jangan share** APP_KEY
- Generate baru untuk setiap environment
- Gunakan `php artisan key:generate`

### 2. APP_DEBUG
- **HARUS false** di production
- Jangan expose error detail ke user

### 3. Database Credentials
- Gunakan password yang kuat
- Jangan commit `.env` ke git
- Gunakan environment variables di server

### 4. CORS
- Hanya allow domain yang diperlukan
- Jangan gunakan `*` di production

---

## 📝 Checklist Setup .env

### Backend
- [ ] Copy `.env.example` ke `.env`
- [ ] Generate `APP_KEY`
- [ ] Set `APP_URL` yang benar
- [ ] Konfigurasi database
- [ ] Set `SANCTUM_STATEFUL_DOMAINS`
- [ ] Test dengan `php artisan config:show`

### Frontend
- [ ] Copy `.env.example` ke `.env`
- [ ] Generate `APP_KEY`
- [ ] Set `APP_URL` yang benar
- [ ] Set `API_BASE_URL` yang benar (PENTING!)
- [ ] Konfigurasi database (SQLite)
- [ ] Test dengan `php artisan config:show`

---

## 🧪 Testing Configuration

### Test Backend
```bash
# Check config
php artisan config:show

# Test database connection
php artisan migrate:status

# Test API
curl http://localhost:8000/api/loket
```

### Test Frontend
```bash
# Check config
php artisan config:show

# Check API_BASE_URL
php artisan tinker
>>> config('app.api_base_url')  # Should not be null
>>> env('API_BASE_URL')
```

### Test Integration
```bash
# Dari frontend, test API connection
curl http://localhost:8000/api/loket

# Buka browser
http://localhost:8001
# Cek console (F12) untuk error
```

---

## 📞 Troubleshooting

### Config Cached
```bash
# Jika perubahan .env tidak apply
php artisan config:clear
php artisan cache:clear
```

### API_BASE_URL Not Working
```bash
# Frontend
1. Check .env file
2. Run: php artisan config:clear
3. Check app/Services/ApiService.php
4. Restart server
```

### Database Connection Error
```bash
# Check .env
DB_CONNECTION=sqlite

# For SQLite, create database file
touch database/database.sqlite

# Run migration
php artisan migrate
```

---

**Catatan:** File `.env` tidak boleh di-commit ke git. Gunakan `.env.example` sebagai template.
