<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserAddress extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'user_addresses';

    protected $fillable = [
        'user_id',
        'title',
        'first_name',
        'last_name',
        'phone',
        'address_line_1',
        'address_line_2',
        'district',
        'city',
        'country',
        'postal_code',
        'is_default',
        'is_active',
        'notes',
    ];
    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
