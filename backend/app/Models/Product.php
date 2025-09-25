<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\Search\ElasticsearchService; 
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
        'store_name',
        'title',
        'slug',
        'category_id',  
        'description',
        'meta_title',
        'meta_description',
        'list_price',
        'list_price_cents',
        'stock_quantity',
        'sold_quantity',
        'is_published',
    ];
    protected $casts = [
        'list_price' => 'float',
        'list_price_cents' => 'integer',
        'stock_quantity' => 'integer',
        'is_published' => 'boolean',
    ];

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }
    public function variantImages()
    {
        return $this->hasMany(ProductVariantImage::class, 'product_id');
    }

    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function store(){
        return $this->belongsTo(Store::class, 'store_id');
    }
    
    public function scopePublished($query) {
        return $query->where('is_published', true);
    }

    public function getStockQuantityAttribute()
    {
        return $this->variants()->sum('stock_quantity');
    }

    public function getRouteKeyName() {
        return 'slug';
    }

    public function setImageUrlAttribute()
    {
        $this->attributes['image_url'] = $this->getImageUrlAttribute();
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

            $this->attributes['image'] = basename($filePath);
        } else {
            $this->attributes['image'] = $value;
        }
    }


    //Elasticsearch
    protected static function boot()
    {
        
        parent::boot();
        static::saved(function ($product){
            $data = $product->toArray();
            $data['category_title'] = $product->category?->category_title ?? '';
            dispatch(new IndexProductToElasticsearch($data));
        });

        static::deleted(function ($product) {
            dispatch(new DeleteProductToElasticsearch($product->toArray()));
        });

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->title);
            }
        });
    }

}
