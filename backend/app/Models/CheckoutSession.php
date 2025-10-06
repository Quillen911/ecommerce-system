<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class CheckoutSession extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'checkout_sessions';

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $guarded = [];
    
    protected $fillable = [
        'id',
        'user_id',
        'bag_id',
        'bag_snapshot',
        'shipping_data',
        'billing_data',
        'payment_data',
        'meta',
        'status',
        'expires_at',
    ];

    protected $casts = [
        'bag_snapshot'  => 'array',
        'shipping_data' => 'array',
        'billing_data'  => 'array',
        'payment_data'  => 'array',
        'meta'          => 'array',
        'expires_at'    => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
