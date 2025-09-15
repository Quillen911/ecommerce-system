<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Store;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Campaign>
 */
class CampaignFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'store_id' => Store::factory(),
            'store_name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'type' => fake()->randomElement(['percentage', 'fixed']),
            'is_active' => fake()->boolean(),
            'priority' => fake()->numberBetween(1, 100),
            'usage_limit' => fake()->numberBetween(1, 100),
            'usage_limit_for_user' => fake()->numberBetween(1, 100),
            'starts_at' => fake()->dateTimeBetween('-1 month', '+1 month'),
            'ends_at' => fake()->dateTimeBetween('+1 month', '+2 months'),
            'created_at' => fake()->dateTimeBetween('-1 month', '+1 month'),
            'updated_at' => fake()->dateTimeBetween('-1 month', '+1 month'),
        ];
    }
}
