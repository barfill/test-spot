<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dashboard;
use App\Models\User;

class DashboardUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($locale, $dashboardId)
    {
        $dashboard = Dashboard::findOrFail($dashboardId);
        $dashboardUsers = $dashboard->users()->get();
        $notAssignedStudents = User::where('type', 'student')
            ->whereNotIn('id', $dashboardUsers->pluck('id'))
            ->get();

        return inertia('DashboardUser/Index', [
            'locale' => $locale,
            'dashboard' => $dashboard,
            'dashboardUsers' => $dashboardUsers,
            'students' => $notAssignedStudents,
            'translations' => [
                'dashboard' => __('app.dashboard'),
                'dashboards' => __('app.dashboards'),
                'users' => __('app.users'),
                'user' => __('app.user'),
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
                'edit_profile' => __('app.edit_profile')
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
