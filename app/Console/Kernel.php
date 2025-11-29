<?php

namespace App\Console;

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
                ->timezone('Africa/Lagos');
        
        // Expire old consultancies at midnight (NEW)
        $schedule->command('consultancies:expire')
                ->dailyAt('00:01')
                ->timezone('Africa/Lagos');
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