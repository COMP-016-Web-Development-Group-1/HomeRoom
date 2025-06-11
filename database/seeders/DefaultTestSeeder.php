<?php

namespace Database\Seeders;

use App\Enums\MaintenanceRequestStatus;
use App\Enums\PropertyType;
use App\Models\Announcement;
use App\Models\Landlord;
use App\Models\MaintenanceRequest;
use App\Models\Property;
use App\Models\Room;
use App\Models\Tenant;
use App\Models\User;
use Hash;
use Illuminate\Database\Seeder;

class DefaultTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Landlord $landlord): void
    {
        $properties = $this->createTestProperties($landlord);
        $rooms = $this->createTestRooms($properties);
        $tenants = $this->createTestTenants($rooms);
        $this->createTestMaintenanceRequest($tenants);
    }

    /**
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
     * @param  Property[]  $properties
     * @return Room[]
     */
    private function createTestRooms(array $properties)
    {
        $rooms = [];

        foreach ($properties as $property) {
            if ($property->type === PropertyType::HOUSE->value) {
                $rooms[] = Room::create([
                    'property_id' => $property->id,
                    'code' => generate_code(),
                    'name' => 'Main Room',
                    'rent_amount' => 15000.00,
                    'max_occupancy' => 10,
                ]);
            } else {
                $roomCount = rand(5, 10);
                for ($i = 1; $i <= $roomCount; $i++) {
                    $rooms[] = Room::create([
                        'property_id' => $property->id,
                        'code' => generate_code(),
                        'name' => 'Room ' . str_pad($i, 3, '0', STR_PAD_LEFT),
                        'rent_amount' => rand(8, 16) * 500, // 4000-8000
                        'max_occupancy' => rand(1, 6),
                    ]);
                }
            }
        }

        return $rooms;
    }

    /**
     * @param  Room[]  $rooms
     * @return Tenant[]
     */
    private function createTestTenants(array $rooms): array
    {
        $tenants = [];

        foreach ($rooms as $room) {
            $tenantCount = rand(0, $room->max_occupancy);

            for ($j = 1; $j <= $tenantCount; $j++) {
                $user = User::create([
                    'name' => fake()->name,
                    'email' => fake()->unique()->safeEmail,
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                    'role' => 'tenant',
                    'profile_completed' => true,
                ]);

                $tenant = Tenant::create([
                    'user_id' => $user->id,
                    'room_id' => $room->id,
                ]);

                $tenants[] = $tenant;
            }
        }

        return $tenants;
    }


    /**
     * Creates 0–2 maintenance requests per tenant room, choosing from a pool of issues.
     *
     * @param  Tenant[]  $tenants
     */
    private function createTestMaintenanceRequest(array $tenants): void
    {
        $issues = [
            [
                'title' => 'Leaky Faucet',
                'description' => "Hello,\n\nI wanted to report that the faucet in the bathroom is leaking continuously. Water keeps dripping even when turned off completely.\nThis is causing water to pool in the sink and makes a constant dripping noise throughout the night.\nCould someone please take a look at it soon?\n\nThank you!",
            ],
            [
                'title' => 'Air Conditioner Not Cooling',
                'description' => "Hi,\n\nThe air conditioner in my room is running but it's not blowing any cold air. I tried adjusting the thermostat and cleaning the filter, but it didn't help.\nIt's been very uncomfortable during the afternoons.\nCan you kindly send someone to check or repair it?\n\nBest regards.",
            ],
            [
                'title' => 'Broken Door Lock',
                'description' => "Good day,\n\nThe lock on my door has been difficult to turn and now it's completely jammed. I am unable to lock my room when I go out, which is a safety concern for me.\nPlease send a maintenance staff to fix or replace the lock as soon as possible.\n\nThank you for your assistance.",
            ],
            [
                'title' => 'Internet Connectivity Issues',
                'description' => "Hello Admin,\n\nI've been experiencing frequent internet disconnections in my room. The WiFi signal is weak and sometimes the network disappears entirely.\nThis has affected my ability to attend online classes and meetings.\nWould appreciate if you could check the router or the network connection in our area.\n\nSincerely.",
            ],
            [
                'title' => 'Clogged Drain',
                'description' => "Hi,\n\nThe bathroom drain is clogged and water takes a long time to go down. It sometimes overflows onto the floor, making it slippery and causing a bad smell.\nCan you please send someone to clean or fix the drain soon?\n\nThanks!",
            ],
            [
                'title' => 'No Hot Water',
                'description' => "Hello,\n\nThere has been no hot water in the shower for the past couple of days. I checked with my roommates and they're experiencing the same issue.\nCould you please arrange for the water heater to be inspected and repaired?\n\nThank you very much.",
            ],
            [
                'title' => 'Pest Infestation',
                'description' => "Hi,\n\nI have noticed several cockroaches and ants in the kitchen and bathroom areas. They seem to be coming from under the sink.\nIt's getting worse, and I'm worried about hygiene and food safety.\nWould you please arrange for pest control soon?\n\nBest regards.",
            ],
            [
                'title' => 'Light Bulb Replacement',
                'description' => "Greetings,\n\nThe light bulb in the hallway right outside my room has burnt out and it's very dark at night. It's a bit hazardous to walk through that area.\nCould you please have someone replace the bulb?\n\nThank you!",
            ],
            [
                'title' => 'Cracked Window',
                'description' => "Hello,\n\nThere's a visible crack in the window glass of my room. I'm concerned it might break further, especially during strong winds or rain.\nCould maintenance please check and repair or replace the window soon?\n\nThank you.",
            ],
            [
                'title' => 'Washing Machine Not Working',
                'description' => "Hi,\n\nThe shared washing machine is not spinning during the wash cycle. Clothes remain soaking wet after the cycle ends.\nThis has been an ongoing issue for the past week.\nPlease send someone to inspect and repair the washing machine.\n\nThank you for your help.",
            ],
            [
                'title' => 'Water Heater Making Loud Noise',
                'description' => "Hello,\n\nThe water heater is making loud banging and rumbling noises when it's on. I'm worried it might be a sign of a malfunction.\nPlease have it inspected soon to avoid further damage or safety risks.\n\nThanks!",
            ],
            [
                'title' => 'Ceiling Leak During Rain',
                'description' => "Hi,\n\nThere's a leak in the ceiling that becomes active whenever it rains. Water drips down slowly near the corner of the room.\nIt's starting to stain the ceiling and might damage furniture.\nPlease assist at your earliest convenience.\n\nRegards.",
            ],
            [
                'title' => "Toilet Won't Flush Properly",
                'description' => "Good day,\n\nThe toilet in our bathroom isn't flushing completely. Sometimes it takes two or more tries, and even then, it doesn't clear properly.\nCould maintenance please take a look?\n\nAppreciate your help.",
            ],
            [
                'title' => 'Power Outlet Sparks',
                'description' => "Hi,\n\nOne of the power outlets in my room sparks when I plug anything in. I'm afraid it could be a fire hazard.\nCan you send an electrician to inspect it?\n\nThank you.",
            ],
            [
                'title' => 'Mold on Wall',
                'description' => "Hello,\n\nI've noticed mold growing on one of the walls in my room, possibly due to humidity or a hidden leak.\nThis could be a health issue. Can maintenance take a look?\n\nThank you very much.",
            ],
            [
                'title' => 'Broken Window Screen',
                'description' => "Hi,\n\nThe screen on the window is torn and bugs are coming in at night.\nPlease send someone to replace or repair it.\n\nThanks!",
            ],
            [
                'title' => 'Fridge Not Cooling',
                'description' => "Hi Admin,\n\nThe shared refrigerator in the common area is not cooling. Food is spoiling quickly.\nCan someone please check it urgently?\n\nThanks!",
            ],
            [
                'title' => 'Loose Towel Rack',
                'description' => "Hello,\n\nThe towel rack in the bathroom is coming loose from the wall. It feels like it could fall off any moment.\nCan it be tightened or reinstalled properly?\n\nThanks for your help!",
            ],
            [
                'title' => 'Elevator Stuck Often',
                'description' => "Hi,\n\nThe elevator in the building gets stuck frequently, especially on the 3rd floor. It's becoming unreliable.\nCould this be looked into for everyone's safety?\n\nThanks!",
            ],
            [
                'title' => 'Smoke Detector Beeping',
                'description' => "Good day,\n\nThe smoke detector in my unit is constantly beeping. I've replaced the battery but the noise continues.\nPlease have someone check or replace it.\n\nRegards.",
            ],
        ];

        $weights = [0, 0, 0, 0, 0, 0, 1, 1, 2, 2]; // 60% for 0, (20% for 1 or 2)
        foreach ($tenants as $tenant) {
            $requestsToCreate = fake()->randomElement($weights);
            if ($requestsToCreate === 0) {
                continue;
            }

            $chosenIssues = collect($issues)->shuffle()->take($requestsToCreate);
            foreach ($chosenIssues as $issue) {
                MaintenanceRequest::create([
                    'tenant_id' => $tenant->id,
                    'room_id' => $tenant->room_id,
                    'title' => $issue['title'],
                    'description' => $issue['description'],
                    'status' => fake()->randomElement(MaintenanceRequestStatus::cases())->value,
                ]);
            }
        }
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
