<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'visit_id',
        'patient_id',
        'encounter_id',
        'service_type',
        'request_name',
        'instructions',
        'status',
        'sample_barcode',
        'result_text',
        'report_text',
        'image_path',
        'template_name',
        'comparison_notes',
        'requested_by',
        'processed_by',
        'approved_by',
        'processed_at',
        'approved_at',
    ];

    protected $casts = [
        'processed_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function encounter()
    {
        return $this->belongsTo(ClinicalEncounter::class, 'encounter_id');
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getResultSummaryAttribute(): string
    {
        return $this->service_type === 'laboratory'
            ? ($this->result_text ?: 'Result pending')
            : ($this->report_text ?: 'Report pending');
    }
}
