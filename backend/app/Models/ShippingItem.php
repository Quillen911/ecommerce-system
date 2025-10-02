<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingItems extends Model
{
    use SoftDeletes;
    protected $table = 'shipping_items';
    
    protected $fillable = [
        'order_item_id',
        'tracking_number',
        'shipping_company',
        'shipping_status',
        'estimated_delivery_date',
        'shipping_notes',
    ];

    protected $casts = [
        'estimated_delivery_date' => 'date',
    ];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }
}
