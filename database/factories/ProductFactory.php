<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_category_id' => ProductCategory::factory(),
            'is_active' => true,
            'order' => 0,
            'sku' => fake()->unique()->bothify('SKU-####??'),
            'price' => fake()->randomFloat(2, 5, 999),
        ];
    }
}
