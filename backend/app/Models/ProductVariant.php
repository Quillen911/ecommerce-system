<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'price_cents',
        'stock_quantity',
    ];

    protected $casts = [
        'price' => 'float',
        'price_cents' => 'integer',
        'stock_quantity' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variantAttributes()
    {
        return $this->hasMany(VariantAttribute::class, 'variant_id');
    }

    public function variantImages()
    {
        return $this->hasMany(ProductVariantImage::class, 'product_variant_id');
    }
}
