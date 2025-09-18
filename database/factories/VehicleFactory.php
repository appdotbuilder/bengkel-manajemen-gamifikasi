<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $brands = ['Honda', 'Yamaha', 'Suzuki', 'Kawasaki', 'TVS'];
        $models = ['Beat', 'Vario', 'Scoopy', 'PCX', 'NMAX', 'Aerox', 'Jupiter', 'Satria', 'Ninja'];
        $types = ['matic', '2tak', '4tak'];
        
        return [
            'customer_id' => Customer::factory(),
            'license_plate' => strtoupper(fake()->lexify('?? ????')),
            'brand' => fake()->randomElement($brands),
            'model' => fake()->randomElement($models),
            'year' => (string) fake()->numberBetween(2010, 2024),
            'type' => fake()->randomElement($types),
            'engine_capacity' => fake()->randomElement(['110cc', '125cc', '150cc', '160cc', '250cc']),
            'notes' => fake()->optional(0.3)->sentence(),
        ];
    }
}