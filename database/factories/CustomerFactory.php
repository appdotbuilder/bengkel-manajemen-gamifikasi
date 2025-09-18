<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'phone' => '08' . fake()->randomNumber(9, true) . fake()->randomNumber(2),
            'email' => fake()->optional(0.7)->unique()->safeEmail(),
            'address' => fake()->optional(0.8)->address(),
        ];
    }
}