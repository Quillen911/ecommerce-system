<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\ResponseHelper;
use App\Services\Search\ElasticsearchService; 
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Jobs\IndexProductToElasticsearch;
use App\Jobs\DeleteProductToElasticsearch;

class Product extends Model
{
    use SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'store_id',
        'store_name',
        'title',
        'category_id',  
        'author',
        'list_price',
        'stock_quantity',
        'sold_quantity',
        'images'
    ];
    protected $casts = [
        'list_price' => 'float',
        'stock_quantity' => 'integer',
        'images' => 'array'
    ];

    public function getFirstImageAttribute() {
        return empty($this->images) ? '/images/no-image.jpg' : '/storage/productsImages/' . $this->images[0];
    }
    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function store(){
        return $this->belongsTo(Store::class, 'store_id');
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
    }

}
