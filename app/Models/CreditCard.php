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
        'card_number',
        'cvv',
        'expire_year',
        'expire_month',
        'card_type',
        'card_holder_name',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}