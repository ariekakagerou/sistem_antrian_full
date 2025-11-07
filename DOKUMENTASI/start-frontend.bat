@echo off
echo ========================================
echo   Starting Frontend Livewire
echo ========================================
echo.

cd frontend-livewire

echo Checking if frontend is ready...
if not exist "vendor\" (
    echo Installing composer dependencies...
    composer install
)

if not exist "node_modules\" (
    echo Installing npm dependencies...
    call npm install
)

if not exist ".env" (
    echo Creating .env file...
    copy .env.example .env
    php artisan key:generate
)

echo.
echo Starting Laravel Frontend Server...
echo Frontend will run at: http://localhost:8001
echo.
echo Press Ctrl+C to stop the server
echo ========================================
echo.

php artisan serve --port=8001

pause
