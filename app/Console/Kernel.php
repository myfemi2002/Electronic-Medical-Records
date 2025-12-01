<?php

namespace App\Console;

use Illuminate\Support\Facades\Log;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Auto-close files at 11:59 PM
        $schedule->command('patients:auto-close')
                ->dailyAt('23:59')
                ->timezone('Africa/Lagos')
                ->onSuccess(function () {
                    Log::info('Patient files auto-closed successfully at ' . now());
                })
                ->onFailure(function () {
                    Log::error('Failed to auto-close patient files at ' . now());
                });
        
        // Expire old consultancies at midnight
        $schedule->command('consultancies:expire')
                ->dailyAt('00:01')
                ->timezone('Africa/Lagos')
                ->onSuccess(function () {
                    Log::info('Consultancies expired successfully at ' . now());
                })
                ->onFailure(function () {
                    Log::error('Failed to expire consultancies at ' . now());
                });
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}