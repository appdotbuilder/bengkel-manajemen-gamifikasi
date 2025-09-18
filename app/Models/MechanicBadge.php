<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\MechanicBadge
 *
 * @property int $id
 * @property int $mechanic_id
 * @property string $badge_type
 * @property string $title
 * @property string $description
 * @property string|null $icon
 * @property \Illuminate\Support\Carbon $earned_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\User $mechanic
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|MechanicBadge newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MechanicBadge newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MechanicBadge query()
 * @method static \Illuminate\Database\Eloquent\Builder|MechanicBadge whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MechanicBadge whereMechanicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MechanicBadge whereBadgeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MechanicBadge whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MechanicBadge whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MechanicBadge whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MechanicBadge whereEarnedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MechanicBadge whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MechanicBadge whereUpdatedAt($value)
 * @method static \Database\Factories\MechanicBadgeFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class MechanicBadge extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'mechanic_id',
        'badge_type',
        'title',
        'description',
        'icon',
        'earned_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'mechanic_id' => 'integer',
        'earned_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the mechanic that earned this badge.
     */
    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mechanic_id');
    }

    /**
     * Badge configuration.
     */
    public static function getBadgeConfig(): array
    {
        return [
            'mechanic_of_month' => [
                'title' => 'Mekanik Terbaik Bulan Ini',
                'description' => 'Mekanik dengan poin tertinggi bulan ini',
                'icon' => '🏆',
            ],
            'specialist_matic' => [
                'title' => 'Spesialis Matic',
                'description' => 'Telah menyelesaikan 50 servis motor matic',
                'icon' => '⚙️',
            ],
            'specialist_2tak' => [
                'title' => 'Spesialis 2 Tak',
                'description' => 'Telah menyelesaikan 50 servis motor 2 tak',
                'icon' => '🔧',
            ],
            'specialist_4tak' => [
                'title' => 'Spesialis 4 Tak',
                'description' => 'Telah menyelesaikan 50 servis motor 4 tak',
                'icon' => '🛠️',
            ],
            'specialist_all' => [
                'title' => 'Spesialis Semua Motor',
                'description' => 'Ahli dalam semua jenis motor',
                'icon' => '🌟',
            ],
        ];
    }
}