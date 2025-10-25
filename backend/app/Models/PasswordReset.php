<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PasswordReset extends Model
{
    use SoftDeletes;

    protected $table = 'password_resets';

    protected $fillable = [
        'user_id',
        'email',
        'token',
        'status',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'email' => 'string',
        'status' => 'string',
    ];
    
    protected $hidden = [
        'token',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
