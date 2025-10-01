<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class ProductVariantImage extends Model
{
    protected $fillable = ['product_variant_id', 'image', 'is_primary', 'sort_order'];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function getImageUrlAttribute()
    {
        return $this->image
            ? asset('storage/productImages/' . $this->image)
            : asset('images/no-image.png');
    }

    public function setImageAttribute($value)
    {
        if ($value instanceof UploadedFile) {
            $filePath = $value->store('productImages', 'public');

            $this->attributes['image'] = basename($filePath);
        } else {
            $this->attributes['image'] = $value;
        }
    }
}
