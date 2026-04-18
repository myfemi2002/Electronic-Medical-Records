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
}
