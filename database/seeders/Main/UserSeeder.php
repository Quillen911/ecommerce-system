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
            'username' => 'İsmail',
            'password' => Hash::make('ismail'),
        ]);
        User::firstOrCreate([
            'email' => 'hecksoft00@gmail.com',
        ], [
            'username' => 'İsmaill',
            'password' => Hash::make('ismail'),
        ]);
    }
} 