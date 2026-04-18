<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'visit_id',
        'patient_id',
        'receipt_number',
        'payment_method',
        'amount',
        'status',
        'reference',
        'received_by',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function refunds()
    {
        return $this->hasMany(PaymentRefund::class);
    }
}
