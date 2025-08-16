<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use Illuminate\Database\Eloquent\SoftDeletes;
class OrderItem extends Model
{
    protected $table = 'order_items';
    use SoftDeletes;
    
    protected $fillable = [
        'order_id',
        'product_id',
        'product_title',
        'product_category_title',
        'store_id',
        'store_name',
        'quantity',
        'list_price',
        'paid_price',
        'payment_transaction_id',
        'payment_status',
        'status',
        'refunded_price',
        'refunded_at',
        'canceled_at',
        'deleted_at',
    ];

    protected $casts = [
        'list_price' => 'float',
        'paid_price' => 'float',
        'refunded_price' => 'float',
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