<?php

namespace App\Http\Controllers;

use App\Models\Dashboard;
use Illuminate\Http\Request;
use App\Http\Requests\DashboardRequest;

class DashboardController extends Controller
{
    public function index()
    {
        $dashBoards = Dashboard::all();

        return inertia('Dashboard/Index', [
            'dashboards' => $dashBoards
        ]);
    }

    public function create()
    {
        return inertia('Dashboard/Create');
    }

    public function store(DashboardRequest $request)
    {
        // $dashboard = auth()->user()->dashboards()->create($request->validated()); DODAĆ PO WPROWADZENIU AUTHA
        $validated = $request->validated();
        $validated['created_by'] = 21;

        $dashboard = Dashboard::create($validated);

        return redirect()->route('dashboard.index')
            ->with('success', 'Dashboard created successfully.');
    }

    public function show(Dashboard $dashboard)
    {
        return inertia('Dashboard/Show', [
            'dashboard' => $dashboard
        ]);
    }

    public function edit(Dashboard $dashboard)
    {
        return inertia('Dashboard/Edit', [
            'dashboard' => $dashboard
        ]);
    }

    public function update(DashboardRequest $request, Dashboard $dashboard)
    {
        $validated = $request->validated();
        $dashboard->update($validated);

        return redirect()->route('dashboard.index')
            ->with('success', 'Dashboard updated successfully.');
    }

    public function destroy(Dashboard $dashboard)
    {
        $dashboard->delete();

        return redirect()->route('dashboard.index')
            ->with('success', 'Dashboard deleted successfully.');
    }
}
