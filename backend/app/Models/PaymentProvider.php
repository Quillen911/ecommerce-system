<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentProvider extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'is_active',
        'config',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'config' => 'array',
    ];

    public function getConfigAttribute($value)
    {
        $config = json_decode($value, true);

        return [
            'api_key'    => $config['api_key']    ? decrypt($config['api_key'])    : null,
            'secret_key' => $config['secret_key'] ? decrypt($config['secret_key']) : null,
            'base_url'   => $config['base_url'] ?? null,
        ];
    }


}
