<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Wallet;

class FixWalletContributions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wallet:fix-contributions {--dry-run : Show what would be changed without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix wallet total_contributions to reflect actual contributions only';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        
        $this->info('Starting wallet contributions fix...');
        
        if ($isDryRun) {
            $this->warn('DRY RUN MODE - No changes will be made');
        }

        $wallets = Wallet::with(['user', 'contributions', 'adjustments'])->get();
        
        $this->info("Found {$wallets->count()} wallets to process");
        
        $fixed = 0;
        $errors = 0;
        
        foreach ($wallets as $wallet) {
            try {
                $oldTotal = $wallet->total_contributions;
                $newTotal = $wallet->getActualTotalContributions();
                
                if ($oldTotal != $newTotal) {
                    $this->line("User: {$wallet->user->name} ({$wallet->user->email})");
                    $this->line("  Current Total: ₦" . number_format($oldTotal, 2));
                    $this->line("  Correct Total: ₦" . number_format($newTotal, 2));
                    $this->line("  Difference: ₦" . number_format($newTotal - $oldTotal, 2));
                    
                    if (!$isDryRun) {
                        $wallet->recalculateTotalContributions();
                        $this->info("  ✓ Fixed");
                    } else {
                        $this->comment("  → Would be fixed");
                    }
                    
                    $fixed++;
                } else {
                    $this->comment("User {$wallet->user->name}: Already correct (₦" . number_format($oldTotal, 2) . ")");
                }
                
            } catch (\Exception $e) {
                $this->error("Error processing wallet for user {$wallet->user->name}: " . $e->getMessage());
                $errors++;
            }
        }
        
        $this->newLine();
        
        if ($isDryRun) {
            $this->info("DRY RUN COMPLETE:");
            $this->info("  Wallets that would be fixed: {$fixed}");
            $this->info("  Wallets with errors: {$errors}");
            $this->info("  Run without --dry-run to apply changes");
        } else {
            $this->info("WALLET FIX COMPLETE:");
            $this->info("  Wallets fixed: {$fixed}");
            $this->info("  Errors: {$errors}");
        }
        
        return Command::SUCCESS;
    }
}