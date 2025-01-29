<?php

namespace App\Policies;

use App\Models\Agreements;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AgreementPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('agreement_read');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Agreements $agreements): bool
    {
        return $user->hasPermissionTo('agreement_read');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('agreement_create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Agreements $agreements): bool
    {
        return $user->hasPermissionTo('agreement_update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Agreements $agreements): bool
    {
        return $user->hasPermissionTo('agreement_delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Agreements $agreements): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Agreements $agreements): bool
    {
        //
    }
}
