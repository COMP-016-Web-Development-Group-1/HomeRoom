<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Landlord>
 */
class LandlordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'gcash_qr' => $this->faker->optional()->image(
                storage_path('app/public/qr_codes'),
                640,
                480,
                null,
                false
            ),
            'maya_qr' => $this->faker->optional()->image(
                storage_path('app/public/qr_codes'),
                640,
                480,
                null,
                false
            ),
            'user_id' => User::factory(),
        ];
    }
}
