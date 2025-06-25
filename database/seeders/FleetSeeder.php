<?php

namespace Database\Seeders;

use App\Models\Fleet;
use Illuminate\Database\Seeder;

class FleetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fleets = [
            [
                'title' => 'Citation CJ3+',
                'category' => 'Light Jet',
                'description' => 'Perfect for short to medium-range flights with exceptional fuel efficiency and comfort for up to 7 passengers.',
                'passengers' => 7,
                'range' => 2040,
                'features' => [
                    'WiFi & Entertainment',
                    'Refreshment Center',
                    'Leather Seating'
                ],
                'is_active' => true,
                'order' => 1,
            ],
            [
                'title' => 'Hawker 850XP',
                'category' => 'Mid-Size',
                'description' => 'Ideal for transcontinental flights with spacious cabin and advanced avionics for up to 8 passengers.',
                'passengers' => 8,
                'range' => 2642,
                'features' => [
                    'Full Galley Kitchen',
                    'Conference Seating',
                    'Satellite Communication'
                ],
                'is_active' => true,
                'order' => 2,
            ],
            [
                'title' => 'Gulfstream G550',
                'category' => 'Heavy Jet',
                'description' => 'Ultra-long range capability with luxurious amenities and spacious cabin for up to 14 passengers.',
                'passengers' => 14,
                'range' => 6750,
                'features' => [
                    'Private Bedroom',
                    'Full Entertainment System',
                    'Global High-Speed WiFi'
                ],
                'is_active' => true,
                'order' => 3,
            ],
            [
                'title' => 'King Air 350',
                'category' => 'Turboprop',
                'description' => 'Cost-effective option for short regional flights with excellent performance and reliability.',
                'passengers' => 11,
                'range' => 1800,
                'features' => [
                    'Weather Radar',
                    'Air Conditioning',
                    'Comfortable Seating'
                ],
                'is_active' => true,
                'order' => 4,
            ],
            [
                'title' => 'Bombardier Global 6000',
                'category' => 'Heavy Jet',
                'description' => 'Premium ultra-long range jet with exceptional cabin comfort and advanced technology.',
                'passengers' => 13,
                'range' => 6000,
                'features' => [
                    'Master Suite',
                    'Advanced Flight Deck',
                    'High-Speed Internet'
                ],
                'is_active' => true,
                'order' => 5,
            ],
        ];

        foreach ($fleets as $fleet) {
            Fleet::create($fleet);
        }
    }
}
