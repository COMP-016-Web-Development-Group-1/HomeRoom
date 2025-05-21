<?php

namespace Database\Seeders;

use App\Models\Landlord;
use App\Models\Property;
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
                'name' => 'Default Landlord',
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

        $code = generate_code();

        Property::firstOrCreate([
            'code' => $code,
        ], [
            'landlord_id' => $landlord->id,
            'title' => 'Sample Property',
            'description' => 'A default seeded property',
            'address' => '123 Example St. Sample City',
            'rent_amount' => 5000.00,
            'max_occupancy' => 3,
            'current_occupancy' => 0,
        ]);
    }
}
