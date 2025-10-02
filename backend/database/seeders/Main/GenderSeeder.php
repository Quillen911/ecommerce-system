<?php

namespace Database\Seeders\Main;

use Illuminate\Database\Seeder;
use App\Models\Gender;

class GenderSeeder extends Seeder
{
    public function run()
    {
        $genders = [
            ['title' => 'Erkek Çocuk', 'slug' => 'erkek-cocuk'],
            ['title' => 'Kız Çocuk', 'slug' => 'kiz-cocuk'],
        ];

        foreach ($genders as $gender) {
            Gender::create($gender);
        }
    }
}
