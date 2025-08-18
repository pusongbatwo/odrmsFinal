<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DocumentRequestVerification;
use Carbon\Carbon;

class CleanupExpiredVerifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verifications:cleanup {--days=7 : Number of days to keep expired verifications}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired document request verifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $cutoffDate = Carbon::now()->subDays($days);

        $this->info("Cleaning up verifications older than {$days} days...");

        // Count expired verifications
        $expiredCount = DocumentRequestVerification::where('expires_at', '<', $cutoffDate)->count();

        if ($expiredCount === 0) {
            $this->info('No expired verifications found.');
            return 0;
        }

        // Delete expired verifications
        $deletedCount = DocumentRequestVerification::where('expires_at', '<', $cutoffDate)->delete();

        $this->info("Successfully deleted {$deletedCount} expired verification records.");

        // Also clean up verified records that are older than 30 days
        $verifiedCutoff = Carbon::now()->subDays(30);
        $verifiedCount = DocumentRequestVerification::where('is_verified', true)
            ->where('created_at', '<', $verifiedCutoff)
            ->count();

        if ($verifiedCount > 0) {
            $deletedVerified = DocumentRequestVerification::where('is_verified', true)
                ->where('created_at', '<', $verifiedCutoff)
                ->delete();

            $this->info("Successfully deleted {$deletedVerified} old verified verification records.");
        }

        $this->info('Cleanup completed successfully!');

        return 0;
    }
}
