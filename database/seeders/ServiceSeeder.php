<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'title' => 'Private Charter',
                'description' => 'On-demand private jet charter for business or leisure travel with complete flexibility and premium service.',
                'image' => 'service1.jpg',
                'features' => [
                    '24/7 availability',
                    'Global destinations',
                    'Instant booking'
                ],
                'order' => 1,
                'is_active' => true
            ],
            [
                'title' => 'Corporate Travel',
                'description' => 'Streamlined corporate aviation solutions designed for business efficiency and executive productivity.',
                'image' => 'service2.jpg',
                'features' => [
                    'Custom schedules',
                    'Meeting facilities',
                    'Privacy & discretion'
                ],
                'order' => 2,
                'is_active' => true
            ],
            [
                'title' => 'Group Charter',
                'description' => 'Perfect for large groups, events, and special occasions requiring premium group transportation.',
                'image' => 'service3.jpg',
                'features' => [
                    'Large capacity aircraft',
                    'Event coordination',
                    'Competitive pricing'
                ],
                'order' => 3,
                'is_active' => true
            ]
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
