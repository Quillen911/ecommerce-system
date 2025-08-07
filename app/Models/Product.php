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
        'title',
        'category_id',  
        'author',
        'list_price',
        'stock_quantity'
    ];
    protected $casts = [
        'list_price' => 'float',
        'stock_quantity' => 'integer',
    ];


    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
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
