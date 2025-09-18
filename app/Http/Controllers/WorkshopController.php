<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WorkOrder;
use Inertia\Inertia;

class WorkshopController extends Controller
{
    /**
     * Display the workshop dashboard.
     */
    public function index()
    {
        $totalWorkOrders = WorkOrder::count();
        $activeWorkOrders = WorkOrder::whereIn('status', ['menunggu', 'dikerjakan', 'pengecekan'])->count();
        $completedWorkOrders = WorkOrder::where('status', 'selesai')->count();
        $totalMechanics = User::mechanics()->where('is_active', true)->count();
        
        // Recent work orders
        $recentWorkOrders = WorkOrder::with(['customer', 'vehicle', 'assignedMechanic'])
            ->latest()
            ->take(5)
            ->get();
        
        // Top mechanics this month
        $topMechanics = User::mechanics()
            ->withSum(['points as monthly_points' => function ($query) {
                $query->whereMonth('earned_at', now()->month)
                      ->whereYear('earned_at', now()->year);
            }], 'points')
            ->orderByDesc('monthly_points')
            ->take(5)
            ->get();
        
        return Inertia::render('workshop/dashboard', [
            'stats' => [
                'totalWorkOrders' => $totalWorkOrders,
                'activeWorkOrders' => $activeWorkOrders,
                'completedWorkOrders' => $completedWorkOrders,
                'totalMechanics' => $totalMechanics,
            ],
            'recentWorkOrders' => $recentWorkOrders,
            'topMechanics' => $topMechanics,
        ]);
    }
}