<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_method_id',
        'provider',
        'provider_payment_id',
        'conversation_id',
        'amount_cents',
        'authorized_amount_cents',
        'captured_amount_cents',
        'refunded_amount_cents',
        'currency',
        'status',
        'installment_count',
        'installment_commission_cents',
        'three_ds_status',
        'error_code',
        'error_message',
        'authorized_at',
        'captured_at',
        'refunded_at',
        'payload',
    ];

    protected $casts = [
        'amount_cents' => 'integer',
        'authorized_amount_cents' => 'integer',
        'captured_amount_cents' => 'integer',
        'refunded_amount_cents' => 'integer',
        'installment_count' => 'integer',
        'installment_commission_cents' => 'integer',
        'currency' => 'string',
        'status' => 'string',
        'three_ds_status' => 'string',
        'error_code' => 'string',
        'error_message' => 'string',
        'payload' => 'array',
    ];

    protected $dates = [
        'authorized_at',
        'captured_at',
        'refunded_at',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
}
