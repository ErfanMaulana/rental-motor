@echo off
color 0E
echo.
echo ========================================
echo   TEST AUTO BOOKING SCHEDULER
echo ========================================
echo.
echo Menjalankan update status booking...
echo.

cd /d "%~dp0"
php artisan bookings:update-status

echo.
echo ========================================
echo.
pause
