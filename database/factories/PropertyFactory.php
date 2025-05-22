<?php

namespace Database\Factories;

use App\Models\Landlord;
use App\PropertyType;
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
            'type' => fake()->randomElement(PropertyType::cases())->value,
            'title' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'address' => fake()->address(),
        ];
    }
}
