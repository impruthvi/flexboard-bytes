<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            ['name' => 'Urgent', 'slug' => 'urgent', 'color' => '#ff2d92'],
            ['name' => 'Chill', 'slug' => 'chill', 'color' => '#00f5ff'],
            ['name' => 'Grind', 'slug' => 'grind', 'color' => '#ff6b35'],
            ['name' => 'Self Care', 'slug' => 'self-care', 'color' => '#bf5af2'],
            ['name' => 'Hustle', 'slug' => 'hustle', 'color' => '#f7ff00'],
            ['name' => 'Creative', 'slug' => 'creative', 'color' => '#39ff14'],
            ['name' => 'Learning', 'slug' => 'learning', 'color' => '#00f5ff'],
            ['name' => 'Health', 'slug' => 'health', 'color' => '#39ff14'],
            ['name' => 'Social', 'slug' => 'social', 'color' => '#ff2d92'],
            ['name' => 'Money', 'slug' => 'money', 'color' => '#f7ff00'],
            ['name' => 'Mindset', 'slug' => 'mindset', 'color' => '#bf5af2'],
            ['name' => 'Goals', 'slug' => 'goals', 'color' => '#ff6b35'],
        ];

        foreach ($tags as $tag) {
            Tag::create($tag);
        }
    }
}
