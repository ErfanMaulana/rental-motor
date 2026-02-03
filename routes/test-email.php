<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Test Email Route
|--------------------------------------------------------------------------
|
| Route untuk test apakah konfigurasi email sudah benar
| Akses: http://127.0.0.1:8000/test-email?email=your@email.com
|
*/

Route::get('/test-email', function () {
    $email = request('email', 'test@example.com');
    
    try {
        Mail::raw('Test email dari FannRental. Jika Anda menerima email ini, konfigurasi email sudah benar!', function ($message) use ($email) {
            $message->to($email)
                ->subject('Test Email - FannRental');
        });
        
        return response()->json([
            'success' => true,
            'message' => "Email test berhasil dikirim ke {$email}. Silakan cek inbox Anda."
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengirim email: ' . $e->getMessage()
        ], 500);
    }
})->name('test.email');
