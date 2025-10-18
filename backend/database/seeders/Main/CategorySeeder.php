<?php

namespace Database\Seeders\Main;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            
          /*id:1*/ ['title' => 'Jean', 'slug' => 'jean', 'gender_id' => null, 'parent_id' => null],
          /*id:2*/ ['title' => 'Keten', 'slug' => 'keten', 'gender_id' => null, 'parent_id' => null],
          /*id:3*/ ['title' => 'Eşofman Takım', 'slug' => 'esofman-takim', 'gender_id' => null, 'parent_id' => null],
          
          /*id:4*/ ['title' => 'Jean', 'slug' => 'unisex-jean', 'gender_id' => 3, 'parent_id' => 1],
          /*id:5*/ ['title' => 'Keten', 'slug' => 'unisex-keten', 'gender_id' => 3, 'parent_id' => 2],
          /*id:6*/ ['title' => 'Eşofman Takım', 'slug' => 'unisex-esofman-takim', 'gender_id' => 3, 'parent_id' => 3],

          /*id:7*/ ['title' => 'Jean', 'slug' => 'erkek-cocuk-jean', 'gender_id' => 1, 'parent_id' => 1],
          /*id:8*/ ['title' => 'Keten', 'slug' => 'erkek-cocuk-keten', 'gender_id' => 1, 'parent_id' => 2],
          /*id:9*/ ['title' => 'Eşofman Takım', 'slug' => 'erkek-cocuk-esofman-takim', 'gender_id' => 1, 'parent_id' => 3],

          /*id:10*/ ['title' => 'Jean', 'slug' => 'kiz-cocuk-jean', 'gender_id' => 2, 'parent_id' => 1],
          /*id:11*/ ['title' => 'Keten', 'slug' => 'kiz-cocuk-keten', 'gender_id' => 2, 'parent_id' => 2],
          /*id:12*/ ['title' => 'Eşofman Takım', 'slug' => 'kiz-cocuk-esofman-takim', 'gender_id' => 2, 'parent_id' => 3],
            
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}