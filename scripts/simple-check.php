<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Checking Bookings vs Revenue Sharing...\n\n";

$bookings = DB::table('bookings')
    ->select('id', 'renter_id', 'status')
    ->orderBy('id')
    ->get();

$revenueSharing = DB::table('revenue_sharings')
    ->select('booking_id')
    ->get()
    ->pluck('booking_id')
    ->toArray();

echo "Total Bookings: " . $bookings->count() . "\n";
echo "Total Revenue Sharing: " . count($revenueSharing) . "\n\n";

echo "Bookings without Revenue Sharing:\n";
foreach ($bookings as $booking) {
    if (!in_array($booking->id, $revenueSharing)) {
        echo "- Booking ID: {$booking->id}, Status: {$booking->status}\n";
    }
}

echo "\nDone!\n";
