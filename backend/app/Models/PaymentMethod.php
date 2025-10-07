<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'provider',
        'provider_customer_id',
        'provider_payment_method_id',
        'type',
        'brand',
        'last4',
        'fingerprint',
        'is_default',
        'is_active',
        'billing_address_id',
        'metadata',
    ];
    protected $casts = [
        'is_default'              => 'boolean',
        'is_active'               => 'boolean',
        'metadata'                => 'array',
        'provider_customer_id'    => 'encrypted',
        'provider_payment_method_id' => 'encrypted',
    ];

    protected $hidden = [
        'provider_customer_id',
        'provider_payment_method_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function billingAddress()
    {
        return $this->belongsTo(UserAddress::class, 'billing_address_id');
    }
}
