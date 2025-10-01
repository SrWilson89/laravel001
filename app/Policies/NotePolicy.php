<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Note;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotePolicy
{
    use HandlesAuthorization;

    /**
     * Determina si el usuario puede ver cualquier nota.
     */
    public function viewAny(User $user): bool
    {
        return true; // Todos los usuarios autenticados pueden ver la lista.
    }

    /**
     * Determina si el usuario puede ver la nota.
     * Solo si es el dueño, si la nota es pública, o si es admin.
     */
    public function view(User $user, Note $note): bool
    {
        return $user->id === $note->user_id 
            || $note->is_public 
            || $user->role === 'admin';
    }

    /**
     * Determina si el usuario puede crear notas.
     */
    public function create(User $user): bool
    {
        return true; // Todos los usuarios autenticados pueden crear notas.
    }

    /**
     * Determina si el usuario puede actualizar la nota.
     * Permitido para el dueño, admin, o si se desea, para todos (true).
     */
    public function update(User $user, Note $note): Response
    {
        // Opción segura:
        if ($user->role === 'admin' || $user->id === $note->user_id) {
            return Response::allow();
        }

        // Opción Insegura (para probar temporalmente):
        // return Response::allow(); 

        return Response::deny('No tienes permiso para editar esta nota.');
    }

    /**
     * Determina si el usuario puede eliminar la nota.
     * Solo el dueño o un admin.
     */
    public function delete(User $user, Note $note): Response
    {
        if ($user->role === 'admin') {
            return Response::allow();
        }

        return $user->id === $note->user_id
            ? Response::allow()
            : Response::deny('No tienes permiso para eliminar esta nota.');
    }

    /**
     * Determina si el usuario puede restaurar la nota (Soft Deletes).
     */
    public function restore(User $user, Note $note): bool
    {
        // Solo el dueño o un admin pueden restaurar.
        return $user->id === $note->user_id || $user->role === 'admin';
    }

    /**
     * Determina si el usuario puede eliminar permanentemente la nota.
     */
    public function forceDelete(User $user, Note $note): bool
    {
        // Solo el dueño o un admin pueden eliminar permanentemente.
        return $user->id === $note->user_id || $user->role === 'admin';
    }
}
