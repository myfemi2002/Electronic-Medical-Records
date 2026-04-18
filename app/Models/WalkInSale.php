<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalkInSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_number',
        'customer_name',
        'total_amount',
        'payment_method',
        'sold_by',
        'sold_at',
    ];

    protected $casts = [
        'sold_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(WalkInSaleItem::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'sold_by');
    }
}
