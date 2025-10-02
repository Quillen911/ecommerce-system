<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'variant_size_id',
        'warehouse_id',
        'inventory_id',
        'type',
        'quantity',
        'reference_type',
        'reference_id',
        'notes',
    ];
    
    protected $casts = [
        'quantity' => 'integer',
        'reference_id' => 'integer',
        'reference_type' => 'string',
        'type' => 'string',
    ];
    
    public function variantSize()
    {
        return $this->belongsTo(VariantSize::class, 'variant_size_id');
    }
    
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
    
    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }
}
