<?php

namespace App\Policies;

use App\Models\User;
use App\Models\InscripcionMateria;
use Illuminate\Auth\Access\HandlesAuthorization;

class InscripcionMateriaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_inscripcion::materia');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, InscripcionMateria $inscripcionMateria): bool
    {
        return $user->can('view_inscripcion::materia');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_inscripcion::materia');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, InscripcionMateria $inscripcionMateria): bool
    {
        return $user->can('update_inscripcion::materia');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, InscripcionMateria $inscripcionMateria): bool
    {
        return $user->can('delete_inscripcion::materia');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_inscripcion::materia');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, InscripcionMateria $inscripcionMateria): bool
    {
        return $user->can('force_delete_inscripcion::materia');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_inscripcion::materia');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, InscripcionMateria $inscripcionMateria): bool
    {
        return $user->can('restore_inscripcion::materia');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_inscripcion::materia');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, InscripcionMateria $inscripcionMateria): bool
    {
        return $user->can('replicate_inscripcion::materia');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_inscripcion::materia');
    }

    public function audit(User $user): bool
    {
        return $user->can('audit_inscripcion_materia');
    }

    public function restoreAudit(User $user): bool
    {
        return $user->can('restore_audit_inscripcion_materia');
    }
}
