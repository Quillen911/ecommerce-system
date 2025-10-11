<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bag extends Model 
{
    protected $fillable = [
        'bag_user_id',
        'campaign_id',
        'campaign_discount_cents'
    ];
    
    
    public function user(){
        return $this->belongsTo(User::class, 'bag_user_id');
    }

    public function bagItems() {
        return $this->hasMany(BagItem::class,'bag_id');
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}