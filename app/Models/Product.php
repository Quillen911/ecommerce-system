<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\ResponseHelper;

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

    public function decreaseStock($quantity) {
        if($this->stock_quantity < $quantity) {
            return false;
        }
        $this->stock_quantity -= $quantity;
        $this->save();
        return true;
    }

}
