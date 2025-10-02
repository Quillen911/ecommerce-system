<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Jobs\IndexProductToElasticsearch;
use App\Jobs\DeleteProductToElasticsearch;

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];
    protected $fillable = [
        'product_id',
        'sku',
        'slug',
        'color_name',
        'color_code',
        'price_cents',
        'is_popular',
        'is_active',
    ];

    protected $casts = [
        'color_name' => 'string',
        'color_code' => 'string',
        'price_cents' => 'integer',
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
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

    public function variantSizes()
    {
        return $this->hasMany(VariantSize::class, 'product_variant_id');
    }

    public function inventories()
    {
        return $this->hasManyThrough(
            Inventory::class,
            VariantSize::class,
            'product_variant_id', // Foreign key on VariantSize table
            'variant_size_id', // Foreign key on Inventory table
            'id', // Local key on ProductVariant table
            'id' // Local key on VariantSize table
        );
    }

}
