<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Store extends Model
{
    use SoftDeletes, HasFactory;
    protected $fillable = [
        'seller_id', 
        'seller_name',
        'name', 
        'phone', 
        'address', 
        'image', 
        'description', 
        'email', 
        'is_active',
        'iyzico_api_key',
        'iyzico_secret_key',
    ];
    protected $casts = [
        'iyzico_api_key' => 'encrypted',
        'iyzico_secret_key' => 'encrypted',
    ];
    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }
    public function products()
    {
        return $this->hasMany(Product::class, 'store_id');
    }
    public function campaigns()
    {
        return $this->hasMany(Campaign::class, 'store_id');
    }
}
