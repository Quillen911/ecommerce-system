<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CampaignCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'category_id',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

}
