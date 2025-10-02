<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Campaign extends Model
{
    use HasFactory;
    protected $table = 'campaigns';

    protected $fillable = [
        'name',
        'code',
        'type',
        'discount_value',
        'min_quantity',
        'usage_limit',
        'usage_count',
        'is_active',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'discount_value' => 'integer',
        'min_quantity' => 'integer',
        'usage_limit' => 'integer',
        'usage_count' => 'integer',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'campaign_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'campaign_products', 'campaign_id', 'product_id');
    }

    public function campaignProducts()
    {
        return $this->hasMany(CampaignProduct::class, 'campaign_id');
    }

}
