<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicalEncounter extends Model
{
    use HasFactory;

    protected $fillable = [
        'visit_id',
        'patient_id',
        'doctor_id',
        'subjective',
        'objective',
        'assessment',
        'plan',
        'icd10_code',
        'diagnosis',
        'disposition',
        'referral_notes',
        'discharge_notes',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'encounter_id');
    }

    public function serviceOrders()
    {
        return $this->hasMany(ServiceOrder::class, 'encounter_id');
    }
}
