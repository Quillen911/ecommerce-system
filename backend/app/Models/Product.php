<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Jobs\IndexProductToElasticsearch;
use App\Jobs\DeleteProductToElasticsearch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Product extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'store_id',
        'title',
        'category_id',  
        'description',
        'meta_title',
        'meta_description',
        'total_sold_quantity',
        'is_published',
    ];
    
    protected $casts = [
        'total_sold_quantity' => 'integer',
        'is_published' => 'boolean',
    ];

    // Relations
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function variantImages()
    {
        return $this->hasMany(ProductVariantImage::class, 'product_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
    
    // Scopes
    public function scopePublished($query) 
    {
        return $query->where('is_published', true);
    }

    // Model Events Elasticsearch için
    protected static function boot()
    {
        parent::boot();
        
        static::saved(function ($product) {
            dispatch(new IndexProductToElasticsearch($product->id));
        });
        
        static::deleted(function ($product) {
            dispatch(new DeleteProductToElasticsearch($product->id));
        });
    }
}