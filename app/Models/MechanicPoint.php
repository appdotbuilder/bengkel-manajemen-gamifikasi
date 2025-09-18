<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\MechanicPoint
 *
 * @property int $id
 * @property int $mechanic_id
 * @property int|null $work_order_id
 * @property string $point_type
 * @property int $points
 * @property string $description
 * @property \Illuminate\Support\Carbon $earned_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\User $mechanic
 * @property-read \App\Models\WorkOrder|null $workOrder
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|MechanicPoint newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MechanicPoint newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MechanicPoint query()
 * @method static \Illuminate\Database\Eloquent\Builder|MechanicPoint whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MechanicPoint whereMechanicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MechanicPoint whereWorkOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MechanicPoint wherePointType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MechanicPoint wherePoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MechanicPoint whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MechanicPoint whereEarnedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MechanicPoint whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MechanicPoint whereUpdatedAt($value)
 * @method static \Database\Factories\MechanicPointFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class MechanicPoint extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'mechanic_id',
        'work_order_id',
        'point_type',
        'points',
        'description',
        'earned_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'mechanic_id' => 'integer',
        'work_order_id' => 'integer',
        'points' => 'integer',
        'earned_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the mechanic that earned this point.
     */
    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mechanic_id');
    }

    /**
     * Get the work order associated with this point.
     */
    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    /**
     * Point values configuration.
     */
    public static function getPointValues(): array
    {
        return [
            'service_light' => 10,
            'service_heavy' => 50,
            'no_revision' => 15,
            'additional_finding' => 20,
            'overtime' => 5, // per hour
            'customer_rating' => 25, // for 4-5 stars
            'repeat_customer' => 30,
            'attendance_daily' => 2,
            'attendance_monthly' => 50,
        ];
    }

    /**
     * Point type descriptions.
     */
    public static function getPointTypeDescriptions(): array
    {
        return [
            'service_light' => 'Menyelesaikan servis ringan',
            'service_heavy' => 'Menyelesaikan servis berat',
            'no_revision' => 'Kerja rapi tanpa revisi',
            'additional_finding' => 'Menemukan kerusakan tambahan',
            'overtime' => 'Kerja lembur',
            'customer_rating' => 'Rating pelanggan 4-5 bintang',
            'repeat_customer' => 'Pelanggan repeat order',
            'attendance_daily' => 'Hadir tepat waktu',
            'attendance_monthly' => 'Tidak ada absen sebulan',
        ];
    }
}