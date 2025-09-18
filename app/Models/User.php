<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $role
 * @property string|null $phone
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WorkOrder> $assignedWorkOrders
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MechanicPoint> $points
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MechanicBadge> $badges
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attendance> $attendances
 * @property-read int $total_points
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User mechanics()
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'phone',
        'is_active',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the work orders assigned to this mechanic.
     */
    public function assignedWorkOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class, 'assigned_mechanic_id');
    }

    /**
     * Get the points earned by this mechanic.
     */
    public function points(): HasMany
    {
        return $this->hasMany(MechanicPoint::class, 'mechanic_id');
    }

    /**
     * Get the badges earned by this mechanic.
     */
    public function badges(): HasMany
    {
        return $this->hasMany(MechanicBadge::class, 'mechanic_id');
    }

    /**
     * Get the attendance records for this mechanic.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'mechanic_id');
    }

    /**
     * Get the total points for this mechanic.
     */
    public function getTotalPointsAttribute(): int
    {
        return $this->points()->sum('points');
    }

    /**
     * Scope a query to only include mechanics.
     */
    public function scopeMechanics($query)
    {
        return $query->whereIn('role', ['mechanic', 'head_mechanic']);
    }

    /**
     * Check if user is a mechanic.
     */
    public function isMechanic(): bool
    {
        return in_array($this->role, ['mechanic', 'head_mechanic']);
    }

    /**
     * Check if user is head mechanic.
     */
    public function isHeadMechanic(): bool
    {
        return $this->role === 'head_mechanic';
    }

    /**
     * Check if user is owner.
     */
    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}