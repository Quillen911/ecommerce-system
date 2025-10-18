<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderRefundItem extends Model
{
    protected $table = 'order_refund_items';

    protected $fillable = [
        'order_refund_id',
        'order_item_id',
        'byWho',
        'quantity',
        'reason',
        'refund_amount_cents',
        'inspection_status',
        'inspection_note',
    ];

    public function orderRefund()
    {
        return $this->belongsTo(OrderRefund::class, 'order_refund_id');
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }
}
