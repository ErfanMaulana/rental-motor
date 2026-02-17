<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Test route untuk debug payment filter
Route::get('/test-payment-filter', function (Request $request) {
    $query = \App\Models\Payment::with(['booking', 'booking.renter', 'booking.motor', 'verifiedBy']);

    echo "=== PAYMENT FILTER DEBUG ===\n\n";
    echo "Request Parameters:\n";
    echo "Status: " . ($request->status ?? 'null') . "\n";
    echo "Payment Method: " . ($request->payment_method ?? 'null') . "\n";
    echo "Search: " . ($request->search ?? 'null') . "\n\n";

    // Filter by status
    if ($request->filled('status')) {
        $status = $request->status;
        echo "Applying status filter: $status\n";
        
        if ($status === 'verified') {
            $query->whereNotNull('verified_at');
        } elseif ($status === 'unverified') {
            $query->whereNull('verified_at');
        } elseif (in_array($status, ['pending', 'paid', 'failed'])) {
            $query->where('status', $status);
        }
    }

    // Filter by payment method
    if ($request->filled('payment_method')) {
        $paymentMethod = $request->payment_method;
        echo "Applying payment method filter: $paymentMethod\n";
        $query->where('payment_method', $paymentMethod);
    }

    // Search by penyewa name, email, or booking ID
    if ($request->filled('search')) {
        $search = trim($request->search);
        echo "Applying search: $search\n";
        $query->where(function($q) use ($search) {
            // Search in renter name and email
            $q->whereHas('booking.renter', function($q2) use ($search) {
                $q2->where('name', 'like', "%{$search}%")
                   ->orWhere('email', 'like', "%{$search}%");
            })
            // Search by payment ID
            ->orWhere('id', 'like', "%{$search}%")
            // Search by booking ID
            ->orWhereHas('booking', function($q2) use ($search) {
                $q2->where('id', 'like', "%{$search}%");
            });
        });
    }

    $sql = $query->toSql();
    $bindings = $query->getBindings();
    
    echo "\n=== SQL Query ===\n";
    echo $sql . "\n\n";
    echo "Bindings: " . json_encode($bindings) . "\n\n";

    $payments = $query->orderBy('created_at', 'desc')->get();
    
    echo "=== Results ===\n";
    echo "Total results: " . $payments->count() . "\n\n";
    
    foreach ($payments->take(5) as $payment) {
        echo "ID: {$payment->id} | Status: {$payment->status} | Method: {$payment->payment_method} | ";
        echo "Renter: {$payment->booking->renter->name} | ";
        echo "Verified: " . ($payment->verified_at ? 'Yes' : 'No') . "\n";
    }
    
    return response('Check console output', 200)->header('Content-Type', 'text/plain');
});
