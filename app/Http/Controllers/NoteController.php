<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NoteDeletedNotification; // Importa la notificación

class NoteController extends Controller
{
    /**
     * Muestra la lista de notas.
     * Solo muestra las notas del usuario autenticado.
     */
    public function index()
    {
        // Obtiene todas las notas que son públicas
        $publicNotes = Note::where('is_public', true)->get();

        // Obtiene solo las notas del usuario autenticado
        $myNotes = [];
        if (Auth::check()) {
            $myNotes = Auth::user()->notes()->get();
        }

        // Combina ambas colecciones de notas en una sola
        $notes = $publicNotes->merge($myNotes)->unique('id');

        return view('notes.index', compact('notes'));
    }

    /**
     * Guarda una nueva nota en la base de datos.
     * Asigna la nota al usuario autenticado.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'color_class' => 'nullable|string',
            'is_public' => 'boolean', // Asegúrate de validar el campo
        ]);

        // Asigna la nota al usuario autenticado usando la relación
        Auth::user()->notes()->create($validatedData);

        return redirect()->route('notes.index')->with('success', 'Nota creada exitosamente.');
    }

    /**
     * Muestra el formulario para editar una nota.
     */
    public function edit(Note $note)
    {
        // Asegúrate de que solo el dueño de la nota pueda editarla
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }

        return view('notes.edit', compact('note'));
    }

    /**
     * Actualiza los datos de la nota en la base de datos.
     */
    public function update(Request $request, Note $note)
    {
        // Asegúrate de que solo el dueño de la nota pueda actualizarla
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'color_class' => 'nullable|string',
        ]);

        $note->update($request->all());

        return redirect()->route('notes.index')->with('success', 'Nota actualizada exitosamente.');
    }

/**
 * Elimina una nota de la base de datos.
 * Admins pueden eliminar cualquier nota y notificar al dueño.
 * Usuarios normales solo pueden eliminar sus propias notas.
 */
public function destroy(Note $note)
{
    if (Auth::user()->role === 'admin') {
        // Si la nota no pertenece al admin que la borra, envía la notificación
        if ($note->user_id !== Auth::id()) {
            $note->user->notify(new NoteDeletedNotification($note->title, 'Contenido inapropiado.'));
        }
        $note->delete();
        return redirect()->route('notes.index')->with('success', 'Nota eliminada exitosamente.');
    }

    // Lógica para usuarios normales
    if ($note->user_id !== Auth::id()) {
        abort(403);
    }
    $note->delete();
    return redirect()->route('notes.index')->with('success', 'Nota eliminada exitosamente.');
}

}