<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'is_default',
        'is_active',
        'address',
        'phone',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];


    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'warehouse_id');
    }
}
