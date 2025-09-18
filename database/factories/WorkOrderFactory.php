<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkOrder>
 */
class WorkOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $complaints = [
            'Mesin tidak bisa hidup',
            'Oli bocor',
            'Rem blong',
            'Suara mesin kasar',
            'Starter bermasalah',
            'Lampu mati',
            'Ganti oli rutin',
            'Service berkala',
            'Rantai kendor',
            'Ban aus'
        ];
        
        $statuses = ['menunggu', 'dikerjakan', 'pengecekan', 'selesai'];
        $serviceTypes = ['ringan', 'berat'];
        
        $status = fake()->randomElement($statuses);
        $serviceType = fake()->randomElement($serviceTypes);
        
        return [
            'wo_number' => $this->generateWoNumber(),
            'customer_id' => Customer::factory(),
            'vehicle_id' => function (array $attributes) {
                return Vehicle::factory()->create(['customer_id' => $attributes['customer_id']])->id;
            },
            'assigned_mechanic_id' => fake()->optional(0.8)->randomElement(
                User::where('role', 'mechanic')->pluck('id')->toArray() ?: [null]
            ),
            'status' => $status,
            'service_type' => $serviceType,
            'complaint' => fake()->randomElement($complaints),
            'diagnosis' => fake()->optional(0.6)->sentence(),
            'work_done' => $status === 'selesai' ? fake()->sentence() : null,
            'additional_findings' => fake()->optional(0.2)->sentence(),
            'estimated_cost' => fake()->numberBetween(50000, 500000),
            'final_cost' => $status === 'selesai' ? fake()->numberBetween(50000, 500000) : 0,
            'approved_by_head' => $status === 'selesai',
            'no_revision' => $status === 'selesai' ? fake()->boolean(70) : false,
            'customer_rating' => $status === 'selesai' ? fake()->optional(0.8)->numberBetween(3, 5) : null,
            'customer_feedback' => $status === 'selesai' ? fake()->optional(0.5)->sentence() : null,
            'overtime_work' => fake()->boolean(20),
            'overtime_hours' => fake()->boolean(20) ? fake()->randomFloat(1, 1, 4) : 0,
            'started_at' => in_array($status, ['dikerjakan', 'pengecekan', 'selesai']) ? fake()->dateTimeBetween('-7 days', 'now') : null,
            'completed_at' => in_array($status, ['pengecekan', 'selesai']) ? fake()->dateTimeBetween('-3 days', 'now') : null,
            'approved_at' => $status === 'selesai' ? fake()->dateTimeBetween('-1 day', 'now') : null,
        ];
    }

    /**
     * Generate a unique work order number.
     */
    protected function generateWoNumber(): string
    {
        $date = now()->format('Ymd');
        $sequence = random_int(1, 999);
        
        return 'WO' . $date . str_pad((string) $sequence, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Indicate that the work order is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'selesai',
            'work_done' => fake()->sentence(),
            'final_cost' => fake()->numberBetween(50000, 500000),
            'approved_by_head' => true,
            'customer_rating' => fake()->numberBetween(4, 5),
            'started_at' => fake()->dateTimeBetween('-7 days', '-3 days'),
            'completed_at' => fake()->dateTimeBetween('-3 days', '-1 day'),
            'approved_at' => fake()->dateTimeBetween('-1 day', 'now'),
        ]);
    }

    /**
     * Indicate that the work order is in progress.
     */
    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'dikerjakan',
            'started_at' => fake()->dateTimeBetween('-3 days', 'now'),
        ]);
    }
}