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
        'price',
        'price_cents',
        'stock_quantity',
        'sold_quantity',
        'is_popular',
        'is_active',
    ];

    protected $casts = [
        'price' => 'float',
        'price_cents' => 'integer',
        'sold_quantity' => 'integer',
        'stock_quantity' => 'integer',
        'sold_quantity' => 'integer',
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

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($variant) {
            dispatch(new IndexProductToElasticsearch($variant->product_id));
        });

        static::deleted(function ($variant) {
            dispatch(new DeleteProductToElasticsearch($variant->product_id));
        });
    }

}
