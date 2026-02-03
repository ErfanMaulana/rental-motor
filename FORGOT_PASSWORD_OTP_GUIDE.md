# Panduan Fitur Lupa Password dengan OTP

## Deskripsi
Fitur forgot password telah diperbarui dengan sistem OTP (One-Time Password) yang memungkinkan user untuk langsung mereset password tanpa perlu klik link di email.

## Flow Proses

### 1. **Input Email**
- User mengakses halaman `/forgot-password`
- User memasukkan email yang terdaftar
- Klik tombol "Kirim Kode OTP"

### 2. **Kirim OTP**
- Sistem akan mengirim kode OTP 6 digit ke email
- OTP berlaku selama 5 menit
- OTP disimpan di tabel `password_reset_tokens`

### 3. **Verifikasi OTP & Reset Password**
- User memasukkan kode OTP yang diterima
- User memasukkan password baru (minimal 8 karakter)
- User mengkonfirmasi password baru
- Klik tombol "Reset Password"

### 4. **Validasi & Update**
- Sistem memvalidasi OTP (kecocokan & expired time)
- Jika valid, password user akan diupdate
- OTP akan dihapus dari database
- User diarahkan ke halaman login

## Fitur Keamanan

1. **OTP Expiration**: Kode OTP hanya berlaku 5 menit
2. **One-Time Use**: Setiap OTP hanya bisa digunakan sekali
3. **Email Verification**: Hanya email terdaftar yang bisa menerima OTP
4. **Password Strength**: Password minimal 8 karakter
5. **Password Confirmation**: Validasi konfirmasi password

## File yang Dimodifikasi

1. **Controller**: `app/Http/Controllers/Auth/PasswordResetLinkController.php`
   - Method `store()`: Mengirim OTP ke email
   - Method `verifyAndReset()`: Verifikasi OTP dan reset password

2. **View**: `resources/views/auth/forgot-password.blade.php`
   - Multi-step form (Email → OTP & Password)
   - Validasi client-side
   - AJAX request untuk smooth UX

3. **Routes**: `routes/auth.php`
   - Route baru: `POST /verify-reset-password`

## Testing

Untuk menguji fitur ini:

1. Pastikan server Laravel berjalan: `php artisan serve`
2. Pastikan email configuration sudah diatur di `.env`
3. Akses `http://127.0.0.1:8000/forgot-password`
4. Masukkan email yang terdaftar
5. Cek email untuk kode OTP
6. Masukkan OTP dan password baru
7. Login dengan password baru

## Catatan Penting

- Pastikan mail configuration di `.env` sudah benar:
  ```
  MAIL_MAILER=smtp
  MAIL_HOST=smtp.mailtrap.io (atau smtp lainnya)
  MAIL_PORT=2525
  MAIL_USERNAME=your-username
  MAIL_PASSWORD=your-password
  MAIL_ENCRYPTION=tls
  MAIL_FROM_ADDRESS=noreply@fannrental.com
  MAIL_FROM_NAME="FannRental"
  ```

- Jika testing di local, bisa gunakan Mailtrap atau MailHog
- OTP dikirim dalam plain text via email (untuk production, pertimbangkan HTML email yang lebih menarik)

## Troubleshooting

### Email tidak terkirim
- Cek konfigurasi mail di `.env`
- Cek log Laravel: `storage/logs/laravel.log`
- Test koneksi SMTP

### OTP tidak valid
- Pastikan kode diketik dengan benar (6 digit)
- Cek apakah OTP sudah expired (5 menit)
- Minta OTP baru dengan ubah email

### Password tidak terupdate
- Cek validasi password (minimal 8 karakter)
- Pastikan password confirmation match
- Cek database connection
