<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * Almacena/archiva un favorito (solo para VIP o Admin).
     */
    public function store(Note $note)
    {
        $user = Auth::user();

        // 🚨 RESTRICCIÓN VIP: Solo VIP o Admin pueden archivar favoritos
        if ($user->role !== 'vip' && $user->role !== 'admin') {
            return back()->with('error', 'Solo los usuarios VIP o Admin pueden usar la función de Favoritos.');
        }

        $note->likes()->attach($user->id);

        return back()->with('success', '¡Nota añadida a Favoritos!');
    }

    /**
     * Elimina un favorito.
     */
    public function destroy(Note $note)
    {
        $user = Auth::user();

        // No necesitamos la restricción VIP para DESHACER la acción.
        $note->likes()->detach($user->id);

        return back()->with('success', '¡Nota eliminada de Favoritos!');
    }
}