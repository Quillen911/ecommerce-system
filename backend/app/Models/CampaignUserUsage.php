<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class CampaignUserUsage extends Model
{
    protected $table = 'campaign_user_usages';
    use SoftDeletes;
    protected $fillable = [
        'campaign_id',
        'campaign_name',
        'user_id',
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
