<?php

namespace Database\Seeders\Main;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Ana kategoriler
        $erkekCocuk = Category::create([
            'category_title' => 'Erkek Çocuk',
            'category_slug'  => 'erkek-cocuk',
        ]);

        $kizCocuk = Category::create([
            'category_title' => 'Kız Çocuk',
            'category_slug'  => 'kiz-cocuk',
        ]);

        // Alt kategoriler
        Category::create([
            'category_title' => 'Jean',
            'category_slug'  => 'jean',
            'parent_id'      => $erkekCocuk->id,
        ]);

        Category::create([
            'category_title' => 'Eşofman Takım',
            'category_slug'  => 'esofman-takim',
            'parent_id'      => $erkekCocuk->id,
        ]);

        Category::create([
            'category_title' => 'Keten Pantolon',
            'category_slug'  => 'keten-pantolon',
            'parent_id'      => $kizCocuk->id,
        ]);
    }
}
