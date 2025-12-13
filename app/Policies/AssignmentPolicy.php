<?php

namespace App\Policies;

use App\Models\Assignment;
use App\Models\Dashboard;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AssignmentPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Dashboard $dashboard, Assignment $assignment): bool
    {
        return in_array($user->id, [...$dashboard->users->pluck('id')->toArray(), $dashboard->created_by]);
    }

    public function create(User $user, Dashboard $dashboard): bool
    {
        return $user->id === $dashboard->created_by;
    }

    public function update(User $user, Dashboard $dashboard, Assignment $assignment): bool
    {
        return $user->id === $dashboard->created_by;
    }

    public function delete(User $user, Dashboard $dashboard, Assignment $assignment): bool
    {
        return $user->id === $dashboard->created_by;
    }

    public function restore(User $user, Assignment $assignment): bool
    {
        return true;
    }

    public function forceDelete(User $user, Assignment $assignment): bool
    {
        return true;
    }
}
