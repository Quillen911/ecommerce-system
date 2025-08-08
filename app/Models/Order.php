<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Order extends Model
{
    protected $table = 'orders';
    use SoftDeletes;
    protected $fillable = [
        'Bag_User_id',
        'user_id',
        'credit_card_id',
        'card_holder_name',
        'price', 
        'cargo_price',
        'discount',
        'campaing_price',
        'campaign_id',
        'campaign_info',
        'status'
    ];
    protected $dates = ['deleted_at'];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'Bag_User_id');
    }
    public function user_id()
    {
        return $this->belongsTo(User::class, 'user_id');
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
