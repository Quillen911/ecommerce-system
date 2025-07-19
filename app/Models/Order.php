<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'Bag_User_id',
        'price', 
        'cargo_price',
        'campaing_price',
        'campaign_info',
        'status'
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class, 'Bag_User_id');
    }

    
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
