<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    /**
     * Almacena/archiva un marcador (solo para VIP o Admin).
     */
    public function store(Note $note)
    {
        $user = Auth::user();

        // 🚨 RESTRICCIÓN VIP: Solo VIP o Admin pueden archivar marcadores
        if ($user->role !== 'vip' && $user->role !== 'admin') {
            return back()->with('error', 'Solo los usuarios VIP o Admin pueden usar la función de Marcadores.');
        }

        $note->bookmarks()->attach($user->id);

        return back()->with('success', '¡Nota añadida a Marcadores!');
    }

    /**
     * Elimina un marcador.
     */
    public function destroy(Note $note)
    {
        $user = Auth::user();

        // No necesitamos la restricción VIP para DESHACER la acción.
        $note->bookmarks()->detach($user->id);

        return back()->with('success', '¡Nota eliminada de Marcadores!');
    }
}