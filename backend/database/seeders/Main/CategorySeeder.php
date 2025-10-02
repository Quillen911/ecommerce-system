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
            ['title' => 'Jean Pantolon', 'slug' => 'jean-pantolon', 'parent_id' => null],
            ['title' => 'Keten Pantolon', 'slug' => 'keten-pantolon', 'parent_id' => null],
            ['title' => 'Eşofman Takım', 'slug' => 'esofman-takim', 'parent_id' => null],
            
            // ✅ ERKEK ÇOCUK ALT KATEGORİLERİ
            ['title' => 'Jean Pantolon', 'slug' => 'erkek-cocuk-jean-pantolon', 'parent_id' => 1],
            ['title' => 'Keten Pantolon', 'slug' => 'erkek-cocuk-keten-pantolon', 'parent_id' => 1],
            ['title' => 'Eşofman Takım', 'slug' => 'erkek-cocuk-esofman-takim', 'parent_id' => 1],
            
            // ✅ KIZ ÇOCUK ALT KATEGORİLERİ  
            ['title' => 'Jean Pantolon', 'slug' => 'kiz-cocuk-jean-pantolon', 'parent_id' => 2],
            ['title' => 'Keten Pantolon', 'slug' => 'kiz-cocuk-keten-pantolon', 'parent_id' => 2],
            ['title' => 'Eşofman Takım', 'slug' => 'kiz-cocuk-esofman-takim', 'parent_id' => 2],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // ✅ PRODUCT_CATEGORIES İLİŞKİSİNİ KUR (ÇOKLU KATEGORİ)
        $this->assignProductCategories();
    }

    private function assignProductCategories()
    {
        // Bu methodu ProductSeeder'da kullanacağız
        // Ürünleri hem genel hem cinsiyet kategorilerine bağlayacağız
    }
}