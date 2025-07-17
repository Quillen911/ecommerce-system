<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $table = 'orders';

    protected $fillable = [
        
        'Bag_User_id',
        'total_price',
        'status'

    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'Bag_User_id');
    }

}
