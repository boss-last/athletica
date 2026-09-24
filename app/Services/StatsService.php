<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\Goal;
use Carbon\Carbon;

class StatsService
{
    public function getWeeklyStats($userId)
    {
        return Activity::where('user_id', $userId)
            ->whereBetween('start_time', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->selectRaw('
                COUNT(*) as total,
                COALESCE(SUM(distance_meters), 0) as total_distance,
                COALESCE(SUM(duration_seconds), 0) as total_duration,
                COALESCE(SUM(calories_burned), 0) as total_calories,
                COALESCE(AVG(avg_heart_rate), 0) as avg_hr
            ')
            ->first();
    }

    public function getMonthlyStats($userId)
    {
        return Activity::where('user_id', $userId)
            ->whereBetween('start_time', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->selectRaw('
                COUNT(*) as total,
                COALESCE(SUM(distance_meters), 0) as total_distance,
                COALESCE(SUM(duration_seconds), 0) as total_duration
            ')
            ->first();
    }

    public function getSportsBreakdown($userId)
    {
        return Activity::where('user_id', $userId)
            ->selectRaw('sport_type, COUNT(*) as count, COALESCE(SUM(distance_meters), 0) as total_distance')
            ->groupBy('sport_type')
            ->get();
    }
}
