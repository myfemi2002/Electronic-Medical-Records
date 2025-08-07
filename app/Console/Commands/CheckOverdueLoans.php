<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Loan;
use App\Models\LoanNotification;
use Carbon\Carbon;

class CheckOverdueLoans extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'loans:check-overdue {--notify : Send notifications to overdue borrowers}';

    /**
     * The console command description.
     */
    protected $description = 'Check for overdue loans and optionally send notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for overdue loans...');

        // Get active loans that are past due date
        $overdueLoans = Loan::whereIn('status', ['disbursed', 'active'])
            ->where('due_date', '<', Carbon::now())
            ->get();

        $updatedCount = 0;
        $notificationCount = 0;

        foreach ($overdueLoans as $loan) {
            $wasOverdue = $loan->status === 'overdue';
            
            // Update loan status to overdue
            $loan->checkOverdue();
            
            if (!$wasOverdue && $loan->status === 'overdue') {
                $updatedCount++;
                $this->line("Loan {$loan->loan_id} marked as overdue");
            }

            // Send notifications if requested
            if ($this->option('notify')) {
                $daysOverdue = abs($loan->days_until_due);
                
                // Send reminder notifications at specific intervals
                if (in_array($daysOverdue, [1, 3, 7, 14, 30])) {
                    $this->sendOverdueNotification($loan, $daysOverdue);
                    $notificationCount++;
                }
            }
        }

        $this->info("Processed {$overdueLoans->count()} overdue loans");
        $this->info("Updated {$updatedCount} loans to overdue status");
        
        if ($this->option('notify')) {
            $this->info("Sent {$notificationCount} overdue notifications");
        }

        return Command::SUCCESS;
    }

    /**
     * Send overdue notification to borrower
     */
    private function sendOverdueNotification(Loan $loan, int $daysOverdue)
    {
        $messages = [
            1 => "Your loan payment is 1 day overdue. Please make a payment as soon as possible.",
            3 => "Your loan payment is 3 days overdue. Immediate payment is required to avoid additional charges.",
            7 => "Your loan payment is 1 week overdue. Please contact us immediately to resolve this matter.",
            14 => "Your loan payment is 2 weeks overdue. This may affect your credit rating.",
            30 => "Your loan payment is 1 month overdue. This loan may be escalated to collections."
        ];

        $title = $daysOverdue === 1 
            ? "Payment Overdue ⚠️" 
            : "Urgent: Payment {$daysOverdue} Days Overdue 🚨";

        LoanNotification::create([
            'user_id' => $loan->user_id,
            'loan_id' => $loan->id,
            'type' => 'loan_reminder',
            'title' => $title,
            'message' => $messages[$daysOverdue] ?? "Your loan payment is {$daysOverdue} days overdue.",
            'is_popup' => true,
            'data' => [
                'loan_id' => $loan->loan_id,
                'outstanding_balance' => $loan->outstanding_balance,
                'days_overdue' => $daysOverdue,
                'reminder_type' => 'overdue_' . $daysOverdue . '_days'
            ]
        ]);

        $this->line("Sent overdue notification to {$loan->user->name} for loan {$loan->loan_id}");
    }
}
