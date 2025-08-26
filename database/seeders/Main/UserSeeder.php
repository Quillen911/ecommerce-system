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
            'phone' => '5555555555',
            'address' => '123 Main St',
            'city' => 'New York',
            'district' => 'Manhattan',
            'postal_code' => '10001',
        ]);
        User::firstOrCreate([
            'email' => 'hecksoft00@gmail.com',
        ], [
            'username' => 'İsmaill',
            'password' => Hash::make('ismail'),
            'phone' => '5555555556',
            'address' => '123 Main St',
            'city' => 'California',
            'district' => 'Los Angeles',
            'postal_code' => '90001',
        ]);
    }
} 