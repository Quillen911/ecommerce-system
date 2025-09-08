<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\PaymentStatus;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\UserAddress;
class Order extends Model
{
    use SoftDeletes, HasFactory;
    protected $table = 'orders';
    
    protected $fillable = [
        'bag_user_id',
        'user_id',
        'user_shipping_address_id',
        'user_billing_address_id',
        'credit_card_id',
        'card_holder_name',
        'order_price', 
        'order_price_cents',
        'cargo_price',
        'cargo_price_cents',
        'discount',
        'discount_cents',
        'campaign_price',
        'campaign_price_cents',
        'campaign_id',
        'campaign_info',
        'status',
        'paid_price',
        'paid_price_cents',
        'currency',
        'payment_id',
        'conversation_id',
        'payment_status',
        'refunded_at',
        'canceled_at',
    ];

    protected $casts = [
        'user_shipping_address_id' => 'integer',
        'user_billing_address_id' => 'integer',
        'order_price_cents' => 'integer',
        'cargo_price_cents' => 'integer',
        'discount_cents' => 'integer',
        'campaign_price_cents' => 'integer',
        'paid_price_cents' => 'integer',
        'payment_status' => PaymentStatus::class,
        'status' => OrderStatus::class,
    ];
    protected $dates = ['deleted_at', 'refunded_at', 'canceled_at'];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'bag_user_id');
    }

    public function creditCard()
    {
        return $this->belongsTo(CreditCard::class, 'credit_card_id');
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }
    public function shippingAddress()
    {
        return $this->belongsTo(UserAddress::class, 'user_shipping_address_id');
    }
    public function billingAddress()
    {
        return $this->belongsTo(UserAddress::class, 'user_billing_address_id');
    }
}
