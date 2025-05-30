<?php

namespace Database\Seeders;

use App\Enums\PropertyType;
use App\Models\Landlord;
use App\Models\Property;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Hash;
use Illuminate\Database\Seeder;

class DefaultLandlordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => env('DEFAULT_LANDLORD_EMAIL', 'landlord@gmail.com')],
            [
                'name' => env('DEFAULT_LANDLORD_NAME', 'Default Landlord'),
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make(env('DEFAULT_LANDLORD_PASSWORD', 'password')),
                'role' => 'landlord',
            ]
        );

        $landlord = Landlord::firstOrCreate(
            ['user_id' => $user->id],
            [
                'gcash_qr' => null,
                'maya_qr' => null,
            ]
        );

        $property = Property::firstOrCreate([
            'landlord_id' => $landlord->id,
            'type' => PropertyType::DORM->value,
            'title' => 'Sample Property',
            'description' => 'A default seeded property',
            'address' => '123 Example St. Sample City',
        ]);

        $roomCode = generate_code();

        Room::firstOrCreate([
            'code' => $roomCode,
        ], [
            'property_id' => $property->id,
            'name' => 'Room A',
            'rent_amount' => 5000.00,
            'max_occupancy' => 3,
        ]);

        $this->command->info("  Default room created with code: $roomCode\n");
    }
}
