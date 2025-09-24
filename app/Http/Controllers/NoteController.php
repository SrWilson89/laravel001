<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * Muestra la lista de notas.
     */
    public function index()
    {
        $notes = Note::all();
        return view('notes.index', ['notes' => $notes]);
    }

    /**
     * Muestra el formulario para crear una nueva nota.
     */
    public function create()
    {
        return view('notes.create');
    }

    /**
     * Guarda una nueva nota en la base de datos.
     */
    public function store(Request $request)
    {
        // Validación de los datos, incluyendo el campo de color
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'color_class' => 'nullable|string',
        ]);

        // Crea una nueva nota
        Note::create($request->all());

        // Redirecciona al usuario a la página de inicio
        return redirect('/');
    }

    /**
     * Muestra el formulario para editar una nota.
     */
    public function edit($id)
    {
        $note = Note::findOrFail($id);
        return view('notes.edit', ['note' => $note]);
    }

    /**
     * Actualiza los datos de la nota en la base de datos.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'color_class' => 'nullable|string',
        ]);

        $note = Note::findOrFail($id);
        $note->update($request->all());

        return redirect('/');
    }

    /**
     * Elimina una nota de la base de datos.
     */
    public function destroy($id)
    {
        $note = Note::findOrFail($id);
        $note->delete();

        return redirect('/');
    }
}