<?php

namespace Database\Seeders\Main;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            
            ['title' => 'Jean', 'slug' => 'jean', 'gender_id' => 3, 'parent_id' => null],
            ['title' => 'Keten', 'slug' => 'keten', 'gender_id' => 3, 'parent_id' => null],
            ['title' => 'Eşofman Takım', 'slug' => 'esofman-takim', 'gender_id' => 3, 'parent_id' => null],

            ['title' => 'Jean', 'slug' => 'erkek-cocuk-jean', 'gender_id' => 1, 'parent_id' => 1],
            ['title' => 'Keten', 'slug' => 'erkek-cocuk-keten', 'gender_id' => 1, 'parent_id' => 2],
            ['title' => 'Eşofman Takım', 'slug' => 'erkek-cocuk-esofman-takim', 'gender_id' => 1, 'parent_id' => 3],

            ['title' => 'Jean', 'slug' => 'kiz-cocuk-jean', 'gender_id' => 2, 'parent_id' => 1],
            ['title' => 'Keten', 'slug' => 'kiz-cocuk-keten', 'gender_id' => 2, 'parent_id' => 2],
            ['title' => 'Eşofman Takım', 'slug' => 'kiz-cocuk-esofman-takim', 'gender_id' => 2, 'parent_id' => 3],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}