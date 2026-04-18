<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'sku',
        'batch_number',
        'expiry_date',
        'stock_quantity',
        'reorder_level',
        'unit_price',
        'status',
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock_quantity', '<=', 'reorder_level');
    }
}
