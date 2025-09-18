<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WorkOrder;
use Inertia\Inertia;

class MechanicProfileController extends Controller
{
    /**
     * Display mechanic profile.
     */
    public function show(User $mechanic)
    {
        $mechanic->load([
            'badges' => function ($query) {
                $query->latest();
            },
            'points' => function ($query) {
                $query->latest()->take(10);
            }
        ]);
        
        $totalPoints = $mechanic->points()->sum('points');
        $monthlyPoints = $mechanic->points()
            ->whereMonth('earned_at', now()->month)
            ->whereYear('earned_at', now()->year)
            ->sum('points');
        
        $completedWorkOrders = WorkOrder::where('assigned_mechanic_id', $mechanic->id)
            ->where('status', 'selesai')
            ->count();
        
        $averageRating = WorkOrder::where('assigned_mechanic_id', $mechanic->id)
            ->whereNotNull('customer_rating')
            ->avg('customer_rating');
        
        // Service type statistics
        $serviceStats = WorkOrder::where('assigned_mechanic_id', $mechanic->id)
            ->where('status', 'selesai')
            ->selectRaw('
                SUM(CASE WHEN service_type = "ringan" THEN 1 ELSE 0 END) as light_services,
                SUM(CASE WHEN service_type = "berat" THEN 1 ELSE 0 END) as heavy_services
            ')
            ->first();
        
        // Vehicle type expertise
        $vehicleTypeStats = WorkOrder::where('assigned_mechanic_id', $mechanic->id)
            ->where('status', 'selesai')
            ->join('vehicles', 'work_orders.vehicle_id', '=', 'vehicles.id')
            ->selectRaw('
                vehicles.type,
                COUNT(*) as count
            ')
            ->groupBy('vehicles.type')
            ->pluck('count', 'type')
            ->toArray();
        
        return Inertia::render('workshop/mechanic-profile', [
            'mechanic' => $mechanic,
            'stats' => [
                'totalPoints' => $totalPoints,
                'monthlyPoints' => $monthlyPoints,
                'completedWorkOrders' => $completedWorkOrders,
                'averageRating' => round($averageRating, 1),
                'lightServices' => $serviceStats->light_services ?? 0,
                'heavyServices' => $serviceStats->heavy_services ?? 0,
                'vehicleTypeStats' => $vehicleTypeStats,
            ],
        ]);
    }
}