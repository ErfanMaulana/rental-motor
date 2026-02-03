<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class UpdateBookingStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Otomatis update status booking berdasarkan tanggal real-time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai update status booking...');
        
        $today = Carbon::today();
        $now = Carbon::now();
        
        // 1. Auto-activate bookings yang tanggal mulainya sudah tiba
        $confirmedBookings = Booking::where('status', 'confirmed')
            ->whereDate('start_date', '<=', $today)
            ->get();
            
        foreach ($confirmedBookings as $booking) {
            $booking->status = 'active';
            $booking->save();
            
            $this->info("✓ Booking #{$booking->id} diaktifkan (mulai: {$booking->start_date->format('d/m/Y')})");
            Log::info("Auto-activated booking #{$booking->id}");
        }
        
        // 2. Auto-complete bookings yang tanggal selesainya sudah lewat
        $activeBookings = Booking::whereIn('status', ['active', 'ongoing'])
            ->whereDate('end_date', '<', $today)
            ->get();
            
        foreach ($activeBookings as $booking) {
            $booking->status = 'completed';
            $booking->save();
            
            $this->info("✓ Booking #{$booking->id} diselesaikan (selesai: {$booking->end_date->format('d/m/Y')})");
            Log::info("Auto-completed booking #{$booking->id}");
        }
        
        // 3. Update motor status ke 'available' jika booking sudah selesai/dibatalkan
        $completedBookings = Booking::whereIn('status', ['completed', 'cancelled'])
            ->with('motor')
            ->get();
            
        foreach ($completedBookings as $booking) {
            if ($booking->motor && $booking->motor->status !== 'available') {
                // Cek apakah ada booking lain yang aktif untuk motor ini
                $hasActiveBooking = Booking::where('motor_id', $booking->motor_id)
                    ->whereIn('status', ['confirmed', 'active', 'ongoing'])
                    ->where('id', '!=', $booking->id)
                    ->exists();
                    
                if (!$hasActiveBooking) {
                    $booking->motor->status = 'available';
                    $booking->motor->save();
                    $this->info("✓ Motor {$booking->motor->plate_number} tersedia kembali");
                }
            }
        }
        
        $this->info("\n✅ Update status booking selesai!");
        $this->info("   - Diaktifkan: {$confirmedBookings->count()} booking");
        $this->info("   - Diselesaikan: {$activeBookings->count()} booking");
        
        return Command::SUCCESS;
    }
}
