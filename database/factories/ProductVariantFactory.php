<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(), // Automatically creates a product if none exists
            'size' => $this->faker->randomElement(['1 oz', '15 ml', '10 ml']),
            'price' => $this->faker->randomFloat(2, 15, 50),
            'stock_quantity' => $this->faker->numberBetween(1, 50),
            'sku' => strtoupper($this->faker->bothify('???###')),
            'image_url' => $this->faker->imageUrl(),
        ];
    }
}
