<?php

namespace App\Policies;

use App\Models\Dashboard;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DashboardPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Dashboard $dashboard): bool
    {
        return in_array($user->id, [...$dashboard->users->pluck('id')->toArray(), $dashboard->created_by]);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->type, ['admin', 'teacher']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Dashboard $dashboard): bool
    {
        return $user->id === $dashboard->created_by;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Dashboard $dashboard): bool
    {
        return $user->id === $dashboard->created_by;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Dashboard $dashboard): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Dashboard $dashboard): bool
    {
        return false;
    }
}
