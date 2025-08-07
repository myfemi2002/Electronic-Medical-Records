<?php

namespace App\Console\Commands;

use App\Models\MotherWallet;
use App\Models\User;
use App\Models\UserWallet;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FixWalletIssues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wallets:fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix various wallet-related issues in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting wallet issue fixes...');
        
        // Step 1: Find and fix users without wallets
        $usersWithoutWallets = User::whereDoesntHave('wallet')->get();
        $this->info("Found {$usersWithoutWallets->count()} users without wallets.");
        
        $walletAssignCount = 0;
        foreach ($usersWithoutWallets as $user) {
            $motherWallet = MotherWallet::getRandomActiveWallet();
            
            if (!$motherWallet) {
                $this->error('No active mother wallets found. Please add at least one mother wallet first.');
                return 1;
            }
            
            UserWallet::create([
                'user_id' => $user->id,
                'mother_wallet_id' => $motherWallet->id,
                'balance' => 0
            ]);
            
            $walletAssignCount++;
        }
        
        $this->info("Created wallets for {$walletAssignCount} users.");
        
        // Step 2: Find wallets with invalid mother_wallet_id references
        $invalidWallets = UserWallet::whereDoesntHave('motherWallet')->get();
        $this->info("Found {$invalidWallets->count()} wallets with invalid mother wallet references.");
        
        $walletFixCount = 0;
        foreach ($invalidWallets as $wallet) {
            $motherWallet = MotherWallet::getRandomActiveWallet();
            
            if (!$motherWallet) {
                $this->error('No active mother wallets found. Please add at least one mother wallet first.');
                return 1;
            }
            
            $wallet->mother_wallet_id = $motherWallet->id;
            $wallet->save();
            
            $walletFixCount++;
        }
        
        $this->info("Fixed {$walletFixCount} wallets with invalid mother wallet references.");
        
        // Step 3: Verify all mother wallets are valid
        $motherWalletCount = MotherWallet::where('is_active', true)->count();
        
        if ($motherWalletCount === 0) {
            $this->warn("WARNING: No active mother wallets found in the system.");
            
            if ($this->confirm('Would you like to make all mother wallets active?')) {
                $updated = MotherWallet::where('is_active', false)->update(['is_active' => true]);
                $this->info("Made {$updated} mother wallets active.");
            }
        } else {
            $this->info("Found {$motherWalletCount} active mother wallets in the system.");
        }
        
        $this->info('Wallet issue fixes completed successfully!');
        return 0;
    }
}