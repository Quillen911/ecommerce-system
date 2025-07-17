<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bag extends Model 
{
    protected $fillable = [
        'Bag_User_id'
    ];
    
    
    public function user(){
        return $this->belongsTo(User::class, 'Bag_User_id');
    }

    public function bagItems() {
        return $this->hasMany(BagItem::class,'bag_id');
    }
}