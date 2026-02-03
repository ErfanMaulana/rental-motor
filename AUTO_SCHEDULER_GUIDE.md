# 🚀 CARA MENJALANKAN AUTO SCHEDULER

Sistem booking sekarang **OTOMATIS** update status tanpa perlu klik manual!

## ⚡ Quick Start (Paling Mudah)

### Pilih salah satu:

### 1️⃣ **Double-click `run-scheduler.bat`**
- Window akan terbuka dan menampilkan status
- Biarkan window tetap terbuka
- Scheduler cek setiap 1 menit

### 2️⃣ **Double-click `start-scheduler-hidden.vbs`** (Recommended)
- Scheduler berjalan di background
- Tidak ada window yang muncul
- Untuk stop: buka Task Manager → cari `php.exe` → End Task

### 3️⃣ **Jalankan via Terminal**
```bash
# Buka terminal di folder project, lalu:
php artisan schedule:work
```

## ✅ Yang Dilakukan Scheduler

Setiap 1 menit, sistem otomatis:

1. **Auto-Activate Booking** 
   - Status: `confirmed` → `active`
   - Ketika: Tanggal mulai sewa sudah tiba

2. **Auto-Complete Booking**
   - Status: `active` → `completed`
   - Ketika: Tanggal selesai sewa sudah lewat

3. **Auto-Free Motor**
   - Status motor: `rented` → `available`
   - Ketika: Booking selesai dan tidak ada booking aktif lain

## 🧪 Test Manual

Test apakah scheduler berfungsi:
```bash
php artisan bookings:update-status
```

## 📊 Lihat Log

Cek log scheduler:
```bash
type storage\logs\scheduler.log
```

## 🛑 Stop Scheduler

- **Jika pakai run-scheduler.bat**: Tutup window command prompt
- **Jika pakai start-scheduler-hidden.vbs**: 
  1. Buka Task Manager (Ctrl+Shift+Esc)
  2. Cari process `php.exe`
  3. Klik End Task

## ⚙️ Setup untuk Production (Optional)

Untuk server atau jika ingin lebih permanen, setup Windows Task Scheduler:

1. Buka Task Scheduler (Win+R → `taskschd.msc`)
2. Create Basic Task
3. Name: `Laravel Scheduler`
4. Trigger: Daily, repeat every 1 hour
5. Action: Start program
   - Program: `C:\php\php.exe`
   - Arguments: `artisan schedule:run`
   - Start in: `C:\Users\Erfan_Maulana\rental-motor`

## 💡 Tips

- ✅ Jalankan scheduler setelah start server Laravel (`php artisan serve`)
- ✅ Biarkan scheduler tetap berjalan selama aplikasi digunakan
- ✅ Scheduler akan cek setiap 1 menit secara otomatis
- ✅ Tidak perlu restart scheduler kecuali ada perubahan code

## 🆘 Troubleshooting

**Scheduler tidak berjalan?**
- Pastikan PHP bisa diakses dari command line
- Cek error di `storage\logs\scheduler.log`
- Test manual dengan `php artisan bookings:update-status`

**Status tidak berubah otomatis?**
- Pastikan tanggal di database sesuai
- Pastikan scheduler sedang berjalan
- Cek Task Manager apakah ada process `php.exe`

---

**PENTING**: Untuk development, cukup jalankan `run-scheduler.bat` atau `start-scheduler-hidden.vbs` sekali saja!
