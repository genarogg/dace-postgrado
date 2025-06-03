<?php

namespace App\Policies;

use App\Models\User;
use App\Models\EstudioRealizado;
use Illuminate\Auth\Access\HandlesAuthorization;

class EstudioRealizadoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_estudio::realizado');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, EstudioRealizado $estudioRealizado): bool
    {
        return $user->can('view_estudio::realizado');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_estudio::realizado');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, EstudioRealizado $estudioRealizado): bool
    {
        return $user->can('update_estudio::realizado');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, EstudioRealizado $estudioRealizado): bool
    {
        return $user->can('delete_estudio::realizado');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_estudio::realizado');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, EstudioRealizado $estudioRealizado): bool
    {
        return $user->can('force_delete_estudio::realizado');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_estudio::realizado');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, EstudioRealizado $estudioRealizado): bool
    {
        return $user->can('restore_estudio::realizado');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_estudio::realizado');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, EstudioRealizado $estudioRealizado): bool
    {
        return $user->can('replicate_estudio::realizado');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_estudio::realizado');
    }

    public function audit(User $user): bool
    {
        return $user->can('audit_estudio::realizado');
    }

    public function restoreAudit(User $user): bool
    {
        return $user->can('restore_audit_estudio::realizado');
    }
}
