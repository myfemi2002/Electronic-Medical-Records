<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Patient;
use Illuminate\Support\Facades\Log;

class AutoClosePatientFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'patients:auto-close';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically close all open patient files at the end of the day (11:59 PM)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting auto-close process for patient files...');
        
        // Get all files that need to be closed
        $filesToClose = Patient::getFilesToAutoClose();
        
        $closedCount = 0;
        
        foreach ($filesToClose as $patient) {
            $patient->closeFile();
            $closedCount++;
            
            $this->line("Closed file for: {$patient->patient_firstname} {$patient->patient_lastname} (Card: {$patient->card_number})");
        }
        
        // Log the activity
        Log::info("Auto-closed {$closedCount} patient files at " . now()->format('Y-m-d H:i:s'));
        
        $this->info("Successfully closed {$closedCount} patient file(s).");
        
        return Command::SUCCESS;
    }
}