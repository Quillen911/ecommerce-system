<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Category extends Model
{
    use HasFactory; 
    protected $table = 'categories';

    protected $fillable = [
        'category_title',
        'category_slug',
        'parent_id',
    ];
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    public function genders()
    {
        return $this->belongsToMany(Gender::class, 'category_genders');
    }
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
