@echo off
title Laravel Booking Auto-Update Scheduler
color 0A
echo.
echo ============================================
echo  RENTAL MOTOR - AUTO BOOKING SCHEDULER
echo ============================================
echo.
echo Status: RUNNING
echo Update: Setiap 1 menit
echo.
echo Scheduler akan otomatis:
echo  [+] Aktifkan booking saat tanggal mulai tiba
echo  [+] Selesaikan booking saat tanggal selesai lewat
echo  [+] Bebaskan motor yang sudah selesai disewa
echo.
echo JANGAN TUTUP WINDOW INI!
echo.
echo ============================================
echo.

cd /d "%~dp0"

:loop
echo [%date% %time%] Checking bookings...
php artisan bookings:update-status
echo.
timeout /t 60 /nobreak > nul
goto loop
