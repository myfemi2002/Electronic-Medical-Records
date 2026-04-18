<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'visit_number',
        'visit_type',
        'current_stage',
        'status',
        'is_emergency',
        'department_id',
        'created_by',
        'cashier_cleared_by',
        'triaged_by',
        'doctor_id',
        'discharged_by',
        'queued_for_cashier_at',
        'cashier_cleared_at',
        'triaged_at',
        'doctor_seen_at',
        'discharged_at',
        'chief_complaint',
        'notes',
    ];

    protected $casts = [
        'is_emergency' => 'boolean',
        'queued_for_cashier_at' => 'datetime',
        'cashier_cleared_at' => 'datetime',
        'triaged_at' => 'datetime',
        'doctor_seen_at' => 'datetime',
        'discharged_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function stageLogs()
    {
        return $this->hasMany(VisitStageLog::class);
    }

    public function triageQueue()
    {
        return $this->hasOne(TriageQueue::class);
    }

    public function encounter()
    {
        return $this->hasOne(ClinicalEncounter::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function serviceOrders()
    {
        return $this->hasMany(ServiceOrder::class);
    }

    public function nursingNotes()
    {
        return $this->hasMany(NursingNote::class);
    }

    public static function generateVisitNumber(bool $emergency = false): string
    {
        $prefix = $emergency ? 'EMG' : 'VIS';
        $date = now()->format('Ymd');
        $count = static::whereDate('created_at', today())->count() + 1;

        return sprintf('%s-%s-%04d', $prefix, $date, $count);
    }
}
