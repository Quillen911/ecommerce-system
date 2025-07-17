<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
