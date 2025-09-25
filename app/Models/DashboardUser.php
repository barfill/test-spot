<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DashboardUser extends Model
{
    public function scopeDashboardUsersAndNoAssignedStudentsAndFlag($query, $dashboardId) {
        return $query
            ->leftJoin('dashboard_user', function ($join) use ($dashboardId) {
                $join->on('users.id', '=', 'dashboard_user.user_id')
                     ->where('dashboard_user.dashboard_id', '=', $dashboardId);
            })
            ->where(function ($q) {
                $q->where('users.type', 'student')
                  ->orWhereNotNull('dashboard_user.dashboard_id');
            })
            ->select([
                'users.*',
                DB::raw('CASE WHEN dashboard_user.dashboard_id IS NOT NULL THEN 1 ELSE 0 END AS is_in_dashboard'),
            ])
            ->distinct();
    }
};

