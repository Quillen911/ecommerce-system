<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\ResponseHelper;
use App\Services\ElasticsearchService; 

class Product extends Model
{
    
    protected $table = 'products';

    protected $fillable = [
        'title',
        'category_id',
        'author',
        'list_price',
        'stock_quantity'
    ];


    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }
    //Elasticsearch
    protected static function boot()
    {
        parent::boot();
        static::saved(function ($product){
            app(ElasticsearchService::class)->indexDocument('products', $product->id, $product->toArray());

        });

        static::deleted(function ($product) {
            app(ElasticsearchService::class)->deleteDocument('products', $product->id);
        });

    }

}
