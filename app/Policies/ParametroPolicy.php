<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Parametro;
use Illuminate\Auth\Access\HandlesAuthorization;

class ParametroPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_parametro');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Parametro $parametro): bool
    {
        return $user->can('view_parametro');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_parametro');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Parametro $parametro): bool
    {
        return $user->can('update_parametro');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Parametro $parametro): bool
    {
        return $user->can('delete_parametro');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_parametro');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Parametro $parametro): bool
    {
        return $user->can('force_delete_parametro');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_parametro');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Parametro $parametro): bool
    {
        return $user->can('restore_parametro');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_parametro');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Parametro $parametro): bool
    {
        return $user->can('replicate_parametro');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_parametro');
    }

    public function audit(User $user): bool
    {
        return $user->can('audit_parametro');
    }

    public function restoreAudit(User $user): bool
    {
        return $user->can('restore_audit_parametro');
    }
}
