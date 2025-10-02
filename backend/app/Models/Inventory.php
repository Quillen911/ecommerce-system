<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'variant_size_id',
        'warehouse_id',
        'on_hand',
        'reserved',
        'available',
        'min_stock_level',
    ];

    protected $casts = [
        'on_hand' => 'integer',
        'reserved' => 'integer',
    ];

    public function variantSize()
    {
        return $this->belongsTo(VariantSize::class, 'variant_size_id');
    }
    
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function getAvailableAttribute()
    {
        return $this->on_hand - $this->reserved;
    }
}



