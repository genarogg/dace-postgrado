<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CarreraMateria;
use Illuminate\Auth\Access\HandlesAuthorization;

class CarreraMateriaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_carrera::materia');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CarreraMateria $carreraMateria): bool
    {
        return $user->can('view_carrera::materia');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_carrera::materia');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CarreraMateria $carreraMateria): bool
    {
        return $user->can('update_carrera::materia');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CarreraMateria $carreraMateria): bool
    {
        return $user->can('delete_carrera::materia');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_carrera::materia');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, CarreraMateria $carreraMateria): bool
    {
        return $user->can('force_delete_carrera::materia');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_carrera::materia');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, CarreraMateria $carreraMateria): bool
    {
        return $user->can('restore_carrera::materia');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_carrera::materia');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, CarreraMateria $carreraMateria): bool
    {
        return $user->can('replicate_carrera::materia');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_carrera::materia');
    }

    public function audit(User $user): bool
    {
        return $user->can('audit_materia');
    }

    public function restoreAudit(User $user): bool
    {
        return $user->can('restore_audit_materia');
    }
}
