<?php

namespace Database\Seeders;

use App\Models\Landlord;
use App\Models\User;
use Carbon\Carbon;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

        Landlord::firstOrCreate(
            ['user_id' => $user->id],
            [
                'gcash_qr' => null,
                'maya_qr' => null,
            ]
        );
    }

}
