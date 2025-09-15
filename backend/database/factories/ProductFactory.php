<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;
use App\Models\Store;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        'store_id' => Store::factory(),
        'store_name' => fake()->company(),
        'title' => fake()->words(3, true),
        'category_id' => Category::factory(),
        'author' => fake()->name(),
        'list_price' => fake()->randomFloat(2, 10, 1000),
        'list_price_cents' => fake()->numberBetween(1000, 100000),
        'stock_quantity' => fake()->numberBetween(0, 100),
        'sold_quantity' => fake()->numberBetween(0, 50),
        'images' => json_encode(['image1.jpg', 'image2.jpg']),
        ];
    }
}
