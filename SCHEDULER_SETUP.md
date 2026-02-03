# Setup Scheduler Otomatis untuk Update Status Booking

## Cara Kerja
Sistem akan otomatis:
1. ✅ Mengaktifkan booking yang tanggal mulainya sudah tiba (confirmed → active)
2. ✅ Menyelesaikan booking yang tanggal selesainya sudah lewat (active → completed)
3. ✅ Membebaskan motor yang booking-nya sudah selesai

## Opsi 1: Jalankan Manual (untuk Testing)

Test command secara manual:
```bash
php artisan bookings:update-status
```

## Opsi 2: Auto-Run dengan Batch File (Recommended untuk Development)

1. **Double-click file `scheduler.bat`** di folder project
2. Window command prompt akan terbuka dan terus berjalan
3. Scheduler akan cek setiap 1 menit
4. **JANGAN TUTUP WINDOW** ini selama aplikasi berjalan
5. Log tersimpan di `storage/logs/scheduler.log`

## Opsi 3: Windows Task Scheduler (untuk Production)

### Setup Task Scheduler:

1. Buka **Task Scheduler** Windows (tekan Win + R, ketik `taskschd.msc`)

2. Klik **Create Basic Task**

3. Isi detail task:
   - Name: `Laravel Booking Scheduler`
   - Description: `Auto-update status booking setiap jam`

4. Trigger: **Daily** (atau **One time** jika ingin selalu berjalan)
   - Start: Hari ini
   - Recur every: 1 days
   - Centang "Repeat task every": **1 hour**
   - For a duration of: **Indefinitely**

5. Action: **Start a program**
   - Program/script: `C:\php\php.exe` (sesuaikan dengan lokasi PHP Anda)
   - Add arguments: `artisan schedule:run`
   - Start in: `C:\Users\Erfan_Maulana\rental-motor` (folder project Anda)

6. Finish dan test dengan klik **Run**

## Opsi 4: Run di Background (Paling Simple)

Buka terminal baru dan jalankan:
```bash
php artisan schedule:work
```

Command ini akan terus berjalan dan execute scheduler setiap 1 menit.

## Verifikasi

Cek apakah scheduler berjalan:
```bash
# Lihat log scheduler
type storage\logs\scheduler.log

# Test manual update
php artisan bookings:update-status
```

## Troubleshooting

### Scheduler tidak jalan?
- Pastikan file `scheduler.bat` running
- Atau pastikan Task Scheduler aktif
- Atau gunakan `php artisan schedule:work`

### Status tidak berubah?
- Cek tanggal di database booking
- Pastikan tanggal sistem Windows benar
- Jalankan manual: `php artisan bookings:update-status`

## Catatan Penting

⚠️ **Untuk Development**: Gunakan Opsi 2 atau 4 (simple dan mudah)
⚠️ **Untuk Production**: Gunakan Opsi 3 (Task Scheduler) atau setup Cron Job di server Linux

Scheduler akan cek setiap jam, jadi status booking akan otomatis update tanpa perlu klik manual!
