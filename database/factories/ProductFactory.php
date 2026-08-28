<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\Sticker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
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
            'title' => fake()->words(1, true),
            'description' => fake()->paragraph(1),
            'preview_image' => '',
            'category_id' => Category::all()->random()->id,
            'sticker_id' => Sticker::all()->random()->id,
            'price' => fake()->numberBetween(100, 1000),
            'sold_quantity' => fake()->numberBetween(0, 100000),
            'count' => fake()->numberBetween(0, 100000),
            'rating' => fake()->randomFloat(1, 0, 5),
            'is_published' => 1,
        ];
    }
}
