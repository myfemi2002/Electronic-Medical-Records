<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TriageVital extends Model
{
    use HasFactory;

    protected $fillable = [
        'triage_queue_id',
        'patient_id',
        'recorded_by',
        'blood_pressure',
        'temperature',
        'pulse_rate',
        'respiratory_rate',
        'oxygen_saturation',
        'weight',
        'height',
        'bmi',
        'bp_interpretation',
        'temp_interpretation',
        'pulse_interpretation',
        'rr_interpretation',
        'spo2_interpretation',
        'bmi_interpretation',
        'overall_priority',
        'clinical_alerts',
        'notes',
    ];

    protected $casts = [
        'clinical_alerts' => 'array',
        'temperature' => 'decimal:2',
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
        'bmi' => 'decimal:2',
    ];

    // Relationships
    public function triageQueue()
    {
        return $this->belongsTo(TriageQueue::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // Calculate BMI
    public function calculateBMI()
    {
        if ($this->weight && $this->height) {
            $heightInMeters = $this->height / 100;
            $this->bmi = round($this->weight / ($heightInMeters * $heightInMeters), 2);
            return $this->bmi;
        }
        return null;
    }

    // Get BP systolic and diastolic
    public function getBPValues()
    {
        if (!$this->blood_pressure) {
            return null;
        }

        $parts = explode('/', $this->blood_pressure);
        
        return [
            'systolic' => (int) ($parts[0] ?? 0),
            'diastolic' => (int) ($parts[1] ?? 0),
        ];
    }

    // Attributes
    public function getPriorityBadgeAttribute()
    {
        $badges = [
            'critical' => '<span class="badge bg-danger"><i class="bi bi-exclamation-triangle me-1"></i>Critical</span>',
            'moderate' => '<span class="badge bg-warning text-dark"><i class="bi bi-exclamation-circle me-1"></i>Moderate</span>',
            'mild' => '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Mild</span>',
        ];

        return $badges[$this->overall_priority] ?? '<span class="badge bg-secondary">Pending</span>';
    }
}