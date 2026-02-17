<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\RevenueSharing;
use Illuminate\Support\Facades\Log;

class FixMissingRevenueSharing extends Command
{
    protected $signature = 'fix:missing-revenue-sharing {--dry-run : Show what would be done without actually doing it}';
    protected $description = 'Create missing revenue sharing records for verified payments';

    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        
        $this->info('Searching for verified payments without revenue sharing...');
        $this->newLine();
        
        // Find all verified payments that don't have revenue sharing
        $bookingsWithoutRevenue = Booking::whereHas('payment', function($q) {
            $q->whereNotNull('verified_at')
              ->where('status', 'paid');
        })
        ->whereDoesntHave('revenueSharing')
        ->with(['payment', 'motor.owner'])
        ->get();
        
        if ($bookingsWithoutRevenue->isEmpty()) {
            $this->info('✅ No missing revenue sharing records found!');
            return 0;
        }
        
        $this->warn("Found {$bookingsWithoutRevenue->count()} booking(s) without revenue sharing:");
        $this->newLine();
        
        $created = 0;
        $skipped = 0;
        
        foreach ($bookingsWithoutRevenue as $booking) {
            $this->line("Booking #{$booking->id}:");
            $this->line("  - Status: {$booking->status}");
            $this->line("  - Price: Rp " . number_format($booking->price, 0, ',', '.'));
            
            if (!$booking->motor) {
                $this->error("  ❌ SKIP: Motor not found");
                $skipped++;
                $this->newLine();
                continue;
            }
            
            if (!$booking->motor->owner_id) {
                $this->error("  ❌ SKIP: Motor has no owner");
                $skipped++;
                $this->newLine();
                continue;
            }
            
            $totalAmount = $booking->price;
            $ownerAmount = $totalAmount * 0.7; // 70% untuk pemilik
            $adminCommission = $totalAmount * 0.3; // 30% untuk admin
            
            $this->line("  - Owner: {$booking->motor->owner->name}");
            $this->line("  - Total: Rp " . number_format($totalAmount, 0, ',', '.'));
            $this->line("  - Owner Share (70%): Rp " . number_format($ownerAmount, 0, ',', '.'));
            $this->line("  - Admin Commission (30%): Rp " . number_format($adminCommission, 0, ',', '.'));
            
            if ($isDryRun) {
                $this->comment("  ℹ️  DRY RUN: Would create revenue sharing");
            } else {
                // Determine status based on booking status
                $status = in_array($booking->status, ['completed']) ? 'paid' : 'pending';
                $settledAt = $status === 'paid' ? now() : null;
                
                RevenueSharing::create([
                    'booking_id' => $booking->id,
                    'owner_id' => $booking->motor->owner_id,
                    'total_amount' => $totalAmount,
                    'owner_amount' => $ownerAmount,
                    'admin_commission' => $adminCommission,
                    'owner_percentage' => 70.00,
                    'admin_percentage' => 30.00,
                    'status' => $status,
                    'settled_at' => $settledAt
                ]);
                
                Log::info('Missing revenue sharing created by fix command', [
                    'booking_id' => $booking->id,
                    'total_amount' => $totalAmount,
                    'status' => $status
                ]);
                
                $this->info("  ✅ Revenue sharing created (status: {$status})");
                $created++;
            }
            
            $this->newLine();
        }
        
        $this->newLine();
        if ($isDryRun) {
            $this->warn("DRY RUN COMPLETE!");
            $this->info("Would create: {$created} revenue sharing records");
            $this->info("Would skip: {$skipped} bookings");
            $this->newLine();
            $this->comment("Run without --dry-run to actually create the records");
        } else {
            $this->info("COMPLETE!");
            $this->info("✅ Created: {$created} revenue sharing records");
            if ($skipped > 0) {
                $this->warn("⚠️  Skipped: {$skipped} bookings (missing motor/owner)");
            }
        }
        
        return 0;
    }
}
