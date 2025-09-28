<?php

namespace Database\Seeders\Main;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Gender;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $erkek = Gender::where('slug', 'erkek')->first();
        $kiz   = Gender::where('slug', 'kiz')->first();

        $erkekCocuk = Category::create([
            'category_title' => 'Erkek Çocuk',
            'category_slug'  => 'erkek-cocuk',
        ]);
        $erkekCocuk->genders()->attach($erkek->id);

        $kizCocuk = Category::create([
            'category_title' => 'Kız Çocuk',
            'category_slug'  => 'kiz-cocuk',
        ]);
        $kizCocuk->genders()->attach($kiz->id);

        $altKategoriler = [
            'Jean',
            'Eşofman Takım',
            'Keten Pantolon'
        ];

        foreach ($altKategoriler as $alt) {
            $slug = Str::slug($alt);

            $erkekAlt = $erkekCocuk->children()->create([
                'category_title' => $alt,
                'category_slug'  => $slug,
            ]);
            $erkekAlt->genders()->attach($erkek->id);

            $kizAlt = $kizCocuk->children()->create([
                'category_title' => $alt,
                'category_slug'  => $slug,
            ]);
            $kizAlt->genders()->attach($kiz->id);
        }
    }
}
