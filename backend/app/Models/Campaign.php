<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'store_id',
        'code',
        'type',
        'description',
        'discount_value',
        'buy_quantity',
        'pay_quantity',
        'min_subtotal',
        'usage_limit',
        'per_user_limit',
        'usage_count',
        'is_active',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'min_subtotal' => 'float',
        'is_active' => 'boolean',
        'discount_value' => 'integer',
        'buy_quantity' => 'integer',
        'pay_quantity' => 'integer',
        'min_quantity' => 'integer',
        'usage_limit' => 'integer',
        'per_user_limit' => 'integer',
        'usage_count' => 'integer',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

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

    public function campaignCategories()
    {
        return $this->hasMany(CampaignCategory::class, 'campaign_id');
    }

    public function campaign_usages()
    {
        return $this->hasMany(CampaignUsage::class, 'campaign_id');
    }

}
