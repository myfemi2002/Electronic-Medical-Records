<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NursingNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'visit_id',
        'patient_id',
        'nurse_id',
        'note',
        'medication_administration',
        'procedures',
        'bed_allocation',
        'fluid_balance',
        'status',
    ];
}
