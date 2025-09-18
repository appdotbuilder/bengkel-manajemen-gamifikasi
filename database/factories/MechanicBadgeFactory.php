<?php

namespace Database\Factories;

use App\Models\MechanicBadge;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MechanicBadge>
 */
class MechanicBadgeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $badgeConfig = MechanicBadge::getBadgeConfig();
        $badgeType = fake()->randomElement(array_keys($badgeConfig));
        $config = $badgeConfig[$badgeType];
        
        return [
            'mechanic_id' => User::factory()->mechanic(),
            'badge_type' => $badgeType,
            'title' => $config['title'],
            'description' => $config['description'],
            'icon' => $config['icon'],
            'earned_at' => fake()->dateTimeBetween('-60 days', 'now'),
        ];
    }
}