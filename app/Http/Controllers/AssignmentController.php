<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
USE App\Models\Dashboard;
use Illuminate\Http\Request;
use App\Http\Requests\AssignmentRequest;
use PhpParser\Node\Expr\Assign;

class AssignmentController extends Controller
{
    public function create($locale, Dashboard $dashboard)
    {
        $this->authorize('create', [Assignment::class, $dashboard]);

        return inertia('Assignment/Create', [
            'locale' => $locale,
            'dashboard' => $dashboard,
            'translations' => [
                'dashboards' => __('app.dashboards'),
                'image' => __('app.image'),
                'open' => __('app.open'),
                'closed' => __('app.closed'),
                'start_time' => __('app.start_time'),
                'end_time' => __('app.end_time'),
                'create' => __('app.create'),
                'create_dashboard' => __('app.create_dashboard'),
                'name' => __('app.name'),
                'description' => __('app.description'),
                'save' => __('app.save'),
                'cancel' => __('app.cancel'),
                'required' => __('validation.required'),
                'sign_in' => __('auth.sign_in'),
                'sign_out' => __('auth.sign_out'),
                'edit_profile' => __('app.edit_profile')
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AssignmentRequest $request, $locale, Dashboard $dashboard)
    {
        $this->authorize('create', [Assignment::class, $dashboard]);

        $validated = $request->validated();
        $validated['dashboard_id'] = $dashboard->id;
        Assignment::create($validated);

        return redirect()->route('dashboard.show', ['locale' => $locale, 'dashboard' => $dashboard->id])
            ->with('success', $this->successMessage('create', 'assignment'));
    }

    /**
     * Display the specified resource.
     */
    public function show($locale, Dashboard $dashboard, Assignment $assignment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($locale, Dashboard $dashboard, Assignment $assignment)
    {
        $this->authorize('update', [$dashboard, $assignment]);

        return inertia('Assignment/Edit', [
            'locale' => $locale,
            'dashboard' => $dashboard,
            'assignment' => $assignment,
            'translations' => [
                'dashboards' => __('app.dashboards'),
                'image' => __('app.image'),
                'open' => __('app.open'),
                'closed' => __('app.closed'),
                'start_time' => __('app.start_time'),
                'end_time' => __('app.end_time'),
                'create' => __('app.create'),
                'update' => __('app.update'),
                'edit' => __('app.edit'),
                'create_dashboard' => __('app.create_dashboard'),
                'create_assignment' => __('app.create_assignment'),
                'name' => __('app.name'),
                'description' => __('app.description'),
                'save' => __('app.save'),
                'cancel' => __('app.cancel'),
                'required' => __('validation.required'),
                'sign_in' => __('auth.sign_in'),
                'sign_out' => __('auth.sign_out'),
                'edit_profile' => __('app.edit_profile')
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AssignmentRequest $request, $locale, Dashboard $dashboard, Assignment $assignment)
    {
        $this->authorize('update', [$dashboard, $assignment]);

        $validated = $request->validated();

        $assignment->update($validated);

        return redirect()->route('dashboard.show', ['locale' => $locale, 'dashboard' => $dashboard->id])
            ->with('success', $this->successMessage('update', 'assignment'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($locale, Dashboard $dashboard,Assignment $assignment)
    {
        $this->authorize('delete', [$dashboard, $assignment]);

        $assignment->delete();

         return redirect()->route('dashboard.show', ['locale' => $locale, 'dashboard' => $dashboard->id])
            ->with('success', $this->successMessage('delete', 'assignment'));
    }

    private function successMessage($action, $attribute) {
        return __("messages.assignment_".$action."_success", ['attribute' => __("app.$attribute")]);
    }
}
