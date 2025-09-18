<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\WorkOrder
 *
 * @property int $id
 * @property string $wo_number
 * @property int $customer_id
 * @property int $vehicle_id
 * @property int|null $assigned_mechanic_id
 * @property string $status
 * @property string $service_type
 * @property string|null $complaint
 * @property string|null $diagnosis
 * @property string|null $work_done
 * @property string|null $additional_findings
 * @property float $estimated_cost
 * @property float $final_cost
 * @property bool $approved_by_head
 * @property bool $no_revision
 * @property int|null $customer_rating
 * @property string|null $customer_feedback
 * @property bool $overtime_work
 * @property float $overtime_hours
 * @property \Illuminate\Support\Carbon|null $started_at
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\Customer $customer
 * @property-read \App\Models\Vehicle $vehicle
 * @property-read \App\Models\User|null $assignedMechanic
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MechanicPoint> $mechanicPoints
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|WorkOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkOrder whereWoNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkOrder whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkOrder whereVehicleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkOrder whereAssignedMechanicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkOrder whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkOrder whereServiceType($value)
 * @method static \Database\Factories\WorkOrderFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class WorkOrder extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'wo_number',
        'customer_id',
        'vehicle_id',
        'assigned_mechanic_id',
        'status',
        'service_type',
        'complaint',
        'diagnosis',
        'work_done',
        'additional_findings',
        'estimated_cost',
        'final_cost',
        'approved_by_head',
        'no_revision',
        'customer_rating',
        'customer_feedback',
        'overtime_work',
        'overtime_hours',
        'started_at',
        'completed_at',
        'approved_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'customer_id' => 'integer',
        'vehicle_id' => 'integer',
        'assigned_mechanic_id' => 'integer',
        'estimated_cost' => 'decimal:2',
        'final_cost' => 'decimal:2',
        'approved_by_head' => 'boolean',
        'no_revision' => 'boolean',
        'customer_rating' => 'integer',
        'overtime_work' => 'boolean',
        'overtime_hours' => 'decimal:2',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'approved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the customer for this work order.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the vehicle for this work order.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Get the assigned mechanic for this work order.
     */
    public function assignedMechanic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_mechanic_id');
    }

    /**
     * Get the mechanic points associated with this work order.
     */
    public function mechanicPoints(): HasMany
    {
        return $this->hasMany(MechanicPoint::class);
    }

    /**
     * Generate a unique work order number.
     */
    public static function generateWoNumber(): string
    {
        $date = now()->format('Ymd');
        $lastOrder = static::whereDate('created_at', now()->toDateString())
            ->orderBy('created_at', 'desc')
            ->first();
        
        $sequence = $lastOrder ? (intval(substr($lastOrder->wo_number, -3)) + 1) : 1;
        
        return 'WO' . $date . str_pad((string) $sequence, 3, '0', STR_PAD_LEFT);
    }
}