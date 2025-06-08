<?php

namespace Database\Seeders;

use App\Enums\PropertyType;
use App\Models\Landlord;
use App\Models\Property;
use App\Models\Room;
use App\Models\Tenant;
use App\Models\User;
use Carbon\Carbon;
use Hash;
use Illuminate\Database\Seeder;

class DefaultTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user_password = 'password';
        $user = User::firstOrCreate(
            ['email' => env('DEFAULT_LANDLORD_EMAIL', 'landlord@gmail.com')],
            [
                'name' => 'Default Landlord',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make($user_password),
                'role' => 'landlord',
                'profile_completed' => true,
            ]
        );

        $landlord = Landlord::firstOrCreate(
            ['user_id' => $user->id]
        );

        $property = Property::firstOrCreate([
            'landlord_id' => $landlord->id,
            'type' => PropertyType::DORM->value,
            'title' => 'Sample Property',
            'description' => 'A default seeded property',
            'address' => '123 Example St. Sample City',
        ]);

        $roomCode = generate_code();

        $room = Room::firstOrCreate([
            'code' => $roomCode,
        ], [
            'property_id' => $property->id,
            'name' => 'Room A',
            'rent_amount' => 5000.00,
            'max_occupancy' => 3,
        ]);

        $user2_password = 'password';
        $user2 = User::firstOrCreate(['email' => 'tenant@gmail.com'], [
            'name' => 'Default Tenant',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make($user2_password),
            'role' => 'tenant',
            'profile_completed' => true,
        ]);

        Tenant::firstOrCreate([
            'user_id' => $user2->id,
            'room_id' => $room->id,
        ]);

        $this->command->info("  Default room created with code: $roomCode");
        $this->command->info("  Landlord created with email of \"{$user->email}\" and password of \"{$user_password}\"");
        $this->command->info("  Tenant created with email of \"{$user2->email}\" and password of \"{$user2_password}\"\n");
    }
}
