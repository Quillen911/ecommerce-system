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
        'variant_size_id',
        'store_id',
        'product_title',
        'product_category_title',
        'selected_options',
        'size_name',
        'color_name',
        'quantity',
        'refunded_quantity',
        'price_cents',
        'discount_price_cents',
        'paid_price_cents',
        'tax_rate',
        'tax_amount_cents',
        'payment_transaction_id',
        'status',
        'refunded_price_cents',
        'payment_status',
        'refunded_at',
    ];

    protected $casts = [
        'price_cents' => 'integer',
        'discount_price_cents' => 'integer',
        'paid_price_cents' => 'integer',
        'tax_rate' => 'integer',
        'tax_amount_cents' => 'integer',
        'refunded_price_cents' => 'integer',
        'quantity' => 'integer',
        'refunded_quantity' => 'integer',
        'payment_status' => 'string',
        'status' => 'string',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class ,'order_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class ,'product_id');
    }
    public function variantSize()
    {
        return $this->belongsTo(VariantSize::class, 'variant_size_id');
    }
    public function shippingItem()
    {
        return $this->hasOne(ShippingItems::class, 'order_item_id');
    }
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
    public function getPriceAttribute()
    {
        return number_format($this->price_cents / 100, 2, ',', '.') . ' TL';
    }
}