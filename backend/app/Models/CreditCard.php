<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class CreditCard extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'credit_cards';

    protected $fillable = [
        'user_id',
        'name',
        'last_four_digits',      
        'expire_year',
        'expire_month',
        'card_type',
        'card_holder_name',
        'is_active',
        'iyzico_card_token',     
        'iyzico_card_user_key',  
    ];

    protected $hidden = ['iyzico_card_token', 'iyzico_card_user_key'];

    protected $casts = [
        'iyzico_card_token' => 'encrypted',
        'iyzico_card_user_key' => 'encrypted'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}