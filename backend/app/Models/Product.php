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
        'images'
    ];
    protected $casts = [
        'list_price' => 'float',
        'list_price_cents' => 'integer',
        'stock_quantity' => 'integer',
        'is_published' => 'boolean',
        'images' => 'array'
    ];
    public function scopePublished($query) {
        return $query->where('is_published', true);
    }
    public function getRouteKeyName() {
        return 'slug';
    }


    public function getFirstImageAttribute() {
        if (!$this->images || !is_array($this->images) || empty($this->images)) {
            return '/images/no-image.png';
        }
        
        $firstImage = $this->images[0];
        
        if (is_object($firstImage) || is_array($firstImage) || empty($firstImage)) {
            return '/images/no-image.png';
        }
        
        return '/storage/productsImages/' . $firstImage;
    }
    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function store(){
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function productAttributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    protected $appends = ['computed_attributes'];

    public function getComputedAttributesAttribute()
    {
        return $this->productAttributes()
            ->with(['attribute:id,name,code,input_type', 'option:id,attribute_id,value,slug'])
            ->get()
            ->map(function ($pa) {
                $attr = $pa->attribute;
                $val = $pa->option?->value ?? $pa->value ?? $pa->value_number ?? $pa->value_bool;
                $slug = $pa->option?->slug;
                return [
                    'code' => $attr->code,
                    'label' => $attr->name,
                    'value' => $val,
                    'slug' => $slug
                ];
            })->values();
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
