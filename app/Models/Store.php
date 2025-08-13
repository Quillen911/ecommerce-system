<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'seller_id', 
        'seller_name',
        'name', 
        'phone', 
        'address', 
        'image', 
        'description', 
        'email', 
        'is_active'];

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }
    public function products()
    {
        return $this->hasMany(Product::class, 'store_id');
    }
}
