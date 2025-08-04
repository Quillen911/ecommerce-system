<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $table = 'campaigns';

    protected $fillable = [
        'name',
        'description',
        'condition_logic',
        'type',
        'is_active',
        'priority',
        'usage_limit',
        'usage_limit_for_user',
        'starts_at',
        'ends_at',
    ];

    public function conditions()
    {
        return $this->hasMany(CampaignCondition::class, 'campaign_id');
    }

    public function discounts()
    {
        return $this->hasMany(CampaignDiscount::class, 'campaign_id');
    }
    public function campaign_user_usages()
    {
        return $this->hasMany(CampaignUserUsage::class, 'campaign_id');
    }
}
