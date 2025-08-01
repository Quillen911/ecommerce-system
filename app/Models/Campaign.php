<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $table = 'campaigns';

    protected $fillable = [
        'name',
        'description',
        'type',
        'is_active',
        'priority',
        'usage_limit',
        'usage_limit_for_user',
        'user_activity',
        'user_usage',
        'starts_at',
        'ends_at',
    ];
    protected $casts = [
        'user_usage' => 'array',
    ];

    public function conditions()
    {
        return $this->hasMany(CampaignCondition::class, 'campaign_id');
    }

    public function discounts()
    {
        return $this->hasMany(CampaignDiscount::class, 'campaign_id');
    }
}
