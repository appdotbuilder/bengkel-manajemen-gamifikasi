<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWorkOrderRequest;
use App\Http\Requests\UpdateWorkOrderRequest;
use App\Models\WorkOrder;
use App\Models\Customer;
use App\Models\User;
use App\Services\GamificationService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WorkOrderController extends Controller
{
    /**
     * Display a listing of work orders.
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        $mechanic = $request->get('mechanic');
        
        $query = WorkOrder::with(['customer', 'vehicle', 'assignedMechanic']);
        
        if ($status) {
            $query->where('status', $status);
        }
        
        if ($mechanic) {
            $query->where('assigned_mechanic_id', $mechanic);
        }
        
        $workOrders = $query->latest()->paginate(10);
        $mechanics = User::mechanics()->where('is_active', true)->get();
        
        return Inertia::render('work-orders/index', [
            'workOrders' => $workOrders,
            'mechanics' => $mechanics,
            'filters' => [
                'status' => $status,
                'mechanic' => $mechanic,
            ],
        ]);
    }

    /**
     * Show the form for creating a new work order.
     */
    public function create()
    {
        $customers = Customer::with('vehicles')->get();
        $mechanics = User::mechanics()->where('is_active', true)->get();
        
        return Inertia::render('work-orders/create', [
            'customers' => $customers,
            'mechanics' => $mechanics,
        ]);
    }

    /**
     * Store a newly created work order.
     */
    public function store(StoreWorkOrderRequest $request)
    {
        $workOrder = WorkOrder::create([
            'wo_number' => WorkOrder::generateWoNumber(),
            ...$request->validated(),
        ]);

        return redirect()->route('work-orders.show', $workOrder)
            ->with('success', 'Work Order berhasil dibuat.');
    }

    /**
     * Display the specified work order.
     */
    public function show(WorkOrder $workOrder)
    {
        $workOrder->load(['customer', 'vehicle', 'assignedMechanic', 'mechanicPoints']);
        
        return Inertia::render('work-orders/show', [
            'workOrder' => $workOrder,
        ]);
    }

    /**
     * Show the form for editing the work order.
     */
    public function edit(WorkOrder $workOrder)
    {
        $workOrder->load(['customer', 'vehicle']);
        $customers = Customer::with('vehicles')->get();
        $mechanics = User::mechanics()->where('is_active', true)->get();
        
        return Inertia::render('work-orders/edit', [
            'workOrder' => $workOrder,
            'customers' => $customers,
            'mechanics' => $mechanics,
        ]);
    }

    /**
     * Update the work order.
     */
    public function update(UpdateWorkOrderRequest $request, WorkOrder $workOrder)
    {
        $oldStatus = $workOrder->status;
        $data = $request->validated();
        
        // Handle status-specific updates
        if (isset($data['status'])) {
            switch ($data['status']) {
                case 'dikerjakan':
                    $data['started_at'] = $data['started_at'] ?? now();
                    break;
                case 'pengecekan':
                    $data['completed_at'] = $data['completed_at'] ?? now();
                    break;
                case 'selesai':
                    $data['approved_by_head'] = true;
                    $data['approved_at'] = $data['approved_at'] ?? now();
                    break;
            }
        }
        
        $workOrder->update($data);
        
        // Handle status changes and gamification
        if ($oldStatus !== $workOrder->status) {
            app(GamificationService::class)->handleStatusChange($workOrder, $oldStatus);
            
            // Award points for completion
            if ($workOrder->status === 'selesai' && $workOrder->assigned_mechanic_id) {
                app(GamificationService::class)->handleWorkOrderCompletion($workOrder);
                
                // Award rating points if applicable
                if ($workOrder->customer_rating >= 4) {
                    app(GamificationService::class)->awardRatingPoints($workOrder);
                }
            }
        }

        return redirect()->route('work-orders.show', $workOrder)
            ->with('success', 'Work Order berhasil diperbarui.');
    }

    /**
     * Remove the work order.
     */
    public function destroy(WorkOrder $workOrder)
    {
        $workOrder->delete();

        return redirect()->route('work-orders.index')
            ->with('success', 'Work Order berhasil dihapus.');
    }
}