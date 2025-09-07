<?php

namespace App\Http\Controllers;

use App\Models\Dashboard;
use Illuminate\Http\Request;
use App\Http\Requests\DashboardRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Policies\DashboardPolicy;


class DashboardController extends Controller
{


    public function index($locale)
    {
        $this->authorize('viewAny', Dashboard::class);

        $user = Auth::user();
        $userAssignedDashboards = $user->userDashboards()->with('owner')->get()->map(function ($d) use ($user) {
            $d->can = [
                'update' => $user->can('update', $d),
                'delete' => $user->can('delete', $d),
                'view'   => $user->can('view', $d),
            ];
            return $d;
        });

        $userCreatedDashboards = ($user->type === 'admin') || ($user->type === 'teacher') ? $user->dashboards()->with('owner')->get()->map(function ($d) use ($user) {
            $d->can = [
                'update' => $user->can('update', $d),
                'delete' => $user->can('delete', $d),
                'view'   => $user->can('view', $d),
            ];
            return $d;
        }) : null;

        return inertia('Dashboard/Index', [
            'assignedDashboards' => $userAssignedDashboards,
            'createdDashboards' => $userCreatedDashboards ?? [],
            'locale' => $locale,
            'translations' => [
                'dashboards' => __('app.dashboards'),
                'all_dashboards' => __('app.all_dashboards'),
                'my_dashboards' => __('app.my_dashboards'),
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

    public function create($locale)
    {
        $this->authorize('create', Dashboard::class);

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
                'required' => __('validation.required'),
                'sign_in' => __('auth.sign_in'),
                'sign_out' => __('auth.sign_out'),
                'edit_profile' => __('app.edit_profile')
            ]
        ]);
    }

    public function store(DashboardRequest $request, $locale)
    {
        $this->authorize('create', Dashboard::class);

        $validated = $request->validated();
        $validated['created_by'] = Auth::id();
        Dashboard::create($validated);

        return redirect()->route('dashboard.index', ['locale' => $locale])
            ->with('success', $this->successMessage('create', 'dashboard'));
    }

    public function show($locale, Dashboard $dashboard)
    {
        $this->authorize('view', $dashboard);

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
                'sign_in' => __('auth.sign_in'),
                'sign_out' => __('auth.sign_out'),
                'edit_profile' => __('app.edit_profile')
            ]
        ]);
    }

    public function edit($locale, Dashboard $dashboard)
    {
        $this->authorize('update', $dashboard);

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
                'sign_in' => __('auth.sign_in'),
                'sign_out' => __('auth.sign_out'),
                'edit_profile' => __('app.edit_profile')
            ]
        ]);
    }

    public function update(DashboardRequest $request, $locale, Dashboard $dashboard)
    {
        $this->authorize('update', $dashboard);

        $validated = $request->validated();
        $dashboard->update($validated);

        return redirect()->route('dashboard.index', ['locale' => $locale])
            ->with('success', $this->successMessage('update', 'dashboard'));
    }

    public function destroy($locale, Dashboard $dashboard)
    {
        $this->authorize('delete', $dashboard);

        $dashboard->delete();

        return redirect()->route('dashboard.index', ['locale' => $locale])
            ->with('success', $this->successMessage('delete', 'dashboard'));
    }

    private function successMessage($action, $attribute) {
        return __("messages.$action"."_success", ['attribute' => __("app.$attribute")]);
    }
}
