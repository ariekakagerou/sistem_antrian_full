@echo off
echo ========================================
echo   Starting Backend Laravel API
echo ========================================
echo.

cd backend-laravel

echo Checking if backend is ready...
if not exist "vendor\" (
    echo Installing composer dependencies...
    composer install
)

if not exist ".env" (
    echo Creating .env file...
    copy .env.example .env
    php artisan key:generate
)

echo.
echo Starting Laravel Backend Server...
echo Backend will run at: http://localhost:8000
echo.
echo Press Ctrl+C to stop the server
echo ========================================
echo.

php artisan serve

pause
