<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CreditCard>
 */
class CreditCardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->words(2, true),
            'card_number' => fake()->creditCardNumber(),
            'cvv' => fake()->numberBetween(100, 999),
            'expire_year' => fake()->year(),
            'expire_month' => fake()->numberBetween(1, 12),
            'card_type' => fake()->randomElement(['visa', 'mastercard']),
            'card_holder_name' => fake()->name(),
            'is_active' => true,
        ];
    }
}