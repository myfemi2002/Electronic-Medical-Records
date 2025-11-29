<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_number',
        'patient_lastname',
        'patient_firstname',
        'patient_phone',
        'patient_occupation',
        'patient_religion',
        'patient_gender',
        'patient_status',
        'patient_dob',
        'patient_type',
        'patient_hmo',
        'patient_nationality',
        'patient_address',
        'image',
        'patient_kin_name',
        'kin_relationship',
        'patient_kin_phone',
        'patient_kin_address',
        'file_opened_at',
        'file_status',
        'opened_by',
        'file_closed_at',
        'current_consultancy_expires_at',
        'has_active_consultancy',
    ];

    protected $casts = [
        'patient_dob' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'file_opened_at' => 'datetime',
        'file_closed_at' => 'datetime',
        'current_consultancy_expires_at' => 'datetime',
        'has_active_consultancy' => 'boolean',
    ];

    /**
     * Get patient's full name
     */
    public function getFullNameAttribute()
    {
        return $this->patient_lastname . ' ' . $this->patient_firstname;
    }

    /**
     * Get patient's age from date of birth
     */
    public function getAgeAttribute()
    {
        return $this->patient_dob ? $this->patient_dob->age : 'N/A';
    }

    // ========================================
    // FILE MANAGEMENT METHODS
    // ========================================

    /**
     * Open patient file
     */
    public function openFile($userId = null)
    {
        $this->update([
            'file_status' => 'open',
            'file_opened_at' => now(),
            'opened_by' => $userId ?? auth()->id(),
            'file_closed_at' => null,
        ]);
    }

    /**
     * Close patient file
     */
    public function closeFile()
    {
        $this->update([
            'file_status' => 'closed',
            'file_closed_at' => now(),
        ]);
    }

    /**
     * Check if file is open
     */
    public function isFileOpen()
    {
        return $this->file_status === 'open';
    }

    /**
     * Check if file needs to be auto-closed (opened before today)
     */
    public function shouldAutoClose()
    {
        if ($this->file_status === 'closed') {
            return false;
        }

        return $this->file_opened_at && 
               ($this->file_opened_at->isYesterday() || 
                $this->file_opened_at->lt(now()->startOfDay()));
    }

    /**
     * Get all open files (opened today)
     */
    public static function getOpenFiles()
    {
        return static::where('file_status', 'open')
            ->whereDate('file_opened_at', today())
            ->latest('file_opened_at')
            ->get();
    }

    /**
     * Get files that need to be auto-closed
     */
    public static function getFilesToAutoClose()
    {
        return static::where('file_status', 'open')
            ->where(function($query) {
                $query->whereDate('file_opened_at', '<', today())
                      ->orWhereNull('file_opened_at');
            })
            ->get();
    }

    // ========================================
    // CONSULTANCY MANAGEMENT METHODS
    // ========================================

    /**
     * Get active consultancy payment record
     */
    public function getActiveConsultancy()
    {
        return ConsultancyPayment::getActiveForPatient($this->id);
    }

    /**
     * Check if patient has active consultancy
     */
    public function hasActiveConsultancy()
    {
        $activeConsultancy = $this->getActiveConsultancy();
        return $activeConsultancy !== null;
    }

    /**
     * Check if patient needs to pay consultancy fee
     */
    public function needsConsultancyPayment()
    {
        return !$this->hasActiveConsultancy();
    }

    /**
     * Record consultancy payment (from receipt verification)
     */
    public function recordConsultancyPayment($data)
    {
        $paymentDate = Carbon::parse($data['payment_date']);
        $startDate = $paymentDate->startOfDay();
        $expiryDate = $paymentDate->copy()->addDays(7)->endOfDay();
        
        // Create payment record
        $payment = ConsultancyPayment::create([
            'patient_id' => $this->id,
            'receipt_number' => $data['receipt_number'],
            'payment_method' => $data['payment_method'],
            'amount_paid' => $data['amount_paid'],
            'payment_date' => $paymentDate,
            'consultancy_start_date' => $startDate,
            'consultancy_expiry_date' => $expiryDate,
            'status' => 'active',
            'verification_note' => $data['verification_note'] ?? null,
            'verified_by' => $data['verified_by'] ?? auth()->id(),
            'verified_at' => now(),
        ]);

        // Update patient record
        $this->update([
            'current_consultancy_expires_at' => $expiryDate,
            'has_active_consultancy' => true,
        ]);

        return $payment;
    }

    /**
     * Update consultancy status (called by cron job)
     */
    public function updateConsultancyStatus()
    {
        $activeConsultancy = $this->getActiveConsultancy();
        
        if ($activeConsultancy) {
            $this->update([
                'current_consultancy_expires_at' => $activeConsultancy->consultancy_expiry_date,
                'has_active_consultancy' => true,
            ]);
        } else {
            $this->update([
                'current_consultancy_expires_at' => null,
                'has_active_consultancy' => false,
            ]);
        }
    }

    /**
     * Get consultancy days remaining
     */
    public function consultancyDaysRemaining()
    {
        $activeConsultancy = $this->getActiveConsultancy();
        return $activeConsultancy ? $activeConsultancy->daysRemaining() : 0;
    }

    /**
     * Get consultancy status with details
     */
    public function getConsultancyStatus()
    {
        $activeConsultancy = $this->getActiveConsultancy();
        
        if (!$activeConsultancy) {
            // Check if ever paid
            $lastPayment = $this->consultancyPayments()->latest('payment_date')->first();
            
            if (!$lastPayment) {
                return [
                    'status' => 'never_paid',
                    'message' => 'No consultancy on record. Patient must go to Accountant to pay.',
                    'needs_payment' => true,
                    'days_remaining' => 0,
                ];
            } else {
                return [
                    'status' => 'expired',
                    'message' => "Last consultancy expired on " . $lastPayment->consultancy_expiry_date->format('M d, Y') . ". Patient must pay at Accountant.",
                    'needs_payment' => true,
                    'days_remaining' => 0,
                    'expired_on' => $lastPayment->consultancy_expiry_date->format('M d, Y'),
                    'last_receipt' => $lastPayment->receipt_number,
                ];
            }
        }

        $daysRemaining = $activeConsultancy->daysRemaining();
        return [
            'status' => 'active',
            'message' => "Valid for {$daysRemaining} more day(s)",
            'needs_payment' => false,
            'days_remaining' => $daysRemaining,
            'expires_at' => $activeConsultancy->consultancy_expiry_date->format('M d, Y'),
            'receipt_number' => $activeConsultancy->receipt_number,
            'payment_date' => $activeConsultancy->payment_date->format('M d, Y'),
            'amount_paid' => $activeConsultancy->amount_paid,
        ];
    }

    /**
     * Get total consultancy payments made
     */
    public function getTotalConsultancyPaidAttribute()
    {
        return $this->consultancyPayments()->count();
    }

    /**
     * Get total amount paid for consultancy
     */
    public function getTotalConsultancyAmountAttribute()
    {
        return $this->consultancyPayments()->sum('amount_paid');
    }

    // ========================================
    // RELATIONSHIPS
    // ========================================

    /**
     * Relationship: User who opened the file
     */
    public function openedByUser()
    {
        return $this->belongsTo(User::class, 'opened_by');
    }

    /**
     * Relationship: All consultancy payments
     */
    public function consultancyPayments()
    {
        return $this->hasMany(ConsultancyPayment::class);
    }

    /**
     * Relationship: Latest consultancy payment
     */
    public function latestConsultancyPayment()
    {
        return $this->hasOne(ConsultancyPayment::class)->latestOfMany('payment_date');
    }

     /**
     * Verify consultancy payment from receipt (Records Department)
     */
    public function verifyConsultancyPayment($data)
    {
        $paymentDate = Carbon::parse($data['payment_date']);
        
        $this->update([
            'last_consultancy_date' => $paymentDate,
            'consultancy_expires_at' => $paymentDate->copy()->addDays(7),
            'consultancy_active' => true,
            'total_consultancy_paid' => $this->total_consultancy_paid + 1,
            'receipt_number' => $data['receipt_number'],
            'payment_method' => $data['payment_method'],
            'amount_paid' => $data['amount_paid'],
            'payment_date' => $paymentDate,
            'verification_note' => $data['verification_note'] ?? null,
            'verified_by' => $data['verified_by'] ?? auth()->id(),
            'verified_at' => now(),
        ]);
    }

    /**
     * Expire all consultancies that are past 7 days
     */
    public static function expireOldConsultancies()
    {
        return static::where('consultancy_active', true)
            ->where('consultancy_expires_at', '<', now())
            ->update(['consultancy_active' => false]);
    }



    /**
     * Relationship: User who verified the payment
     */
    public function verifiedByUser()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

}