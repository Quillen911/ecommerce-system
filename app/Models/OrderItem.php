<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class OrderItem extends Model
{
    protected $table = 'order_items';
    
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'payment_transaction_id',
        'payment_status',
        'refunded_amount',
        'refunded_at',
        'canceled_at',

    ];

    public function order()
    {
        return $this->belongsTo(Order::class ,'order_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class ,'product_id');
    }
    
}