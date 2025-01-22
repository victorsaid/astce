<?php

namespace App\Policies;

use App\Models\Associate;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AssociatePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('associate_read');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Associate $associate): bool
    {
        return $user->hasPermissionTo('associate_read');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('associate_create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Associate $associate): bool
    {
        return $user->hasPermissionTo('associate_update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Associate $associate): bool
    {
        return $user->hasPermissionTo('associate_delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Associate $associate): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Associate $associate): bool
    {
        //
    }
}
