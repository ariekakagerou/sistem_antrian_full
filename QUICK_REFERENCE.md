# 🚀 Quick Reference - Sistem Antrian Rumah Sakit

Referensi cepat untuk command dan URL yang sering digunakan.

## 📍 URL Penting

| Halaman | URL | Deskripsi |
|---------|-----|-----------|
| **Pasien** | http://localhost:8001 | Form pendaftaran antrian |
| **Petugas** | http://localhost:8001/petugas | Dashboard petugas |
| **Display** | http://localhost:8001/display | Monitor antrian |
| **Backend API** | http://localhost:8000 | REST API |
| **API Docs** | http://localhost:8000/api/loket | Test endpoint |

## 🔐 Default Login

```
Email:    petugas@example.com
Password: password
```

## ⚡ Quick Start Commands

### Jalankan Semua (Tercepat)
```bash
# Double-click:
START_ALL.bat
```

### Jalankan Manual

**Backend:**
```bash
cd backend-laravel
php artisan serve
```

**Frontend:**
```bash
cd frontend-livewire
php artisan serve --port=8001
```

**Vite:**
```bash
cd frontend-livewire
npm run dev
```

## 🔧 Setup Commands

### Backend Setup
```bash
cd backend-laravel
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
```

### Frontend Setup
```bash
cd frontend-livewire
composer install
npm install
copy .env.example .env
php artisan key:generate
php artisan migrate
```

## 🛠️ Maintenance Commands

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### Reset Database
```bash
# Backend
php artisan migrate:fresh --seed

# Frontend
php artisan migrate:fresh
```

### Update Dependencies
```bash
# Composer
composer update

# NPM
npm update
```

## 🔍 Debugging Commands

### Check PHP Version
```bash
php --version
```

### Check Composer
```bash
composer --version
```

### Check Node & NPM
```bash
node --version
npm --version
```

### Check Port Usage
```bash
# Windows
netstat -ano | findstr :8000
netstat -ano | findstr :8001
```

### View Laravel Logs
```bash
# Backend
tail -f backend-laravel/storage/logs/laravel.log

# Frontend
tail -f frontend-livewire/storage/logs/laravel.log
```

## 🗺️ Routes

### Backend Web Routes (web.php)
```
GET    /auth/google         # Redirect ke Google login
GET    /auth/google/callback # Callback dari Google
```

### Backend API Routes (api.php - prefix: /api)

**Loket**
```
GET    /api/loket           # List semua loket
GET    /api/loket/{id}      # Detail loket
POST   /api/loket           # Buat loket (auth)
PUT    /api/loket/{id}      # Update loket (auth)
DELETE /api/loket/{id}      # Hapus loket (auth)
```

### Antrian
```
GET    /api/antrian                # List semua antrian
GET    /api/antrian/{id}           # Detail antrian
GET    /api/antrian/loket/{id}     # Antrian per loket
POST   /api/antrian                # Buat antrian (auth)
POST   /api/antrian/{id}/panggil   # Panggil antrian (auth)
POST   /api/antrian/{id}/selesai   # Selesaikan antrian (auth)
PUT    /api/antrian/{id}           # Update antrian (auth)
DELETE /api/antrian/{id}           # Hapus antrian (auth)
```

### Authentication
```
POST   /api/login          # Login
POST   /api/logout         # Logout (auth)
GET    /api/user           # Get user info (auth)
POST   /api/register       # Register
```

## 🎯 Common Tasks

### Tambah Loket Baru
```bash
# Via API atau database
# POST /api/loket
{
  "nama_loket": "Loket Umum",
  "deskripsi": "Pelayanan umum"
}
```

### Tambah User Petugas
```bash
# Via tinker
php artisan tinker

User::create([
    'name' => 'Petugas 1',
    'email' => 'petugas1@example.com',
    'password' => bcrypt('password'),
    'role' => 'petugas'
]);
```

### Test API dengan cURL
```bash
# Get loket
curl http://localhost:8000/api/loket

# Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"petugas@example.com","password":"password"}'
```

## 🐛 Common Issues & Solutions

### Port Already in Use
```bash
# Kill process on port 8000
netstat -ano | findstr :8000
taskkill /PID <PID> /F

# Or use different port
php artisan serve --port=8002
```

### CORS Error
```bash
# Check backend-laravel/config/cors.php
'allowed_origins' => ['http://localhost:8001']
```

### API Connection Failed
```bash
# Check .env in frontend
API_BASE_URL=http://localhost:8000/api

# Restart frontend
php artisan config:clear
php artisan serve --port=8001
```

### Vite Error
```bash
# Delete node_modules and reinstall
rm -rf node_modules
npm install
npm run dev
```

## 📱 Keyboard Shortcuts

| Shortcut | Action |
|----------|--------|
| `F5` | Refresh page |
| `F11` | Fullscreen (Display) |
| `F12` | Open DevTools |
| `Ctrl + Shift + R` | Hard refresh |
| `Ctrl + C` | Stop server |

## 🎨 File Locations

### Frontend
```
app/Livewire/              # Livewire components
app/Services/              # API service
resources/views/livewire/  # Blade templates
resources/views/components/# Layout components
```

### Backend
```
app/Http/Controllers/Api/  # API controllers
app/Models/                # Eloquent models
routes/api.php             # API routes
config/cors.php            # CORS config
```

## 📚 Documentation Files

| File | Purpose |
|------|---------|
| `README.md` | Overview sistem |
| `PANDUAN_SETUP.md` | Setup lengkap |
| `GOOGLE_OAUTH_SETUP.md` | Setup Google OAuth |
| `ROUTE_STRUCTURE.md` | Struktur routes |
| `CHECKLIST_SETUP.md` | Checklist verifikasi |
| `MULAI_DISINI.txt` | Quick start |
| `SUMMARY_IMPLEMENTASI.md` | Summary lengkap |
| `QUICK_REFERENCE.md` | File ini |

## 💡 Tips & Tricks

1. **Gunakan START_ALL.bat** untuk kemudahan
2. **Bookmark URL** yang sering diakses
3. **F11 untuk fullscreen** pada display
4. **Clear cache** jika ada masalah
5. **Cek console browser** untuk debug
6. **Gunakan Postman** untuk test API
7. **Git commit** sebelum perubahan besar

## 🔄 Git Commands (Opsional)

```bash
# Initialize git
git init

# Add all files
git add .

# Commit
git commit -m "Initial commit"

# Create branch
git checkout -b development

# Push to remote
git remote add origin <url>
git push -u origin main
```

---

**Last Updated:** 5 November 2024  
**Version:** 1.0.0
