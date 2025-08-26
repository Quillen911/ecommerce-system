<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $fillable = [
        'username', 
        'email',
        'password',
        'phone',
        'address',
        'city',
        'district',
        'postal_code',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function creditCard()
    {
        return $this->hasMany(CreditCard::class);
    }
}
