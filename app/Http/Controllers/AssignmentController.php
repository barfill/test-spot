<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
USE App\Models\Dashboard;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Assign;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($locale, Dashboard $dashboard)
    {
        $this->authorize('viewAny', Assignment::class);

        $assignments = $dashboard->assignments()
            ->latest('end_time')
            ->get();

        return inertia('Assignment/Index', [
            'assignments' => $assignments,
            'locale' => $locale,
            'translations' => [
                'dashboard' => __('app.dashboard'),
                'dashboards' => __('app.dashboards'),
                'all_dashboards' => __('app.all_dashboards'),
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
    public function show(Assignment $assignment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Assignment $assignment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Assignment $assignment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Assignment $assignment)
    {
        //
    }
}
