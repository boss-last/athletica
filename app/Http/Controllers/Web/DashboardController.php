<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Goal;
use App\Models\UserStat;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Simuler un utilisateur connecté (plus tard on fera l'auth)
        $user = auth()->user();

        // Stats hebdomadaires
        $weeklyStats = UserStat::where('user_id', $user->id)
            ->where('period', 'weekly')
            ->latest('period_date')
            ->first();

        // Activités récentes
        $recentActivities = Activity::where('user_id', $user->id)
            ->orderBy('start_time', 'desc')
            ->take(10)
            ->get();

        // Objectifs en cours
        $activeGoals = Goal::where('user_id', $user->id)
            ->where('is_completed', 0)
            ->get();

        // Statistiques globales
        $totalStats = Activity::where('user_id', $user->id)
            ->selectRaw('
                COUNT(*) as total_activities,
                COALESCE(SUM(distance_meters), 0) as total_distance,
                COALESCE(SUM(duration_seconds), 0) as total_duration,
                COALESCE(SUM(calories_burned), 0) as total_calories
            ')
            ->first();

        // Activités par sport
        $sportsBreakdown = Activity::where('user_id', $user->id)
            ->selectRaw('sport_type, COUNT(*) as count, COALESCE(SUM(distance_meters), 0) as total_distance')
            ->groupBy('sport_type')
            ->get();

        return view('dashboard', compact(
            'user',
            'weeklyStats',
            'recentActivities',
            'activeGoals',
            'totalStats',
            'sportsBreakdown'
        ));
    }
}
