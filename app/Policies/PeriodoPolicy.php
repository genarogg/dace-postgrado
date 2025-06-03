<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Periodo;
use Illuminate\Auth\Access\HandlesAuthorization;

class PeriodoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_periodo');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Periodo $periodo): bool
    {
        return $user->can('view_periodo');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_periodo');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Periodo $periodo): bool
    {
        return $user->can('update_periodo');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Periodo $periodo): bool
    {
        return $user->can('delete_periodo');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_periodo');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Periodo $periodo): bool
    {
        return $user->can('force_delete_periodo');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_periodo');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Periodo $periodo): bool
    {
        return $user->can('restore_periodo');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_periodo');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Periodo $periodo): bool
    {
        return $user->can('replicate_periodo');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_periodo');
    }

    public function audit(User $user): bool
    {
        return $user->can('audit_periodo');
    }

    public function restoreAudit(User $user): bool
    {
        return $user->can('restore_audit_periodo');
    }
}
