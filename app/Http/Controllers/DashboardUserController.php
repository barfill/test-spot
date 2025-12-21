<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dashboard;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Policies\DashboardPolicy;
use Illuminate\Support\Facades\Auth;

class DashboardUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($locale, $dashboardId)
    {
        $dashboard = Dashboard::findOrFail($dashboardId);
        $this->authorize('update', $dashboard);

        $usersCollection = User::dashboardUsersAndNoAssignedStudentsWithMembershipFlag($dashboardId)->get();

        return inertia('DashboardUser/Index', [
            'locale' => $locale,
            'dashboard' => $dashboard,
            'usersCollection' => $usersCollection,
            'translations' => [
                'dashboard' => __('app.dashboard'),
                'dashboards' => __('app.dashboards'),
                'users' => __('app.users'),
                'user' => __('app.user'),
                'manage' => __('app.manage'),
                'search_users' => __('app.search_users'),
                'assign_users' => __('app.assign_users'),
                'assigned_users' => __('app.assigned_users'),
                'create_user' => __('app.create_user'),
                'my_dashboards' => __('app.my_dashboards'),
                'create_dashboard' => __('app.create_dashboard'),
                'assigned_dashboards' => __('app.assigned_dashboards'),
                'created_dashboards' => __('app.created_dashboards'),
                'created_by' => __('app.created_by'),
                'no_images' => __('app.no_images'),
                'create' => __('app.create'),
                'name' => __('app.name'),
                'description' => __('app.description'),
                'edit' => __('app.edit'),
                'delete' => __('app.delete'),
                'sign_in' => __('auth.sign_in'),
                'sign_out' => __('auth.sign_out'),
                'edit_profile' => __('app.edit_profile'),
                'users' => __('app.users')
            ]
        ]);
    }

    public function update(Request $request, $locale, $dashboardId, $user)
    {
        $dashboard = Dashboard::findOrFail($dashboardId);
        $this->authorize('update', $dashboard);

        $userModel = User::findOrFail($user);
        $action = $request->input('action');

        if ($action === 'attach') {
            $userModel->userDashboards()->syncWithoutDetaching([$dashboardId]);
            Log::info("User {$user} added to dashboard {$dashboardId}");
            return back();
        } elseif ($action === 'detach') {
            $userModel->userDashboards()->detach($dashboardId);
            Log::info("User {$user} removed from dashboard {$dashboardId}");
            return back();
        }

        Log::warning("Invalid action '{$action}' for user {$user} and dashboard {$dashboardId}");
        return response()->noContent(400);
    }
}
