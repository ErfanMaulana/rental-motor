<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Detail Booking #7:\n";
echo str_repeat('=', 80) . "\n";

$booking = DB::table('bookings')->where('id', 7)->first();
echo "Booking ID: {$booking->id}\n";
echo "Renter ID: {$booking->renter_id}\n";
echo "Motor ID: {$booking->motor_id}\n";
echo "Status: {$booking->status}\n";
echo "Price: Rp " . number_format($booking->price, 0, ',', '.') . "\n";
echo "Start Date: {$booking->start_date}\n";
echo "End Date: {$booking->end_date}\n";

echo "\nPayment Info:\n";
$payment = DB::table('payments')->where('booking_id', 7)->first();
if ($payment) {
    echo "Payment ID: {$payment->id}\n";
    echo "Amount: Rp " . number_format($payment->amount, 0, ',', '.') . "\n";
    echo "Status: {$payment->status}\n";
    echo "Verified At: " . ($payment->verified_at ?? 'BELUM VERIFIED') . "\n";
    echo "Verified By: " . ($payment->verified_by ?? '-') . "\n";
} else {
    echo "BELUM ADA PAYMENT RECORD\n";
}

echo "\nRevenue Sharing Info:\n";
$revenue = DB::table('revenue_sharings')->where('booking_id', 7)->first();
if ($revenue) {
    echo "Revenue Sharing ID: {$revenue->id}\n";
    echo "Total Amount: Rp " . number_format($revenue->total_amount, 0, ',', '.') . "\n";
    echo "Admin Commission: Rp " . number_format($revenue->admin_commission, 0, ',', '.') . "\n";
    echo "Owner Amount: Rp " . number_format($revenue->owner_amount, 0, ',', '.') . "\n";
    echo "Status: {$revenue->status}\n";
} else {
    echo "TIDAK ADA REVENUE SHARING - Payment belum diverifikasi\n";
}

echo str_repeat('=', 80) . "\n";
