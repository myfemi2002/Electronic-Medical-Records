<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ConsultancyPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'receipt_number',
        'payment_method',
        'amount_paid',
        'payment_date',
        'consultancy_start_date',
        'consultancy_expiry_date',
        'status',
        'verification_note',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'consultancy_start_date' => 'datetime',
        'consultancy_expiry_date' => 'datetime',
        'verified_at' => 'datetime',
        'amount_paid' => 'decimal:2',
    ];

    /**
     * Relationship: Patient
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Relationship: User who verified the payment
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Check if this consultancy is still active
     */
    public function isActive()
    {
        return $this->status === 'active' && $this->consultancy_expiry_date->isFuture();
    }

    /**
     * Check if this consultancy has expired
     */
    public function isExpired()
    {
        return $this->consultancy_expiry_date->isPast();
    }

    /**
     * Get days remaining
     */
    public function daysRemaining()
    {
        if ($this->isExpired()) {
            return 0;
        }
        return now()->diffInDays($this->consultancy_expiry_date, false);
    }

    /**
     * Expire this consultancy
     */
    public function expire()
    {
        $this->update(['status' => 'expired']);
    }

    /**
     * Get all expired consultancies that need to be marked expired
     */
    public static function expireOldConsultancies()
    {
        return static::where('status', 'active')
            ->where('consultancy_expiry_date', '<', now())
            ->update(['status' => 'expired']);
    }

    /**
     * Get active consultancy for a patient
     */
    public static function getActiveForPatient($patientId)
    {
        return static::where('patient_id', $patientId)
            ->where('status', 'active')
            ->where('consultancy_expiry_date', '>=', now())
            ->latest('consultancy_start_date')
            ->first();
    }

    /**
     * Get all consultancy history for a patient
     */
    public static function getHistoryForPatient($patientId)
    {
        return static::where('patient_id', $patientId)
            ->latest('payment_date')
            ->get();
    }

    /**
     * Get payment statistics
     */
    public static function getStats()
    {
        return [
            'total_payments' => static::count(),
            'active_consultancies' => static::where('status', 'active')
                ->where('consultancy_expiry_date', '>=', now())
                ->count(),
            'expired_consultancies' => static::where('status', 'expired')->count(),
            'total_revenue' => static::sum('amount_paid'),
            'today_payments' => static::whereDate('payment_date', today())->count(),
            'this_month_payments' => static::whereMonth('payment_date', now()->month)->count(),
        ];
    }
}