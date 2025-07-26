<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignDiscount extends Model
{
    protected $table = 'campaign_discounts';

    protected $fillable = [
        'campaign_id',
        'discount_type',
        'discount_value',
        'applies_to',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }
}
