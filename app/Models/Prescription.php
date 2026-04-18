<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'visit_id',
        'patient_id',
        'encounter_id',
        'drug_name',
        'dosage',
        'frequency',
        'duration',
        'instructions',
        'status',
        'prescribed_by',
        'dispensed_by',
        'dispensed_at',
    ];

    protected $casts = [
        'dispensed_at' => 'datetime',
    ];

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }
}
