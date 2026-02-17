<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Checking completed bookings with pending revenue sharing...\n";
echo str_repeat('=', 80) . "\n\n";

$results = DB::table('bookings')
    ->join('revenue_sharings', 'bookings.id', '=', 'revenue_sharings.booking_id')
    ->join('users', 'bookings.renter_id', '=', 'users.id')
    ->join('motors', 'bookings.motor_id', '=', 'motors.id')
    ->select(
        'bookings.id as booking_id',
        'bookings.status as booking_status',
        'bookings.end_date',
        'revenue_sharings.id as revenue_id',
        'revenue_sharings.status as revenue_status',
        'revenue_sharings.total_amount',
        'users.name as renter_name',
        'motors.brand',
        'motors.model'
    )
    ->where('bookings.status', 'completed')
    ->where('revenue_sharings.status', 'pending')
    ->get();

if ($results->isEmpty()) {
    echo "✅ Tidak ada masalah! Semua booking completed sudah paid.\n";
} else {
    echo "Found " . $results->count() . " booking(s) with status completed but revenue still pending:\n\n";
    
    foreach ($results as $item) {
        echo "Booking #{$item->booking_id}:\n";
        echo "  - Penyewa: {$item->renter_name}\n";
        echo "  - Motor: {$item->brand} {$item->model}\n";
        echo "  - Booking Status: {$item->booking_status}\n";
        echo "  - End Date: {$item->end_date}\n";
        echo "  - Revenue ID: {$item->revenue_id}\n";
        echo "  - Revenue Status: {$item->revenue_status} ❌ (should be 'paid')\n";
        echo "  - Amount: Rp " . number_format($item->total_amount, 0, ',', '.') . "\n";
        echo "\n";
    }
}

echo str_repeat('=', 80) . "\n";
