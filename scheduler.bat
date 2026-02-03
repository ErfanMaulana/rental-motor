@echo off
REM Script untuk menjalankan Laravel Scheduler
REM Jalankan script ini terus menerus untuk auto-update status booking

cd /d "%~dp0"

:loop
php artisan schedule:run >> storage\logs\scheduler.log 2>&1
timeout /t 60 /nobreak > nul
goto loop