<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = [
            // Common badges
            [
                'name' => 'First Flex',
                'slug' => 'first-flex',
                'description' => 'Complete your first task. Everyone starts somewhere!',
                'icon' => 'sparkles',
                'points_required' => 0,
                'color' => '#00f5ff',
                'rarity' => 'common',
            ],
            [
                'name' => 'Getting Started',
                'slug' => 'getting-started',
                'description' => 'Earn your first 50 points. The grind begins!',
                'icon' => 'star',
                'points_required' => 50,
                'color' => '#00f5ff',
                'rarity' => 'common',
            ],
            [
                'name' => 'Consistency King',
                'slug' => 'consistency-king',
                'description' => 'Maintain a 3-day streak. You\'re building habits!',
                'icon' => 'fire',
                'points_required' => 0,
                'color' => '#ff6b35',
                'rarity' => 'common',
            ],

            // Rare badges
            [
                'name' => 'Century Club',
                'slug' => 'century-club',
                'description' => 'Earn 100 points. Now you\'re cooking!',
                'icon' => 'trophy',
                'points_required' => 100,
                'color' => '#bf5af2',
                'rarity' => 'rare',
            ],
            [
                'name' => 'Week Warrior',
                'slug' => 'week-warrior',
                'description' => 'Maintain a 7-day streak. Absolute legend!',
                'icon' => 'zap',
                'points_required' => 0,
                'color' => '#bf5af2',
                'rarity' => 'rare',
            ],
            [
                'name' => 'Project Pro',
                'slug' => 'project-pro',
                'description' => 'Complete your first project. You finished what you started!',
                'icon' => 'rocket',
                'points_required' => 0,
                'color' => '#bf5af2',
                'rarity' => 'rare',
            ],

            // Epic badges
            [
                'name' => 'Half K Club',
                'slug' => 'half-k-club',
                'description' => 'Earn 500 points. You\'re in elite company!',
                'icon' => 'gem',
                'points_required' => 500,
                'color' => '#ff6b35',
                'rarity' => 'epic',
            ],
            [
                'name' => 'Month Master',
                'slug' => 'month-master',
                'description' => 'Maintain a 30-day streak. Unstoppable!',
                'icon' => 'crown',
                'points_required' => 0,
                'color' => '#ff6b35',
                'rarity' => 'epic',
            ],
            [
                'name' => 'Task Terminator',
                'slug' => 'task-terminator',
                'description' => 'Complete 100 tasks. You\'re a productivity machine!',
                'icon' => 'muscle',
                'points_required' => 0,
                'color' => '#ff6b35',
                'rarity' => 'epic',
            ],

            // Legendary badges
            [
                'name' => 'Legendary Flexer',
                'slug' => 'legendary-flexer',
                'description' => 'Earn 1000 points. You\'ve reached the top!',
                'icon' => 'crown',
                'points_required' => 1000,
                'color' => '#f7ff00',
                'rarity' => 'legendary',
            ],
            [
                'name' => 'Year of Grinding',
                'slug' => 'year-of-grinding',
                'description' => 'Maintain a 365-day streak. You are a legend!',
                'icon' => 'star',
                'points_required' => 0,
                'color' => '#f7ff00',
                'rarity' => 'legendary',
            ],
            [
                'name' => 'Flex God',
                'slug' => 'flex-god',
                'description' => 'Earn 5000 points. You\'ve achieved godhood!',
                'icon' => 'trophy',
                'points_required' => 5000,
                'color' => '#f7ff00',
                'rarity' => 'legendary',
            ],
        ];

        foreach ($badges as $badge) {
            Badge::create($badge);
        }
    }
}
