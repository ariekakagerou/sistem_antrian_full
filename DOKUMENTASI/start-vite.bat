@echo off
echo ========================================
echo   Starting Vite Dev Server
echo ========================================
echo.

cd frontend-livewire

echo Checking if node_modules exists...
if not exist "node_modules\" (
    echo Installing npm dependencies...
    call npm install
)

echo.
echo Starting Vite Development Server...
echo Vite will compile assets for frontend
echo.
echo Press Ctrl+C to stop the server
echo ========================================
echo.

npm run dev

pause
