<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignUserUsage extends Model
{
    protected $table = 'campaign_user_usages';

    protected $fillable = [
        'campaign_id',
        'user_id',
        'usage_count',
        'used_at',
    ];
    public function campaign()
    {
        return $this->belongsTo(Campaign::class,'campaign_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
