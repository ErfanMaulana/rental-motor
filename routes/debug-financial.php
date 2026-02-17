<?php

use Illuminate\Support\Facades\Route;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\RevenueSharing;

Route::get('/debug-financial', function() {
    // 1. Cek semua booking dari penyewa tertentu (ambil dari session atau ganti ID)
    $renterId = auth()->id() ?? 1; // Ganti dengan ID penyewa yang login
    
    $allBookings = Booking::where('renter_id', $renterId)
        ->with(['payment', 'revenueSharing', 'motor.owner'])
        ->get();
    
    echo "<h2>Debug Financial Report Discrepancy</h2>";
    echo "<h3>Total Bookings: " . $allBookings->count() . "</h3>";
    
    echo "<table border='1' cellpadding='10'>";
    echo "<tr>
        <th>Booking ID</th>
        <th>Motor</th>
        <th>Status</th>
        <th>Payment ID</th>
        <th>Payment Verified</th>
        <th>Revenue Sharing</th>
        <th>Owner Valid</th>
        <th>Muncul di Report?</th>
    </tr>";
    
    foreach ($allBookings as $booking) {
        $payment = $booking->payment;
        $revenue = $booking->revenueSharing;
        $hasOwner = $booking->motor && $booking->motor->owner_id && $booking->motor->owner;
        
        $inReport = false;
        if ($revenue) {
            $inReport = in_array($revenue->status, ['pending', 'paid']) 
                       && $booking->exists 
                       && $hasOwner;
        }
        
        echo "<tr>";
        echo "<td>#{$booking->id}</td>";
        echo "<td>" . ($booking->motor ? $booking->motor->brand . ' ' . $booking->motor->model : 'N/A') . "</td>";
        echo "<td>{$booking->status}</td>";
        echo "<td>" . ($payment ? "#{$payment->id}" : "No Payment") . "</td>";
        echo "<td>" . ($payment && $payment->verified_at ? "✅ Yes" : "❌ No") . "</td>";
        echo "<td>" . ($revenue ? "✅ Yes (#{$revenue->id})" : "❌ No") . "</td>";
        echo "<td>" . ($hasOwner ? "✅ Yes" : "❌ No") . "</td>";
        echo "<td style='background:" . ($inReport ? '#d4edda' : '#f8d7da') . "'>" . ($inReport ? "✅ YES" : "❌ NO") . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
    echo "<hr>";
    echo "<h3>Revenue Sharing Summary</h3>";
    $allRevenue = RevenueSharing::with(['booking', 'owner'])->get();
    echo "<p>Total Revenue Sharing Records: " . $allRevenue->count() . "</p>";
    echo "<p>With Valid Booking: " . $allRevenue->filter(fn($r) => $r->booking)->count() . "</p>";
    echo "<p>With Valid Owner: " . $allRevenue->filter(fn($r) => $r->owner)->count() . "</p>";
    echo "<p>Shown in Report: " . $allRevenue->filter(function($r) {
        return $r->booking && $r->owner && in_array($r->status, ['pending', 'paid']);
    })->count() . "</p>";
    
    echo "<hr>";
    echo "<h3>Payments dengan Revenue Sharing yang Missing</h3>";
    $paymentsWithoutRevenue = Payment::whereNotNull('verified_at')
        ->where('status', 'paid')
        ->whereDoesntHave('booking.revenueSharing')
        ->with('booking')
        ->get();
    
    echo "<p>Found: " . $paymentsWithoutRevenue->count() . " verified payments without revenue sharing</p>";
    
    if ($paymentsWithoutRevenue->count() > 0) {
        echo "<ul>";
        foreach ($paymentsWithoutRevenue as $payment) {
            echo "<li>Payment #{$payment->id} - Booking #{$payment->booking_id} - Verified at {$payment->verified_at}</li>";
        }
        echo "</ul>";
    }
});
