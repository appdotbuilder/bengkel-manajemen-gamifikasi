<?php

use App\Http\Controllers\WorkshopController;
use App\Http\Controllers\WorkOrderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\MechanicProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/health-check', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
    ]);
})->name('health-check');

// Main workshop dashboard as home page
Route::get('/', function () {
    if (auth()->check()) {
        return app(WorkshopController::class)->index();
    }
    
    // Show welcome page with basic stats for unauthenticated users
    $totalWorkOrders = \App\Models\WorkOrder::count();
    $activeWorkOrders = \App\Models\WorkOrder::whereIn('status', ['menunggu', 'dikerjakan', 'pengecekan'])->count();
    $completedWorkOrders = \App\Models\WorkOrder::where('status', 'selesai')->count();
    $totalMechanics = \App\Models\User::mechanics()->where('is_active', true)->count();
    
    return Inertia::render('welcome', [
        'stats' => [
            'totalWorkOrders' => $totalWorkOrders,
            'activeWorkOrders' => $activeWorkOrders,
            'completedWorkOrders' => $completedWorkOrders,
            'totalMechanics' => $totalMechanics,
        ],
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [WorkshopController::class, 'index'])->name('dashboard');
    
    // Workshop Management Routes
    Route::get('leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');
    Route::get('mechanics/{mechanic}/profile', [MechanicProfileController::class, 'show'])->name('mechanics.profile');
    
    // Customer Management
    Route::resource('customers', CustomerController::class);
    
    // Work Order Management
    Route::resource('work-orders', WorkOrderController::class);
    
    // Vehicle Management (for adding vehicles to customers)
    Route::post('customers/{customer}/vehicles', [VehicleController::class, 'store'])->name('vehicles.store');
    Route::put('vehicles/{vehicle}', [VehicleController::class, 'update'])->name('vehicles.update');
    Route::delete('vehicles/{vehicle}', [VehicleController::class, 'destroy'])->name('vehicles.destroy');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
