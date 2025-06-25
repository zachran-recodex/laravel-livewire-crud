<?php

namespace Database\Seeders;

use App\Models\Hero;
use Illuminate\Database\Seeder;

class HeroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $heroes = [
            [
                'title' => 'Main Hero Banner 1',
                'image' => 'hero1.jpg',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Featured Product Showcase',
                'image' => 'hero2.jpg',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Brand Story Section',
                'image' => 'hero3.jpg',
                'order' => 3,
                'is_active' => false,
            ],
        ];

        foreach ($heroes as $hero) {
            Hero::create($hero);
        }
    }
}
