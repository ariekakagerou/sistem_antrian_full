@echo off
echo Starting Frontend Livewire...
cd /d "c:\Users\LENOVO\sistem antrian rumah sakit\frontend-livewire"
set API_BASE_URL=http://localhost:8002/api
php artisan serve --host=0.0.0.0 --port=8001
