<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalkInSaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'walk_in_sale_id',
        'inventory_item_id',
        'item_name',
        'quantity',
        'unit_price',
        'line_total',
    ];

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class);
    }
}
