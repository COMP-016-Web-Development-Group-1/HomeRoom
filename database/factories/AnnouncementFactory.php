<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Announcement>
 */
class AnnouncementFactory extends Factory
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
            'room_id' => Room::factory(),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
        ];
    }

    public function systemWide(): static
    {
        return $this->state(fn () => [
            'property_id' => null,
            'room_id' => null,
        ]);
    }

    public function propertyWide(?Property $property = null): static
    {
        return $this->state(fn () => [
            'property_id' => $property?->id ?? Property::factory(),
            'room_id' => null,
        ]);
    }

    public function forRoom(Room $room): static
    {
        return $this->state(fn () => [
            'property_id' => $room->property_id,
            'room_id' => $room->id,
        ]);
    }
}
