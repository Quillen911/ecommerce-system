<?php

namespace Database\Seeders;

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
            ['id' => 1, 'category_title' => 'Roman'],
            ['id' => 2, 'category_title' => 'Kişisel Gelişim'],
            ['id' => 3, 'category_title' => 'Bilim'],
            ['id' => 4, 'category_title' => 'Din Tasavvuf'],
            ['id' => 5, 'category_title' => 'Öykü'],
            ['id' => 6, 'category_title' => 'Felsefe'],
            ['id' => 7, 'category_title' => 'Çocuk ve Gençlik'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
