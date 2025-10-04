<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class BagItem extends Model 
{
    use HasFactory;

    protected $fillable = [
        'bag_id', 
        'product_id', 
        'variant_size_id',
        'product_title',
        'quantity',
        'unit_price_cents',
        'store_id'
    ];

    protected $casts = [
        'unit_price_cents' => 'integer',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
    
    public function bag() {
        return $this->belongsTo(Bag::class ,'bag_id');
    }
    
    public function product() {
        return $this->belongsTo(Product::class ,'product_id');
    }

    public function variantSize()
    {
        return $this->belongsTo(VariantSize::class, 'variant_size_id');
    }
    public function getPriceAttribute()
    {
        return number_format($this->unit_price_cents / 100, 2, ',', '.') . ' TL';
    }
}