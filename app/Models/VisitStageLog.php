<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitStageLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'visit_id',
        'from_stage',
        'to_stage',
        'status',
        'changed_by',
        'note',
    ];

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }
}
