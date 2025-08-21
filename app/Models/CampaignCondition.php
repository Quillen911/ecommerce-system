<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignCondition extends Model
{
    protected $table = 'campaign_conditions'; 

    protected $fillable = [
        'campaign_id',
        'condition_type',
        'condition_value',
        'operator',
    ];

    protected $casts = [
        'condition_value' => 'json',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }
}
