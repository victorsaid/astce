<?php

namespace App\Policies;

use App\Models\AssociatedType;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AssociatedTypePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('associated_type_read');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AssociatedType $associatedType): bool
    {
        return $user->hasPermissionTo('associated_type_read');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('associated_type_create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AssociatedType $associatedType): bool
    {
        return $user->hasPermissionTo('associated_type_update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AssociatedType $associatedType): bool
    {
        return $user->hasPermissionTo('associated_type_delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AssociatedType $associatedType): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AssociatedType $associatedType): bool
    {
        //
    }
}
