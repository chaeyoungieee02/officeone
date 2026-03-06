<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $categories = ['Product', 'Service'];
        $units = ['pcs', 'box', 'ream', 'set', 'pack', 'unit', 'roll', 'bottle'];
        $brands = ['BIC', 'Pilot', 'Epson', 'HP', 'Canon', 'Faber-Castell', 'Staedtler', 'Paper One', 'Hard Copy', 'FOUR CANDIES'];
        $types = ['Office Supplies', 'Office Furniture', 'Electronics', 'Printing', 'Cleaning', 'Writing Instruments'];

        return [
            'item_code'   => strtoupper($this->faker->unique()->bothify('ITM-###')),
            'name'        => $this->faker->words(3, true),
            'category'    => $this->faker->randomElement($categories),
            'unit'        => $this->faker->randomElement($units),
            'unit_price'  => $this->faker->randomFloat(2, 10, 5000),
            'description' => $this->faker->sentence(10),
            'brand'       => $this->faker->randomElement($brands),
            'type'        => $this->faker->randomElement($types),
            'is_active'   => $this->faker->boolean(85),
        ];
    }
}
