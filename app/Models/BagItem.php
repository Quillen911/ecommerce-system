<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BagItem extends Model 
{
    protected $fillable = [
        'bag_id', 
        'product_id', 
        'product_title',
        'author',
        'quantity'
    ];
    
    public function bag() {
        return $this->belongsTo(Bag::class ,'bag_id');
    }
    
    public function product() {
        return $this->belongsTo(Product::class ,'product_id');
    }
    public function productTitle()
    {
        return $this->product ? $this->product->title : null;
    }
    public function productAuthor()
    {
        return $this->product ? $this->product->author : null;
    }
}