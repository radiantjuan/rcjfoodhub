<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SuppliesFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        return [
            'name' => $this->faker->word(),
            'categories_id' => rand(1, 10),
            'gram' => $this->faker->randomElement([100, 250, 500, 1000]),
            'price' => $this->faker->randomFloat(2, 200, 800),
            'stock_count' => $this->faker->numberBetween(100, 1000),
            'stock_warning_count' => 50,
            'available_soon' => false,
            'out_of_stock' => false,
            'img_url' => null,
            'sku' => $this->faker->regexify('SKU-[A-Z]{5}[0-9]{5}'),
            'product_franchise_category' => null,
        ];
    }
}
