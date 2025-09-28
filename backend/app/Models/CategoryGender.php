<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryGender extends Model
{
    use HasFactory;
    //pivot model
    protected $table = 'category_genders';

    protected $fillable = [
        'category_id',
        'gender_id',
    ];
    
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function gender()
    {
        return $this->belongsTo(Gender::class, 'gender_id');
    }
}
