<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VariantSize extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_variant_id',
        'size_option_id',
        'sku',
        'price_cents',
        'is_active',
    ];

    protected $casts = [
        'price_cents' => 'integer',
        'is_active' => 'boolean',
    ];
    
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
    
    public function sizeOption()
    {
        return $this->belongsTo(AttributeOption::class, 'size_option_id');
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class, 'variant_size_id');
    }
    
}
