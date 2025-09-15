<?php

namespace Database\Seeders\Main;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate([
            'email' => 'hecksoft0@gmail.com',
        ], [
            'first_name' => 'İsmail',
            'last_name' => 'Danış',
            'username' => 'İsmail',
            'password' => Hash::make('ismail'),
            'phone' => '5555555555',
        ]);
        User::firstOrCreate([
            'email' => 'danisismail001@gmail.com',
        ], [
            'first_name' => 'İsmaill',
            'last_name' => 'Danış',
            'username' => 'İsmaill',
            'password' => Hash::make('ismail'),
            'phone' => '5555555556',
        ]);
    }
} 