<?php

namespace Database\Factories;

use App\Models\MechanicPoint;
use App\Models\User;
use App\Models\WorkOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MechanicPoint>
 */
class MechanicPointFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $pointTypes = array_keys(MechanicPoint::getPointValues());
        $pointType = fake()->randomElement($pointTypes);
        $pointValues = MechanicPoint::getPointValues();
        $descriptions = MechanicPoint::getPointTypeDescriptions();
        
        return [
            'mechanic_id' => User::factory()->mechanic(),
            'work_order_id' => fake()->optional(0.8)->randomElement(WorkOrder::pluck('id')->toArray()),
            'point_type' => $pointType,
            'points' => $pointValues[$pointType],
            'description' => $descriptions[$pointType],
            'earned_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }
}