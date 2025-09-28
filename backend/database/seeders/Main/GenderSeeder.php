<?php

namespace Database\Seeders\Main;

use Illuminate\Database\Seeder;
use App\Models\Gender;

class GenderSeeder extends Seeder
{
    public function run()
    {
        $genders = [
            ['title' => 'Erkek', 'slug' => 'erkek'],
            ['title' => 'Kız', 'slug' => 'kiz'],
        ];

        foreach ($genders as $gender) {
            Gender::create($gender);
        }
    }
}
