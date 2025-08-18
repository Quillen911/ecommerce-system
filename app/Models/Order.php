<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Order extends Model
{
    protected $table = 'orders';
    use SoftDeletes;
    protected $fillable = [
        'bag_user_id',
        'user_id',
        'credit_card_id',
        'card_holder_name',
        'order_price', 
        'cargo_price',
        'discount',
        'campaign_price',
        'campaign_id',
        'campaign_info',
        'status',
        'paid_price',
        'currency',
        'payment_id',
        'conversation_id',
        'payment_status',
        'refunded_at',
        'canceled_at',
    ];

    protected $casts = [
        'order_price' => 'decimal:4',
        'cargo_price' => 'decimal:4',
        'discount' => 'decimal:4',
        'campaign_price' => 'decimal:4',
        'paid_price' => 'decimal:4',
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
}
