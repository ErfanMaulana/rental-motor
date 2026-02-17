#!/usr/bin/env php
<?php

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\RevenueSharing;

echo "DIAGNOSTIC: Financial Report vs Bookings\n";
echo str_repeat('=', 50) . "\n\n";

// Ambil user penyewa pertama yang punya booking
$renter = User::where('role', 'penyewa')->whereHas('bookings')->first();
if (!$renter) {
    echo "No penyewa found with bookings\n";
    exit;
}

echo "Penyewa: {$renter->name} (ID: {$renter->id})\n\n";

// 1. Total bookings penyewa
$totalBookings = Booking::where('renter_id', $renter->id)->count();
echo "1. Total Bookings: {$totalBookings}\n\n";

// 2. Payments yang sudah diverifikasi
$verifiedPayments = Payment::whereHas('booking', function($q) use ($renter) {
    $q->where('renter_id', $renter->id);
})->whereNotNull('verified_at')->count();
echo "2. Verified Payments: {$verifiedPayments}\n\n";

// 3. Total Revenue Sharing
$totalRevenue = RevenueSharing::whereHas('booking', function($q) use ($renter) {
    $q->where('renter_id', $renter->id);
})->count();
echo "3. Total Revenue Sharing: {$totalRevenue}\n\n";

// 4. Revenue Sharing yang sesuai kriteria financial report
$reportRevenue = RevenueSharing::whereHas('booking', function($q) use ($renter) {
    $q->where('renter_id', $renter->id);
})
->whereHas('booking')
->whereHas('owner')
->whereIn('status', ['pending', 'paid'])
->count();
echo "4. Revenue in Financial Report: {$reportRevenue}\n\n";

// 5. Detail booking tanpa revenue sharing
echo str_repeat('-', 50) . "\n";
echo "BOOKINGS WITHOUT REVENUE SHARING:\n";
echo str_repeat('-', 50) . "\n";
$bookingsWithoutRevenue = Booking::where('renter_id', $renter->id)
    ->whereDoesntHave('revenueSharing')
    ->with(['payment', 'motor'])
    ->get();

foreach ($bookingsWithoutRevenue as $booking) {
    echo "Booking #{$booking->id} - Status: {$booking->status}";
    if ($booking->payment) {
        echo " - Payment: " . ($booking->payment->verified_at ? 'VERIFIED' : 'NOT VERIFIED');
    } else {
        echo " - Payment: NO PAYMENT";
    }
    echo "\n";
}

// 6. Revenue sharing dengan owner null  
echo "\n" . str_repeat('-', 50) . "\n";
echo "REVENUE SHARING WITH MISSING OWNER:\n";
echo str_repeat('-', 50) . "\n";
$revenueWithoutOwner = RevenueSharing::whereHas('booking', function($q) use ($renter) {
    $q->where('renter_id', $renter->id);
})
->whereDoesntHave('owner')
->with('booking')
->get();

echo "Count: {$revenueWithoutOwner->count()}\n";
foreach ($revenueWithoutOwner as $rev) {
    echo "RevenueSharing #{$rev->id} - Owner ID: {$rev->owner_id} - Status: {$rev->status}\n";
}

echo "\n" . str_repeat('=', 50) . "\n";
echo "DIAGNOSIS COMPLETE\n";
