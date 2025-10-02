<?php

namespace Database\Seeders\Main;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            // ✅ ANA KATEGORİLER
            ['title' => 'Erkek Çocuk', 'slug' => 'erkek-cocuk', 'parent_id' => null],
            ['title' => 'Kız Çocuk', 'slug' => 'kiz-cocuk', 'parent_id' => null],
            
            // ✅ GENEL ÜRÜN KATEGORİLERİ (Tüm çocuklar için)
            ['title' => 'Jean', 'slug' => 'jean', 'parent_id' => null],
            ['title' => 'Keten', 'slug' => 'keten', 'parent_id' => null],
            ['title' => 'Eşofman Takım', 'slug' => 'esofman-takim', 'parent_id' => null],
            
            // ✅ ERKEK ÇOCUK ALT KATEGORİLERİ
            ['title' => 'Jean', 'slug' => 'erkek-cocuk-jean', 'parent_id' => 1],
            ['title' => 'Keten', 'slug' => 'erkek-cocuk-keten', 'parent_id' => 1],
            ['title' => 'Eşofman Takım', 'slug' => 'erkek-cocuk-esofman-takim', 'parent_id' => 1],
            
            // ✅ KIZ ÇOCUK ALT KATEGORİLERİ  
            ['title' => 'Jean', 'slug' => 'kiz-cocuk-jean', 'parent_id' => 2],
            ['title' => 'Keten', 'slug' => 'kiz-cocuk-keten', 'parent_id' => 2],
            ['title' => 'Eşofman Takım', 'slug' => 'kiz-cocuk-esofman-takim', 'parent_id' => 2],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}