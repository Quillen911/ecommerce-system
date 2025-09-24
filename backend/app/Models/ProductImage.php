<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = ['product_id', 'image', 'is_primary', 'sort_order'];

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

            $this->attributes['image'] = basename($filePath);
        } else {
            $this->attributes['image'] = $value;
        }
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
