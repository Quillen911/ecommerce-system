<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BagItem extends Model 
{
    protected $fillable = [
        'bagItem_id', 
        'product_id', 
        'quantity'
    ];
    
    public function bags() {
        return $this->belongsTo(Bag::class ,'bagItem_id');
    }
    
    public function product() {
        return $this->belongsTo(Product::class ,'product_id');
    }
}