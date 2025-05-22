<?php

namespace Database\Factories;

use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'property_id' => Property::factory(),
            'code' => generate_code(),
            'name' => $this->faker->bothify('R###'), // e.g., R123
            'rent_amount' => $this->faker->randomFloat(2, 1000, 10000),
            'max_occupancy' => $this->faker->numberBetween(1, 6),
        ];
    }
}
