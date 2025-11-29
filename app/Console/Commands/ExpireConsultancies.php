<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ConsultancyPayment;
use App\Models\Patient;

class ExpireConsultancies extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'consultancies:expire';

    /**
     * The console command description.
     */
    protected $description = 'Expire consultancies that have passed their 7-day validity period';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting consultancy expiry check...');
        
        // Expire old consultancy payment records
        $expiredCount = ConsultancyPayment::expireOldConsultancies();
        $this->info("Expired {$expiredCount} consultancy payment record(s)");
        
        // Update patient records
        $patients = Patient::where('has_active_consultancy', true)->get();
        $updatedCount = 0;
        
        foreach ($patients as $patient) {
            $patient->updateConsultancyStatus();
            $updatedCount++;
        }
        
        $this->info("Updated {$updatedCount} patient record(s)");
        $this->info('Consultancy expiry check completed successfully.');
        
        return 0;
    }
}