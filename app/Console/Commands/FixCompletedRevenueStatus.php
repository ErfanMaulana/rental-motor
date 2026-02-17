<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\RevenueSharing;
use Illuminate\Support\Facades\DB;

class FixCompletedRevenueStatus extends Command
{
    protected $signature = 'fix:completed-revenue-status {--dry-run : Run without making changes}';
    protected $description = 'Update revenue sharing status to paid for completed bookings';

    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->info('🔍 DRY RUN MODE - No changes will be made');
            $this->newLine();
        }

        $this->info('Searching for completed bookings with pending revenue sharing...');
        $this->newLine();

        // Find all completed bookings with pending revenue sharing
        $bookingsToFix = Booking::where('status', 'completed')
            ->whereHas('revenueSharing', function($query) {
                $query->where('status', 'pending');
            })
            ->with(['revenueSharing', 'renter', 'motor'])
            ->get();

        if ($bookingsToFix->isEmpty()) {
            $this->info('✅ No issues found! All completed bookings have paid revenue status.');
            return 0;
        }

        $this->warn("Found {$bookingsToFix->count()} booking(s) with completed status but pending revenue:");
        $this->newLine();

        $updatedCount = 0;

        foreach ($bookingsToFix as $booking) {
            $revenue = $booking->revenueSharing;
            
            $this->line("Booking #{$booking->id}:");
            $this->line("  - Penyewa: {$booking->renter->name}");
            $this->line("  - Motor: {$booking->motor->brand} {$booking->motor->model}");
            $this->line("  - End Date: {$booking->end_date}");
            $this->line("  - Revenue Amount: Rp " . number_format($revenue->total_amount, 0, ',', '.'));
            
            if (!$dryRun) {
                // Update revenue sharing status to paid
                $revenue->update([
                    'status' => 'paid',
                    'settled_at' => $booking->updated_at // Use booking's last update time
                ]);
                
                $this->info("  ✅ Revenue status updated to 'paid'");
                $updatedCount++;
            } else {
                $this->comment("  ⏭️  Would update to 'paid' (dry-run mode)");
            }
            
            $this->newLine();
        }

        $this->newLine();
        
        if ($dryRun) {
            $this->info("DRY RUN COMPLETE!");
            $this->comment("Would update: {$bookingsToFix->count()} revenue sharing records");
            $this->comment("Run without --dry-run to apply changes");
        } else {
            $this->info("COMPLETE!");
            $this->info("✅ Updated: {$updatedCount} revenue sharing records");
        }

        return 0;
    }
}
