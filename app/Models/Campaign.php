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
        'per_user_limit',
        'starts_at',
        'ends_at',
    ];

    public function conditions()
    {
        return $this->hasMany(CampaignConditions::class, 'campaign_id');
    }

    public function discounts()
    {
        return $this->hasMany(CampaignDiscount::class, 'campaign_id');
    }
}
