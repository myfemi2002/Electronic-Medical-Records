<?php

namespace App\Console\Commands;

use App\Models\MotherWallet;
use App\Models\User;
use App\Models\UserWallet;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AssignWalletsToUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wallets:assign {--force : Force reassign wallets to all users}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign mother wallets to users who do not have a wallet assigned';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $force = $this->option('force');
        
        if ($force) {
            $users = User::all();
            $this->info('Reassigning wallets to all users...');
        } else {
            // Get users who don't have a wallet yet
            $users = User::whereDoesntHave('wallet')->get();
            $this->info('Assigning wallets to users without wallets...');
        }
        
        $count = 0;
        
        foreach ($users as $user) {
            // Get a random active mother wallet
            $motherWallet = MotherWallet::getRandomActiveWallet();
            
            if (!$motherWallet) {
                $this->error('No active mother wallets found. Please add some first.');
                Log::error('Failed to assign wallets: No active mother wallets available');
                return 1;
            }
            
            // Check if user already has a wallet
            $existingWallet = UserWallet::where('user_id', $user->id)->first();
            
            if ($existingWallet) {
                if ($force) {
                    // Update existing wallet with new mother wallet
                    $existingWallet->mother_wallet_id = $motherWallet->id;
                    $existingWallet->save();
                    $count++;
                } else {
                    // Skip this user
                    continue;
                }
            } else {
                // Create new wallet for user
                UserWallet::create([
                    'user_id' => $user->id,
                    'mother_wallet_id' => $motherWallet->id,
                    'balance' => 0,
                ]);
                $count++;
            }
        }
        
        $this->info("Successfully assigned wallets to {$count} users.");
        return 0;
    }
}