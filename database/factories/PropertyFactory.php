<?php

namespace Database\Factories;

use App\Models\Landlord;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'landlord_id' => Landlord::factory(),
            'code' => generate_code(),
            'title' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'address' => fake()->address(),
            'rent_amount' => fake()->randomFloat(2, 1000, 10000),
            'max_occupancy' => fake()->numberBetween(1, 5),
            'current_occupancy' => 0,
        ];
    }
}
