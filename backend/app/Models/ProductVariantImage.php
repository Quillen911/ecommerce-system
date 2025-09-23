<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariantImage extends Model
{
    protected $fillable = ['product_variant_id', 'image', 'is_primary', 'sort_order'];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function getImageUrlAttribute()
    {
        return $this->image
            ? asset('storage/productImages/' . $this->image)
            : asset('images/no-image.png');
    }
}
