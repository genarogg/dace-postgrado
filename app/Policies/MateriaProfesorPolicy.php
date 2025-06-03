<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MateriaProfesor;
use Illuminate\Auth\Access\HandlesAuthorization;

class MateriaProfesorPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_materia::profesor');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MateriaProfesor $materiaProfesor): bool
    {
        return $user->can('view_materia::profesor');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_materia::profesor');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MateriaProfesor $materiaProfesor): bool
    {
        return $user->can('update_materia::profesor');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MateriaProfesor $materiaProfesor): bool
    {
        return $user->can('delete_materia::profesor');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_materia::profesor');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, MateriaProfesor $materiaProfesor): bool
    {
        return $user->can('force_delete_materia::profesor');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_materia::profesor');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, MateriaProfesor $materiaProfesor): bool
    {
        return $user->can('restore_materia::profesor');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_materia::profesor');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, MateriaProfesor $materiaProfesor): bool
    {
        return $user->can('replicate_materia::profesor');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_materia::profesor');
    }

    public function audit(User $user): bool
    {
        return $user->can('audit_materia_profesor');
    }

    public function restoreAudit(User $user): bool
    {
        return $user->can('restore_audit_materia_profesor');
    }
}
