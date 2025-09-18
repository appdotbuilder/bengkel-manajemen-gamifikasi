<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LeaderboardController extends Controller
{
    /**
     * Display the leaderboard.
     */
    public function index(Request $request)
    {
        $period = $request->get('period', 'monthly');
        
        $query = User::mechanics()->where('is_active', true);
        
        switch ($period) {
            case 'daily':
                $query->withSum(['points as period_points' => function ($q) {
                    $q->whereDate('earned_at', now()->toDateString());
                }], 'points');
                break;
            case 'weekly':
                $query->withSum(['points as period_points' => function ($q) {
                    $q->whereBetween('earned_at', [now()->startOfWeek(), now()->endOfWeek()]);
                }], 'points');
                break;
            case 'monthly':
            default:
                $query->withSum(['points as period_points' => function ($q) {
                    $q->whereMonth('earned_at', now()->month)
                      ->whereYear('earned_at', now()->year);
                }], 'points');
                break;
        }
        
        $mechanics = $query
            ->with(['badges' => function ($query) {
                $query->latest()->take(3);
            }])
            ->orderByDesc('period_points')
            ->get()
            ->map(function ($mechanic, $index) {
                $mechanic->setAttribute('rank', $index + 1);
                return $mechanic;
            });
        
        return Inertia::render('workshop/leaderboard', [
            'mechanics' => $mechanics,
            'period' => $period,
        ]);
    }
}