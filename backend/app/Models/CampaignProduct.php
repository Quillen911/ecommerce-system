<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CampaignProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'product_id',
    ];
    
    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
