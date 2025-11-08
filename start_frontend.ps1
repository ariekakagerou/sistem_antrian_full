$env:API_BASE_URL="http://localhost:8000/api"
Write-Host "Starting Frontend with API_BASE_URL: $env:API_BASE_URL"
cd "c:\Users\LENOVO\sistem antrian rumah sakit\frontend-livewire"
php artisan serve --host=127.0.0.1 --port=8001
