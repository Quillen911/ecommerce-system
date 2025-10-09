<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class OrderRefund extends Model
{
    use SoftDeletes;

    public const STATUS_REQUESTED        = 'requested';
    public const STATUS_AWAITING_PICKUP  = 'awaiting_pickup';
    public const STATUS_PICKED_UP        = 'picked_up';
    public const STATUS_IN_TRANSIT       = 'in_transit';
    public const STATUS_RECEIVED         = 'received';
    public const STATUS_COMPLETED        = 'completed';
    public const STATUS_PAYMENT_FAILED   = 'payment_failed';
    public const STATUS_SHIPMENT_FAILED  = 'shipment_failed';

    protected $fillable = [
        'order_id',
        'user_id',
        'status',
        'reason',
        'customer_note',
        'shipping_provider',
        'tracking_number',
        'refund_total_cents',
        'approved_at',
        'rejected_at',
        'picked_up_at',
        'in_transit_at',
        'received_at',
        'refunded_at',
        'payment_meta',
    ];

    protected $casts = [
        'payment_meta' => 'array',
        'picked_up_at' => 'datetime',
        'in_transit_at'=> 'datetime',
        'received_at'  => 'datetime',
        'refunded_at'  => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(OrderRefundItem::class, 'order_refund_id');
    }
}
