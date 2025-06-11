<?php

namespace Database\Seeders;

use App\Models\Landlord;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;
use Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // For initial run for production
        $user_password = 'password';
        $user = User::firstOrCreate(
            ['email' => env('DEFAULT_LANDLORD_EMAIL', 'landlord@gmail.com')],
            [
                'name' => 'Default Landlord',
                'email_verified_at' => now(),
                'password' => Hash::make($user_password),
                'role' => 'landlord',
                'profile_completed' => true,
            ]
        );

        $landlord = Landlord::firstOrCreate([
            'user_id' => $user->id,
        ]);

        // For Mocking the website
        if (app()->isLocal()) {
            $this->callWith(DefaultTestSeeder::class, ['landlord' => $landlord]);
        }
    }
}
