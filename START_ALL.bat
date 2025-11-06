@echo off
echo ========================================
echo   SISTEM ANTRIAN RUMAH SAKIT
echo   Starting All Services
echo ========================================
echo.
echo This will open 3 terminal windows:
echo 1. Backend API (Port 8000)
echo 2. Frontend Livewire (Port 8001)
echo 3. Vite Dev Server
echo.
echo Press any key to continue...
pause > nul

echo.
echo Starting Backend API...
start "Backend API - Port 8000" cmd /k "cd /d "%~dp0" && start-backend.bat"

timeout /t 3 /nobreak > nul

echo Starting Frontend Livewire...
start "Frontend Livewire - Port 8001" cmd /k "cd /d "%~dp0" && start-frontend.bat"

timeout /t 3 /nobreak > nul

echo Starting Vite Dev Server...
start "Vite Dev Server" cmd /k "cd /d "%~dp0" && start-vite.bat"

echo.
echo ========================================
echo   All services are starting!
echo ========================================
echo.
echo Backend API: http://localhost:8000
echo Frontend: http://localhost:8001
echo.
echo Wait a few seconds for all services to start...
echo Then open your browser and go to:
echo http://localhost:8001
echo.
echo To stop all services, close all terminal windows.
echo ========================================
echo.

timeout /t 5 /nobreak

echo Opening browser...
start http://localhost:8001

echo.
echo Press any key to exit this window...
pause > nul
