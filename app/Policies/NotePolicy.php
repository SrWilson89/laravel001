<?php

namespace App\Policies;

use App\Models\Note;
use App\Models\User;

class NotePolicy
{
    /**
     * Determina si el usuario puede ver la nota.
     */
    public function view(User $user, Note $note): bool
    {
        // Un usuario puede ver su propia nota O si la nota es pública.
        return $user->is($note->user) || $note->is_public;
    }

    /**
     * Determina si el usuario puede eliminar la nota.
     */
    public function delete(User $user, Note $note): bool
    {
        // Un administrador puede eliminar cualquier nota.
        // Un usuario normal solo puede eliminar sus propias notas.
        return $user->role === 'admin' || $user->is($note->user);
    }
}