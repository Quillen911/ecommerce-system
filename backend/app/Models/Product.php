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
        'slug',
        'brand',
        'category_id',  
        'gender_id',
        'description',
        'meta_title',
        'meta_description',
        'is_published',
        'total_sold_quantity'
    ];
    
    protected $casts = [
        'total_sold_quantity' => 'integer',
        'is_published' => 'boolean',
        'slug' => 'string',
        'brand' => 'string',
        'category_id' => 'integer',
        'gender_id' => 'integer',
        'description' => 'string',
        'meta_title' => 'string',
        'meta_description' => 'string',
    ];

    // Relations
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function similarProducts()
    {
        $category = $this->category;
        $parentId = $category?->parent_id;

        return static::query()
            ->whereKeyNot($this->getKey())
            ->published()
            ->when(
                $parentId,
                fn($query) => $query->whereHas('category', fn($q) => $q->where('parent_id', $parentId))
            )
            ->limit(30);
    }

    public function allVariantImages()
    {
        return $this->hasManyThrough(
            ProductVariantImage::class, 
            ProductVariant::class,
            'product_id', // Foreign key on ProductVariant table
            'product_variant_id', // Foreign key on ProductVariantImage table  
            'id', // Local key on Product table
            'id' // Local key on ProductVariant table
        );
    }

    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class, 'campaign_products', 'product_id', 'campaign_id')
                    ->withTimestamps();
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function productCategories()
    {
        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    public function primaryProductCategory()
    {
        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id')
            ->wherePivot('is_primary', true)
            ->withTimestamps();
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
    
    public function gender()
    {
        return $this->belongsTo(Gender::class, 'gender_id');
    }
    
    // Accessor: gender bilgisini otomatik al (önce kendi gender_id, sonra category.gender)
    public function getGenderInfoAttribute()
    {
        // Eğer direkt gender_id varsa onu kullan
        if ($this->gender_id) {
            return $this->gender;
        }
        // Yoksa category.gender'dan al
        return $this->category?->gender;
    }

    // Scopes
    public function scopePublished($query) 
    {
        return $query->where('is_published', true);
    }


    public function getTotalStockQuantity()
    {
        return $this->variants->with('variantSizes.inventory')->get()
            ->sum(function($variant) {
                return $variant->variantSizes->sum(function($size) {
                    return $size->inventory ? $size->inventory->on_hand : 0;
                });
            });
    }

}