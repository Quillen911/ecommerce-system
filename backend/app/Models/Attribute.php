<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'input_type',
        'is_filterable',
        'is_required',
        'sort_order'
    ];

    protected $casts = [
        'is_filterable' => 'boolean',
        'is_required' => 'boolean',
        'sort_order' => 'integer'
    ];

    public function options()
    {
        return $this->hasMany(AttributeOption::class);
    }

}
