<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Campaign;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CampaignCondition>
 */
class CampaignConditionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'campaign_id' => Campaign::factory(),
            'condition_type' => fake()->randomElement(['author', 'category', 'min_bag']),
            'condition_value' => json_encode(['Sabahattin Ali']),
            'operator' => fake()->randomElement(['=', '!=', '>', '>=', '<', '<=', 'in', 'not_in']),
        ];
    }
}