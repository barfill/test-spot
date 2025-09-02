<?php

namespace App\Http\Controllers;

use App\Models\Dashboard;
use Illuminate\Http\Request;
use App\Http\Requests\DashboardRequest;

class DashboardController extends Controller
{
    public function index($locale)
    {
        $dashBoards = Dashboard::all();

        return inertia('Dashboard/Index', [
            'dashboards' => $dashBoards,
            'locale' => $locale,
            'translations' => [
                'dashboards' => __('app.dashboards'),
                'all_dashboards' => __('app.all_dashboards'),
                'no_images' => __('app.no_images'),
                'create' => __('app.create'),
                'name' => __('app.name'),
                'description' => __('app.description'),
                'edit' => __('app.edit'),
                'delete' => __('app.delete'),
            ]
        ]);
    }

    public function create($locale)
    {
        return inertia('Dashboard/Create', [
            'locale' => $locale,
            'translations' => [
                'dashboards' => __('app.dashboards'),
                'image' => __('app.image'),
                'active' => __('app.active'),
                'inactive' => __('app.inactive'),
                'create' => __('app.create'),
                'create_dashboard' => __('app.create_dashboard'),
                'name' => __('app.name'),
                'description' => __('app.description'),
                'save' => __('app.save'),
                'cancel' => __('app.cancel'),
                'required' => __('validation.required')
            ]
        ]);
    }

    public function store(DashboardRequest $request, $locale)
    {
        // $dashboard = auth()->user()->dashboards()->create($request->validated()); DODAĆ PO WPROWADZENIU AUTHA
        $validated = $request->validated();
        $validated['created_by'] = 21;

        $dashboard = Dashboard::create($validated);

        return redirect()->route('dashboard.index', ['locale' => $locale])
            ->with('success', $this->successMessage('create', 'dashboard'));
    }

    public function show($locale, Dashboard $dashboard)
    {
        return inertia('Dashboard/Show', [
            'dashboard' => $dashboard,
            'locale' => $locale,
            'translations' => [
                'dashboards' => __('app.dashboards'),
                'create' => __('app.create'),
                'dashboard' => __('app.dashboard'),
                'name' => __('app.name'),
                'description' => __('app.description'),
                'edit' => __('app.edit'),
                'delete' => __('app.delete'),
            ]
        ]);
    }

    public function edit($locale, Dashboard $dashboard)
    {
        return inertia('Dashboard/Edit', [
            'dashboard' => $dashboard,
            'locale' => $locale,
            'translations' => [
                'dashboards' => __('app.dashboards'),
                'image' => __('app.image'),
                'active' => __('app.active'),
                'inactive' => __('app.inactive'),
                'create' => __('app.create'),
                'update' => __('app.update'),
                'dashboard' => __('app.dashboard'),
                'name' => __('app.name'),
                'description' => __('app.description'),
                'edit' => __('app.edit'),
                'delete' => __('app.delete'),
            ]
        ]);
    }

    public function update(DashboardRequest $request, $locale, Dashboard $dashboard)
    {
        $validated = $request->validated();
        $dashboard->update($validated);

        return redirect()->route('dashboard.index', ['locale' => $locale])
            ->with('success', $this->successMessage('update', 'dashboard'));
    }

    public function destroy($locale, Dashboard $dashboard)
    {
        $dashboard->delete();

        return redirect()->route('dashboard.index', ['locale' => $locale])
            ->with('success', $this->successMessage('delete', 'dashboard'));
    }

    private function successMessage($action, $attribute) {
        return __("messages.$action"."_success", ['attribute' => __("app.$attribute")]);
    }
}
