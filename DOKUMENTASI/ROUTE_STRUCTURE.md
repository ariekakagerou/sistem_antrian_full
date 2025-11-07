# 🗺️ Route Structure - Sistem Antrian Rumah Sakit

Dokumentasi struktur route untuk Backend dan Frontend.

## 🔧 Backend Routes

### Web Routes (`backend-laravel/routes/web.php`)

```php
// Homepage
GET  /                              → Welcome page

// Google OAuth (Web Routes - PENTING!)
GET  /auth/google                   → Redirect ke Google login
GET  /auth/google/callback          → Callback dari Google
```

**Kenapa Google OAuth di `web.php`?**
- OAuth redirect memerlukan session
- Laravel Socialite bekerja optimal dengan web middleware
- URL lebih clean: `http://localhost:8000/auth/google` (tanpa `/api`)

---

### API Routes (`backend-laravel/routes/api.php`)

**Prefix:** `/api`

#### Loket (Public)
```php
GET     /api/loket                  → List semua loket
GET     /api/loket/{id}             → Detail loket
POST    /api/loket                  → Buat loket (auth)
PUT     /api/loket/{id}             → Update loket (auth)
DELETE  /api/loket/{id}             → Hapus loket (auth)
```

#### Antrian (Public)
```php
GET     /api/antrian                → List semua antrian
GET     /api/antrian/{id}           → Detail antrian
GET     /api/antrian/loket/{id}     → Antrian per loket
POST    /api/antrian                → Buat antrian baru
```

#### Antrian (Protected - Auth Required)
```php
PUT     /api/antrian/{id}           → Update antrian
DELETE  /api/antrian/{id}           → Hapus antrian
POST    /api/antrian/{id}/panggil   → Panggil antrian
POST    /api/antrian/{id}/selesai   → Selesaikan antrian
```

#### Authentication
```php
POST    /api/login                  → Login dengan email/password
POST    /api/logout                 → Logout (auth)
GET     /api/user                   → Get user info (auth)
POST    /api/register               → Register user baru
```

#### Testing
```php
GET     /api/test-auth              → Test authentication (auth)
```

---

## 🎨 Frontend Routes

### Web Routes (`frontend-livewire/routes/web.php`)

```php
GET  /                              → Halaman Pasien (PasienAntrian)
GET  /petugas                       → Dashboard Petugas (PetugasLoket)
GET  /display                       → Display Antrian (DisplayAntrian)
GET  /auth/google/callback          → Callback Google OAuth (GoogleCallback)
```

---

## 🔄 OAuth Flow - Route Mapping

### Flow Lengkap:

```
1. User di Frontend
   http://localhost:8001/petugas
   ↓ Klik "Login dengan Google"

2. Redirect ke Backend (WEB ROUTE)
   http://127.0.0.1:8000/auth/google
   ↓ Backend redirect ke Google

3. Google OAuth Page
   https://accounts.google.com/...
   ↓ User login & authorize

4. Google callback ke Backend (WEB ROUTE)
   http://127.0.0.1:8000/auth/google/callback
   ↓ Backend process & generate token

5. Backend redirect ke Frontend
   http://localhost:8001/auth/google/callback?token=xxx
   ↓ Frontend save token

6. Frontend redirect ke Dashboard
   http://localhost:8001/petugas
   ✅ User logged in
```

---

## 🛡️ Middleware

### Backend

#### Web Middleware (web.php)
- `web` - Session, CSRF, Cookie
- Digunakan untuk: Google OAuth routes

#### API Middleware (api.php)
- `api` - Stateless, Rate limiting
- `auth:sanctum` - Token authentication
- `role.petugas` - Role-based access

### Frontend

#### Web Middleware
- `web` - Session, CSRF, Cookie
- Semua route menggunakan web middleware

---

## 📍 URL Mapping

### Development

| Service | Base URL | Port |
|---------|----------|------|
| Backend API | http://localhost:8000/api | 8000 |
| Backend Web | http://localhost:8000 | 8000 |
| Frontend | http://localhost:8001 | 8001 |

### Important URLs

| Purpose | URL |
|---------|-----|
| Pasien Form | http://localhost:8001 |
| Petugas Dashboard | http://localhost:8001/petugas |
| Display Monitor | http://localhost:8001/display |
| Google Login | http://127.0.0.1:8000/auth/google |
| API Loket | http://localhost:8000/api/loket |
| API Antrian | http://localhost:8000/api/antrian |

---

## 🔍 Route Testing

### Test Backend Web Routes
```bash
# Test Google OAuth redirect
curl -L http://127.0.0.1:8000/auth/google
# Should redirect to Google
```

### Test Backend API Routes
```bash
# Test loket endpoint
curl http://localhost:8000/api/loket

# Test login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"petugas@example.com","password":"password"}'
```

### Test Frontend Routes
```bash
# Open in browser
http://localhost:8001
http://localhost:8001/petugas
http://localhost:8001/display
```

---

## 🐛 Common Issues

### Issue: Google OAuth 404
**Cause:** Route ada di `api.php` bukan `web.php`
**Solution:** Pastikan route ada di `backend-laravel/routes/web.php`

### Issue: CORS Error
**Cause:** Frontend tidak bisa akses backend API
**Solution:** 
1. Cek `backend-laravel/config/cors.php`
2. Pastikan `allowed_origins` include `http://localhost:8001`

### Issue: Token Not Working
**Cause:** Token tidak tersimpan atau expired
**Solution:**
1. Cek session di browser
2. Clear cache: `php artisan config:clear`
3. Restart backend

### Issue: Redirect URI Mismatch
**Cause:** Google Console redirect URI tidak match
**Solution:**
1. Google Console → Credentials
2. Set redirect URI: `http://127.0.0.1:8000/auth/google/callback`
3. Gunakan `127.0.0.1` bukan `localhost`

---

## 📝 Route Checklist

### Backend Setup
- [ ] Route Google OAuth ada di `web.php` (bukan `api.php`)
- [ ] Route API ada di `api.php` dengan prefix `/api`
- [ ] Middleware sudah benar
- [ ] CORS configured

### Frontend Setup
- [ ] Route callback Google OAuth ada
- [ ] Semua Livewire components registered
- [ ] API_BASE_URL configured di .env

### Testing
- [ ] Backend web routes accessible
- [ ] Backend API routes accessible
- [ ] Frontend routes accessible
- [ ] Google OAuth flow working
- [ ] Token authentication working

---

## 💡 Best Practices

1. **Pisahkan Web dan API Routes**
   - OAuth → `web.php`
   - REST API → `api.php`

2. **Gunakan Middleware yang Tepat**
   - Session-based → `web` middleware
   - Token-based → `api` + `auth:sanctum`

3. **Consistent URL Structure**
   - API: `/api/resource`
   - Web: `/resource`
   - OAuth: `/auth/provider`

4. **Security**
   - Protect sensitive routes dengan middleware
   - Validate input
   - Use HTTPS in production

---

**Last Updated:** 5 November 2024  
**Version:** 1.0.0
