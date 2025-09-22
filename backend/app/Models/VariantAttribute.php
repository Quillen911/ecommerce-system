<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VariantAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'variant_id',
        'attribute_id',
        'option_id',
        'value'
    ];

    protected $casts = [
        'value' => 'string'
    ];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
    
    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }
    
    public function option()
    {
        return $this->belongsTo(AttributeOption::class, 'option_id');
    }
}
