<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gender extends Model
{
    use HasFactory;
    protected $table = 'genders';

    protected $fillable = [
        'title',
        'slug',
    ];
    public function categories()
    {
        return $this->hasMany(Category::class, 'gender_id');
    }
}
