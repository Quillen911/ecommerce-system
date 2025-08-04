<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\ResponseHelper;
use App\Services\Search\ElasticsearchService; 
use Illuminate\Database\Eloquent\SoftDeletes;
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
            $data['list_price'] = (float) $data['list_price'];
            $data['category_title'] = $product->category?->category_title ?? '';
            
            
            app(ElasticsearchService::class)->indexDocument('products', $product->id, $data);
        });

        static::deleted(function ($product) {
            app(ElasticsearchService::class)->deleteDocument('products', $product->id);
        });
    }

}
