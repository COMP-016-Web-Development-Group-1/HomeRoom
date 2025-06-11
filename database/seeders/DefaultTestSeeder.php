<?php

namespace Database\Seeders;

use App\Enums\PropertyType;
use App\Models\Announcement;
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
    public function run(Landlord $landlord): void
    {

        // $property = Property::firstOrCreate([
        //     'landlord_id' => $landlord->id,
        //     'type' => PropertyType::DORM->value,
        //     'name' => 'Sample Property',
        //     'description' => 'A default seeded property',
        //     'address' => '123 Example St. Sample City',
        // ]);

        // $roomCode = generate_code();

        // $room = Room::firstOrCreate([
        //     'code' => $roomCode,
        // ], [
        //     'property_id' => $property->id,
        //     'name' => 'Room 001',
        //     'rent_amount' => 5000.00,
        //     'max_occupancy' => 3,
        // ]);

        // $room2 = Room::firstOrCreate([
        //     'code' => generate_code(),
        // ], [
        //     'property_id' => $property->id,
        //     'name' => 'Room 002',
        //     'rent_amount' => 4500.00,
        //     'max_occupancy' => 2,
        // ]);

        // $user2_password = 'password';
        // $user2 = User::firstOrCreate(['email' => 'tenant@gmail.com'], [
        //     'name' => 'Default Tenant',
        //     'email_verified_at' => Carbon::now(),
        //     'password' => Hash::make($user2_password),
        //     'role' => 'tenant',
        //     'profile_completed' => true,
        // ]);

        // Tenant::firstOrCreate([
        //     'user_id' => $user2->id,
        //     'room_id' => $room->id,
        // ]);

        $properties = $this->createTestProperties($landlord);
        $roomsByProperty = $this->createTestRooms($properties);
        // $this->createTestAnnouncements($property, $room, $room2);
    }

    /**
     * @param Landlord $landlord
     * @return Property[]
     */
    private function createTestProperties(Landlord $landlord)
    {
        $propertiesData = [
            [
                'type' => PropertyType::DORM->value,
                'name' => 'Dormitory Haven',
                'description' => 'Dormitory Haven offers a safe and comfortable living space designed specifically for students attending universities in Manila. The dorm features fully furnished rooms, high-speed internet, 24/7 security, and communal study areas to help residents focus on their academic goals. With a friendly atmosphere and proximity to shopping centers, food hubs, and public transportation, Dormitory Haven is your home away from home.',
                'address' => '1232 P. Noval Street, Sampaloc, Manila, Metro Manila, Philippines',
            ],
            [
                'type' => PropertyType::APARTMENT->value,
                'name' => 'Cityview Apartment',
                'description' => 'Cityview Apartment provides modern urban living in the heart of Cebu City. Each unit is designed for young professionals and families seeking convenience and comfort, with amenities including a swimming pool, fitness center, and 24-hour concierge. Residents enjoy easy access to business districts, schools, hospitals, and major shopping malls, making daily life stress-free and enjoyable.',
                'address' => '15 General Maxilom Avenue, Cebu City, Cebu, Philippines',
            ],
            [
                'type' => PropertyType::HOUSE->value,
                'name' => 'Sunny Family House',
                'description' => 'Sunny Family House is a spacious two-story home nestled in a peaceful subdivision, ideal for growing families who value both privacy and community. The house boasts four bedrooms, a large kitchen, a landscaped garden, and a secure two-car garage. Located near schools, parks, and local markets, it offers a perfect balance of suburban tranquility and urban accessibility.',
                'address' => '45 Mango Avenue, Green Meadows Subdivision, Quezon City, Metro Manila, Philippines',
            ],
            [
                'type' => PropertyType::CONDOMINIUM->value,
                'name' => 'Luxury Condo',
                'description' => 'Luxury Condo redefines upscale living with its breathtaking views of Manila Bay and world-class amenities. Enjoy exclusive access to infinity pools, a sky lounge, wellness spa, and a fully equipped gym. Each unit features elegant interiors with top-of-the-line appliances and smart home technology, perfect for executives and expatriates who desire both comfort and prestige.',
                'address' => '888 Roxas Boulevard, Pasay, Metro Manila, Philippines',
            ],
        ];

        $properties = [];

        foreach ($propertiesData as $data) {
            $property = Property::create([
                'landlord_id' => $landlord->id,
                'type' => $data['type'],
                'name' => $data['name'],
                'description' => $data['description'],
                'address' => $data['address'],
            ]);
            $properties[] = $property;
        }

        return $properties;
    }

    /**
     * @param Property[] $properties
     * @return array<int, Room[]> $roomsByProperty  Keyed by property ID
     */
    private function createTestRooms(array $properties)
    {
        $roomsByProperty = [];

        foreach ($properties as $property) {
            // Use type instead of name
            if ($property->type === PropertyType::HOUSE->value) {
                $roomsByProperty[$property->id][] = Room::create([
                    'property_id' => $property->id,
                    'code' => generate_code(),
                    'name' => 'Main Room',
                    'rent_amount' => 15000.00,
                    'max_occupancy' => 10,
                ]);
            } else {
                $roomCount = rand(5, 10);
                for ($i = 1; $i <= $roomCount; $i++) {
                    $roomsByProperty[$property->id][] = Room::create([
                        'property_id' => $property->id,
                        'code' => generate_code(),
                        'name' => 'Room ' . str_pad($i, 3, '0', STR_PAD_LEFT),
                        'rent_amount' => rand(8, 16) * 500,
                        'max_occupancy' => rand(1, 4),
                    ]);
                }
            }
        }
        return $roomsByProperty;
    }

    private function createTestAnnouncements(Property $property, Room $room, Room $room2): void
    {
        // System-wide announcements (no property or room)
        Announcement::factory()->systemWide()->create([
            'title' => 'System Maintenance Notice',
            'description' => 'We will be performing system maintenance on our platform from 2:00 AM to 4:00 AM this Sunday. During this time, some features may be temporarily unavailable. We apologize for any inconvenience.',
        ]);

        Announcement::factory()->systemWide()->create([
            'title' => 'New Platform Features Available',
            'description' => 'We\'ve added new features to improve your experience including real-time notifications, enhanced payment tracking, and improved communication tools. Check them out today!',
        ]);

        // Property-wide announcements
        Announcement::factory()->propertyWide($property)->create([
            'title' => 'Monthly Due Reminder',
            'description' => 'This is a friendly reminder that monthly rent payments are due by the 5th of each month. Please ensure your payments are submitted on time to avoid late fees. Thank you for your cooperation.',
        ]);

        Announcement::factory()->propertyWide($property)->create([
            'title' => 'Common Area Cleaning Schedule',
            'description' => 'Starting next week, we will be implementing a new cleaning schedule for all common areas. The lobby and hallways will be cleaned every Tuesday and Friday. Please keep these areas tidy to maintain a pleasant environment for everyone.',
        ]);

        Announcement::factory()->propertyWide($property)->create([
            'title' => 'WiFi Password Update',
            'description' => 'For security reasons, the WiFi password for all common areas has been updated. The new password is "SampleProperty2024". Please update your devices accordingly.',
        ]);

        // Room-specific announcements
        Announcement::factory()->forRoom($room)->create([
            'title' => 'Room A - Air Conditioning Maintenance',
            'description' => 'We will be servicing the air conditioning unit in Room A this Thursday between 10:00 AM and 2:00 PM. Please ensure someone is available to provide access during this time.',
        ]);

        Announcement::factory()->forRoom($room2)->create([
            'title' => 'Room A - Plumbing Inspection',
            'description' => 'A routine plumbing inspection will be conducted in Room A next Monday at 9:00 AM. This is a quick check to ensure everything is working properly. Please let us know if you have any concerns.',
        ]);

        Announcement::factory()->forRoom($room2)->create([
            'title' => 'Room B - New Tenant Welcome',
            'description' => 'We have a new tenant joining Room B next week. Please extend a warm welcome and help them settle in. If you have any questions about shared spaces or house rules, feel free to reach out.',
        ]);

        // Additional random announcements for variety
        // System-wide announcements don't need landlord association since they're global
        Announcement::factory()->count(3)->systemWide()->create();

        // Property-wide announcements should be linked to the landlord's property
        Announcement::factory()->count(2)->propertyWide($property)->create();

        // Room-specific announcements should be for rooms owned by this landlord
        Announcement::factory()->count(2)->forRoom($room)->create();
    }
}
