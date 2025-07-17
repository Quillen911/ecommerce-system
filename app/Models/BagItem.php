<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BagItem extends Model 
{
    protected $fillable = [
        'bag_id', 
        'product_id', 
        'quantity'
    ];
    
    public function bags() {
        return $this->belongsTo(Bag::class ,'bag_id');
    }
    
    public function product() {
        return $this->belongsTo(Product::class ,'product_id');
    }
}