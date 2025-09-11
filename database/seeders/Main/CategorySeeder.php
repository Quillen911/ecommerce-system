<?php

namespace Database\Seeders\Main;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['id' => 1, 'category_title' => 'Roman', 'category_slug' => 'roman'],
            ['id' => 2, 'category_title' => 'Kişisel Gelişim', 'category_slug' => 'kisisel-gelisim'],
            ['id' => 3, 'category_title' => 'Bilim', 'category_slug' => 'bilim'],
            ['id' => 4, 'category_title' => 'Din Tasavvuf', 'category_slug' => 'din-tasavvuf'],
            ['id' => 5, 'category_title' => 'Öykü', 'category_slug' => 'oyku'],
            ['id' => 6, 'category_title' => 'Felsefe', 'category_slug' => 'felsefe'],
            ['id' => 7, 'category_title' => 'Çocuk ve Gençlik', 'category_slug' => 'cocuk-ve-genclik'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
