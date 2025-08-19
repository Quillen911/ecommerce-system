<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\PaymentStatus;
use App\Enums\OrderItemStatus;

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
        'refunded_quantity',
        'list_price',
        'list_price_cents',
        'paid_price',
        'paid_price_cents',
        'payment_transaction_id',
        'payment_status',
        'status',
        'refunded_price',
        'refunded_price_cents',
        'refunded_at',
        'canceled_at',
        'deleted_at',
    ];

    protected $casts = [
        'list_price' => 'float',
        'list_price_cents' => 'integer',
        'paid_price' => 'float',
        'paid_price_cents' => 'integer',
        'refunded_price' => 'float',
        'refunded_price_cents' => 'integer',
        'quantity' => 'integer',
        'refunded_quantity' => 'integer',
        'payment_status' => PaymentStatus::class,
        'status' => OrderItemStatus::class,
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