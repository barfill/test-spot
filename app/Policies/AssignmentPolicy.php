<?php

namespace App\Policies;

use App\Models\Assignment;
use App\Models\Dashboard;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AssignmentPolicy
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
    public function view(User $user, Dashboard $dashboard, Assignment $assignment): bool
    {
        return in_array($user->id, [...$dashboard->users->pluck('id')->toArray(), $dashboard->created_by]);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Dashboard $dashboard): bool
    {
        return $user->id === $dashboard->created_by;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Dashboard $dashboard, Assignment $assignment): bool
    {
        return $user->id === $dashboard->created_by;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Dashboard $dashboard, Assignment $assignment): bool
    {
        return $user->id === $dashboard->created_by;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Assignment $assignment): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Assignment $assignment): bool
    {
        return true;
    }
}
