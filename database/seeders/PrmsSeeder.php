<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Enums\Tier;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PrmsSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedCrewLeads();
        $this->seedPassengers();
        $this->seedResources();
    }

    private function seedCrewLeads(): void
    {
        $crew = [
            ['name' => 'Commander Vega', 'email' => 'crew1@everest.test'],
            ['name' => 'Lt. Orion', 'email' => 'crew2@everest.test'],
            ['name' => 'Lt. Nova', 'email' => 'crew3@everest.test'],
        ];

        foreach ($crew as $member) {
            User::factory()->create([
                'name' => $member['name'],
                'email' => $member['email'],
                'password' => Hash::make('password'),
                'role' => Role::CrewLead,
                'tier' => null,
            ]);
        }
    }

    private function seedPassengers(): void
    {
        $passengers = [
            ['name' => 'Alex Silver', 'email' => 'silver@passenger.test', 'tier' => Tier::Silver],
            ['name' => 'Jordan Gold', 'email' => 'gold@passenger.test', 'tier' => Tier::Gold],
            ['name' => 'Sam Platinum', 'email' => 'platinum@passenger.test', 'tier' => Tier::Platinum],
        ];

        foreach ($passengers as $passenger) {
            User::factory()->create([
                'name' => $passenger['name'],
                'email' => $passenger['email'],
                'password' => Hash::make('password'),
                'role' => Role::Passenger,
                'tier' => $passenger['tier'],
            ]);
        }
    }

    private function seedResources(): void
    {
        $resources = [
            ['name' => 'Observation Deck', 'slug' => 'observation-deck', 'minimum_tier' => Tier::Silver, 'description' => 'Panoramic views of deep space.'],
            ['name' => 'Fitness Bay', 'slug' => 'fitness-bay', 'minimum_tier' => Tier::Silver, 'description' => 'Zero-G exercise equipment.'],
            ['name' => 'Galley Lounge', 'slug' => 'galley-lounge', 'minimum_tier' => Tier::Silver, 'description' => 'Complimentary refreshments.'],
            ['name' => 'Hydroponics Lab Tour', 'slug' => 'hydroponics-lab', 'minimum_tier' => Tier::Gold, 'description' => 'Guided tour of the ship gardens.'],
            ['name' => 'VR Simulation Pod', 'slug' => 'vr-simulation-pod', 'minimum_tier' => Tier::Gold, 'description' => 'Immersive flight training experiences.'],
            ['name' => 'Private Study Carrel', 'slug' => 'study-carrel', 'minimum_tier' => Tier::Gold, 'description' => 'Quiet workspace with holo-display.'],
            ['name' => 'Captain\'s Observatory', 'slug' => 'captains-observatory', 'minimum_tier' => Tier::Platinum, 'description' => 'Exclusive forward viewing dome.'],
            ['name' => 'Spa & Cryo Suite', 'slug' => 'spa-cryo-suite', 'minimum_tier' => Tier::Platinum, 'description' => 'Premium wellness and recovery.'],
        ];

        foreach ($resources as $resource) {
            Resource::factory()->create([
                'name' => $resource['name'],
                'slug' => $resource['slug'],
                'description' => $resource['description'],
                'minimum_tier' => $resource['minimum_tier'],
                'is_active' => true,
            ]);
        }
    }
}
