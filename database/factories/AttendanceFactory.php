<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $isPresent = fake()->boolean(85); // 85% attendance rate
        $clockIn = $isPresent ? fake()->time('H:i:s', '08:30:00') : null;
        $clockOut = $isPresent ? fake()->time('H:i:s', '17:30:00') : null;
        $isOnTime = $isPresent && $clockIn <= '08:00:00';
        
        return [
            'mechanic_id' => User::factory()->mechanic(),
            'date' => fake()->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
            'clock_in' => $clockIn,
            'clock_out' => $clockOut,
            'is_present' => $isPresent,
            'is_on_time' => $isOnTime,
            'notes' => fake()->optional(0.2)->sentence(),
        ];
    }

    /**
     * Indicate that the attendance is perfect.
     */
    public function perfect(): static
    {
        return $this->state(fn (array $attributes) => [
            'clock_in' => fake()->time('H:i:s', '07:45:00'),
            'clock_out' => fake()->time('H:i:s', '17:30:00'),
            'is_present' => true,
            'is_on_time' => true,
        ]);
    }

    /**
     * Indicate that the attendance is absent.
     */
    public function absent(): static
    {
        return $this->state(fn (array $attributes) => [
            'clock_in' => null,
            'clock_out' => null,
            'is_present' => false,
            'is_on_time' => false,
            'notes' => fake()->randomElement(['Sakit', 'Izin', 'Tanpa Keterangan']),
        ]);
    }
}