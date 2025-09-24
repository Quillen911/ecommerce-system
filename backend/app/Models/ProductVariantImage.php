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

    public function setImageAttribute($value)
    {
        if ($value instanceof \Illuminate\Http\UploadedFile) {
            $filePath = $value->store('productImages', 'public');
            $this->attributes['image'] = $filePath;
        } else {
            $this->attributes['image'] = $value;
        }
    }
}
