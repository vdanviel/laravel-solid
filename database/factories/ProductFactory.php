<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ProductType;

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
            'name' => fake()->name(),
            'price' => fake()->numberBetween(1,1000),
            'company' => fake()->company(),
            'type_id' => ProductType::inRandomOrder()->first()->id,
            'desc' => fake()->text(),
            'stock' => fake()->randomNumber()
        ];
    }
}