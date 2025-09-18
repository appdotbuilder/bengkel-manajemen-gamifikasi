<?php

namespace App\Services;

use App\Models\MechanicPoint;
use App\Models\MechanicBadge;
use App\Models\WorkOrder;
use App\Models\User;
use App\Models\Vehicle;

class GamificationService
{
    /**
     * Handle work order completion and award points.
     */
    public function handleWorkOrderCompletion(WorkOrder $workOrder): void
    {
        if (!$workOrder->assigned_mechanic_id) {
            return;
        }

        // Award service completion points
        $this->awardServicePoints($workOrder);

        // Award no revision bonus
        if ($workOrder->no_revision) {
            $this->awardNoRevisionPoints($workOrder);
        }

        // Award additional findings points
        if ($workOrder->additional_findings) {
            $this->awardAdditionalFindingsPoints($workOrder);
        }

        // Award overtime points
        if ($workOrder->overtime_work && $workOrder->overtime_hours > 0) {
            $this->awardOvertimePoints($workOrder);
        }

        // Check for repeat customer
        $this->checkRepeatCustomer($workOrder);

        // Check and award badges
        $this->checkAndAwardBadges($workOrder->assigned_mechanic_id);
    }

    /**
     * Award service completion points based on service type.
     */
    protected function awardServicePoints(WorkOrder $workOrder): void
    {
        $pointValues = MechanicPoint::getPointValues();
        $pointType = $workOrder->service_type === 'berat' ? 'service_heavy' : 'service_light';
        $points = $pointValues[$pointType];

        MechanicPoint::create([
            'mechanic_id' => $workOrder->assigned_mechanic_id,
            'work_order_id' => $workOrder->id,
            'point_type' => $pointType,
            'points' => $points,
            'description' => "Menyelesaikan servis {$workOrder->service_type} - WO #{$workOrder->wo_number}",
            'earned_at' => now(),
        ]);
    }

    /**
     * Award no revision bonus points.
     */
    protected function awardNoRevisionPoints(WorkOrder $workOrder): void
    {
        $pointValues = MechanicPoint::getPointValues();
        $points = $pointValues['no_revision'];

        MechanicPoint::create([
            'mechanic_id' => $workOrder->assigned_mechanic_id,
            'work_order_id' => $workOrder->id,
            'point_type' => 'no_revision',
            'points' => $points,
            'description' => "Kerja rapi tanpa revisi - WO #{$workOrder->wo_number}",
            'earned_at' => now(),
        ]);
    }

    /**
     * Award additional findings points.
     */
    protected function awardAdditionalFindingsPoints(WorkOrder $workOrder): void
    {
        $pointValues = MechanicPoint::getPointValues();
        $points = $pointValues['additional_finding'];

        MechanicPoint::create([
            'mechanic_id' => $workOrder->assigned_mechanic_id,
            'work_order_id' => $workOrder->id,
            'point_type' => 'additional_finding',
            'points' => $points,
            'description' => "Menemukan kerusakan tambahan - WO #{$workOrder->wo_number}",
            'earned_at' => now(),
        ]);
    }

    /**
     * Award overtime points.
     */
    protected function awardOvertimePoints(WorkOrder $workOrder): void
    {
        $pointValues = MechanicPoint::getPointValues();
        $pointsPerHour = $pointValues['overtime'];
        $totalPoints = (int) ($workOrder->overtime_hours * $pointsPerHour);

        MechanicPoint::create([
            'mechanic_id' => $workOrder->assigned_mechanic_id,
            'work_order_id' => $workOrder->id,
            'point_type' => 'overtime',
            'points' => $totalPoints,
            'description' => "Kerja lembur {$workOrder->overtime_hours} jam - WO #{$workOrder->wo_number}",
            'earned_at' => now(),
        ]);
    }

    /**
     * Award customer rating points.
     */
    public function awardRatingPoints(WorkOrder $workOrder): void
    {
        if (!$workOrder->assigned_mechanic_id || $workOrder->customer_rating < 4) {
            return;
        }

        $pointValues = MechanicPoint::getPointValues();
        $points = $pointValues['customer_rating'];

        MechanicPoint::create([
            'mechanic_id' => $workOrder->assigned_mechanic_id,
            'work_order_id' => $workOrder->id,
            'point_type' => 'customer_rating',
            'points' => $points,
            'description' => "Rating pelanggan {$workOrder->customer_rating} bintang - WO #{$workOrder->wo_number}",
            'earned_at' => now(),
        ]);
    }

    /**
     * Check and award repeat customer points.
     */
    protected function checkRepeatCustomer(WorkOrder $workOrder): void
    {
        $previousOrders = WorkOrder::where('customer_id', $workOrder->customer_id)
            ->where('assigned_mechanic_id', $workOrder->assigned_mechanic_id)
            ->where('status', 'selesai')
            ->where('id', '!=', $workOrder->id)
            ->count();

        if ($previousOrders > 0) {
            $pointValues = MechanicPoint::getPointValues();
            $points = $pointValues['repeat_customer'];

            MechanicPoint::create([
                'mechanic_id' => $workOrder->assigned_mechanic_id,
                'work_order_id' => $workOrder->id,
                'point_type' => 'repeat_customer',
                'points' => $points,
                'description' => "Pelanggan repeat order - WO #{$workOrder->wo_number}",
                'earned_at' => now(),
            ]);
        }
    }

    /**
     * Award daily attendance points.
     */
    public function awardDailyAttendancePoints(int $mechanicId): void
    {
        $pointValues = MechanicPoint::getPointValues();
        $points = $pointValues['attendance_daily'];

        MechanicPoint::create([
            'mechanic_id' => $mechanicId,
            'point_type' => 'attendance_daily',
            'points' => $points,
            'description' => 'Hadir tepat waktu hari ini',
            'earned_at' => now(),
        ]);
    }

    /**
     * Award monthly perfect attendance points.
     */
    public function awardMonthlyAttendancePoints(int $mechanicId): void
    {
        $pointValues = MechanicPoint::getPointValues();
        $points = $pointValues['attendance_monthly'];

        MechanicPoint::create([
            'mechanic_id' => $mechanicId,
            'point_type' => 'attendance_monthly',
            'points' => $points,
            'description' => 'Tidak ada absen selama sebulan',
            'earned_at' => now(),
        ]);
    }

    /**
     * Check and award badges based on achievements.
     */
    public function checkAndAwardBadges(int $mechanicId): void
    {
        $mechanic = User::find($mechanicId);
        if (!$mechanic) return;

        // Check specialist badges
        $this->checkSpecialistBadges($mechanic);
    }

    /**
     * Check and award specialist badges based on vehicle types serviced.
     */
    protected function checkSpecialistBadges(User $mechanic): void
    {
        $completedOrders = WorkOrder::where('assigned_mechanic_id', $mechanic->id)
            ->where('status', 'selesai')
            ->join('vehicles', 'work_orders.vehicle_id', '=', 'vehicles.id')
            ->selectRaw('vehicles.type, COUNT(*) as count')
            ->groupBy('vehicles.type')
            ->pluck('count', 'type');

        $badgeConfig = MechanicBadge::getBadgeConfig();
        $specialistThreshold = 50;

        foreach ($completedOrders as $vehicleType => $count) {
            if ($count >= $specialistThreshold) {
                $badgeType = 'specialist_' . $vehicleType;
                
                // Check if badge already exists
                $existingBadge = MechanicBadge::where('mechanic_id', $mechanic->id)
                    ->where('badge_type', $badgeType)
                    ->first();

                if (!$existingBadge && isset($badgeConfig[$badgeType])) {
                    $config = $badgeConfig[$badgeType];
                    
                    MechanicBadge::create([
                        'mechanic_id' => $mechanic->id,
                        'badge_type' => $badgeType,
                        'title' => $config['title'],
                        'description' => $config['description'],
                        'icon' => $config['icon'],
                        'earned_at' => now(),
                    ]);
                }
            }
        }

        // Check for "Specialist All Motors" badge
        if ($completedOrders->count() >= 3 && 
            $completedOrders->filter(fn($count) => $count >= $specialistThreshold)->count() >= 3) {
            
            $existingBadge = MechanicBadge::where('mechanic_id', $mechanic->id)
                ->where('badge_type', 'specialist_all')
                ->first();

            if (!$existingBadge) {
                $config = $badgeConfig['specialist_all'];
                
                MechanicBadge::create([
                    'mechanic_id' => $mechanic->id,
                    'badge_type' => 'specialist_all',
                    'title' => $config['title'],
                    'description' => $config['description'],
                    'icon' => $config['icon'],
                    'earned_at' => now(),
                ]);
            }
        }
    }

    /**
     * Award monthly top mechanic badge.
     */
    public function awardMonthlyTopMechanicBadge(): void
    {
        $topMechanic = User::mechanics()
            ->withSum(['points as monthly_points' => function ($query) {
                $query->whereMonth('earned_at', now()->month)
                      ->whereYear('earned_at', now()->year);
            }], 'points')
            ->orderByDesc('monthly_points')
            ->first();

        if ($topMechanic && ($topMechanic->getAttribute('monthly_points') ?? 0) > 0) {
            $badgeConfig = MechanicBadge::getBadgeConfig();
            $config = $badgeConfig['mechanic_of_month'];
            
            MechanicBadge::create([
                'mechanic_id' => $topMechanic->id,
                'badge_type' => 'mechanic_of_month',
                'title' => $config['title'],
                'description' => $config['description'],
                'icon' => $config['icon'],
                'earned_at' => now(),
            ]);
        }
    }

    /**
     * Handle status change events.
     */
    public function handleStatusChange(WorkOrder $workOrder, string $oldStatus): void
    {
        // This can be extended to handle specific status change events
        // For example, when status changes from 'dikerjakan' to 'pengecekan'
    }
}