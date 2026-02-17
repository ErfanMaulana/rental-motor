<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$bookings = App\Models\Booking::with(['renter', 'payment', 'revenueSharing'])->orderBy('id')->get();

echo "Total Bookings: " . $bookings->count() . "\n";
echo "Bookings with Revenue Sharing: " . $bookings->filter(fn($b) => $b->revenueSharing)->count() . "\n\n";

echo "Booking Details:\n";
echo str_repeat('=', 100) . "\n";
printf("%-5s %-15s %-15s %-20s %-15s\n", 'ID', 'Penyewa', 'Status', 'Payment', 'Revenue Sharing');
echo str_repeat('=', 100) . "\n";

foreach ($bookings as $booking) {
    $paymentStatus = $booking->payment 
        ? ($booking->payment->verified_at ? 'Verified' : 'Belum Verified') 
        : 'Belum Upload';
    
    $revenueStatus = $booking->revenueSharing ? 'Ada' : 'TIDAK ADA';
    
    printf("%-5s %-15s %-15s %-20s %-15s\n", 
        $booking->id,
        substr($booking->renter->name, 0, 14),
        $booking->status,
        $paymentStatus,
        $revenueStatus
    );
}

echo str_repeat('=', 100) . "\n\n";

echo "Bookings WITHOUT Revenue Sharing:\n";
$bookingsWithoutRS = $bookings->filter(fn($b) => !$b->revenueSharing);
foreach ($bookingsWithoutRS as $booking) {
    echo "- Booking #{$booking->id}: {$booking->renter->name} - Status: {$booking->status}";
    if ($booking->payment) {
        echo " - Payment: " . ($booking->payment->verified_at ? 'Verified' : 'Belum Verified');
    } else {
        echo " - Payment: Belum Upload";
    }
    echo "\n";
}
