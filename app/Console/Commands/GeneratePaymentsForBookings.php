<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\Payment;

class GeneratePaymentsForBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate payment records untuk booking yang belum punya payment';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mencari booking tanpa payment...');
        
        // Get semua booking yang belum punya payment
        $bookings = Booking::doesntHave('payment')->get();
        
        if ($bookings->count() === 0) {
            $this->info('✅ Semua booking sudah memiliki payment record!');
            return Command::SUCCESS;
        }
        
        $this->info("Ditemukan {$bookings->count()} booking tanpa payment");
        
        $paymentMethodMap = [
            'dana' => 'e_wallet',
            'gopay' => 'e_wallet',
            'shopeepay' => 'e_wallet',
            'bank' => 'bank_transfer'
        ];
        
        $created = 0;
        foreach ($bookings as $booking) {
            try {
                Payment::create([
                    'booking_id' => $booking->id,
                    'amount' => $booking->price,
                    'method' => $paymentMethodMap[$booking->payment_method] ?? 'e_wallet',
                    'payment_method' => $booking->payment_method,
                    'status' => 'paid',
                    'paid_at' => $booking->created_at,
                    'notes' => 'Pembayaran via ' . strtoupper($booking->payment_method ?? 'e_wallet')
                ]);
                
                $created++;
                $this->info("✓ Payment created untuk Booking #{$booking->id}");
            } catch (\Exception $e) {
                $this->error("✗ Failed untuk Booking #{$booking->id}: " . $e->getMessage());
            }
        }
        
        $this->info("\n✅ Selesai! {$created} payment records dibuat.");
        
        return Command::SUCCESS;
    }
}
