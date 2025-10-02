<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\UserAddress;
class Order extends Model
{
    use SoftDeletes, HasFactory;
    protected $table = 'orders';
    
    protected $fillable = [
        'user_id',
        'bag_id',
        'user_shipping_address_id',
        'user_billing_address_id',
        'campaign_id',
        'campaign_info',
        'order_number',
        'subtotal_cents',
        'discount_cents',
        'tax_total_cents',
        'cargo_price_cents',
        'campaign_price_cents',
        'grand_total_cents',
        'currency',
        'status',
        'refunded_at',
    ];

    protected $casts = [
        'subtotal_cents' => 'integer',
        'tax_total_cents' => 'integer',
        'cargo_price_cents' => 'integer',
        'discount_cents' => 'integer',
        'campaign_price_cents' => 'integer',
        'grand_total_cents' => 'integer',
        'status' => 'string',
        'currency' => 'string',
    ];
    protected $dates = ['refunded_at'];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function bag()
    {
        return $this->belongsTo(Bag::class, 'bag_id');
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
