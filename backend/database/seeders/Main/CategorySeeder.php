<?php

namespace Database\Seeders\Main;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['id' => 1, 'category_title' => 'Oyunlar', 'category_slug' => 'oyunlar'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
