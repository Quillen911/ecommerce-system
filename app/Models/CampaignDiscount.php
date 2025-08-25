<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class CampaignDiscount extends Model
{
    use HasFactory;
    protected $table = 'campaign_discounts';

    protected $fillable = [
        'campaign_id',
        'discount_type',
        'discount_value',
        'applies_to',
    ];

    protected $casts = [
        'discount_value' => 'json',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }
}
