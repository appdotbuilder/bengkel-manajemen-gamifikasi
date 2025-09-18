<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\WorkOrder;
use App\Models\MechanicPoint;
use App\Services\GamificationService;
use Illuminate\Database\Seeder;

class WorkshopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create users with different roles
        $owner = User::factory()->owner()->create([
            'name' => 'Owner Bengkel',
            'email' => 'owner@bengkel.com',
        ]);

        $headMechanic = User::factory()->headMechanic()->create([
            'name' => 'Kepala Mekanik',
            'email' => 'kepala@bengkel.com',
        ]);

        $admin = User::factory()->admin()->create([
            'name' => 'Admin Bengkel',
            'email' => 'admin@bengkel.com',
        ]);

        // Create mechanics
        $mechanics = User::factory()->mechanic()->count(5)->create();

        // Create customers
        $customers = Customer::factory()->count(20)->create();

        // Create vehicles for customers
        $customers->each(function ($customer) {
            Vehicle::factory()->count(random_int(1, 3))->create([
                'customer_id' => $customer->id,
            ]);
        });

        // Create work orders
        $workOrders = collect();
        for ($i = 0; $i < 30; $i++) {
            $customer = $customers->random();
            $vehicle = $customer->vehicles->random();
            $mechanic = $mechanics->random();

            $workOrder = WorkOrder::factory()->create([
                'customer_id' => $customer->id,
                'vehicle_id' => $vehicle->id,
                'assigned_mechanic_id' => $mechanic->id,
            ]);

            $workOrders->push($workOrder);
        }

        // Create some completed work orders with points
        $completedOrders = WorkOrder::factory()->completed()->count(15)->create();
        
        $gamificationService = new GamificationService();
        
        foreach ($completedOrders as $order) {
            if ($order->assigned_mechanic_id) {
                $gamificationService->handleWorkOrderCompletion($order);
            }
        }

        // Add some additional sample points for variety
        foreach ($mechanics as $mechanic) {
            // Daily attendance points
            for ($i = 0; $i < 20; $i++) {
                MechanicPoint::create([
                    'mechanic_id' => $mechanic->id,
                    'point_type' => 'attendance_daily',
                    'points' => 2,
                    'description' => 'Hadir tepat waktu',
                    'earned_at' => now()->subDays(random_int(1, 30)),
                ]);
            }

            // Monthly attendance bonus
            if (random_int(1, 100) <= 60) { // 60% chance
                MechanicPoint::create([
                    'mechanic_id' => $mechanic->id,
                    'point_type' => 'attendance_monthly',
                    'points' => 50,
                    'description' => 'Tidak ada absen selama sebulan',
                    'earned_at' => now()->subDays(random_int(1, 15)),
                ]);
            }
        }
    }
}