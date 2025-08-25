<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Campaign;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CampaignDiscount>
 */
class CampaignDiscountFactory extends Factory
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
            'discount_type' => fake()->randomElement(['percentage', 'fixed', 'x_buy_y_pay']),
            'discount_value' => json_encode(['percentage' => 10]),
        ];
    }
}