<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class NoteController extends Controller
{
    /**
     * Muestra una lista de las notas propias del usuario.
     */
    public function index(Request $request): View
    {
        // Obtener solo las notas del usuario actual, paginadas.
        $notes = $request->user()
                         ->notes()
                         ->latest() // Ordenar por las más recientes
                         ->with(['user', 'tags']) // Cargar el usuario y los tags relacionados (Eager Loading)
                         ->paginate(15);

        return view('notes.index', [
            'notes' => $notes,
            'view_title' => '📝 Mis Notas',
        ]);
    }

    /**
     * Muestra una lista de todas las notas que son públicas (is_public = 1).
     */
    public function publicNotes(): View
    {
        // 1. Obtener todas las notas públicas.
        $notes = Note::where('is_public', 1)
                     ->latest() // Ordenar por más recientes
                     ->with(['user', 'tags']) // Cargar el usuario y los tags (necesario para la vista)
                     ->paginate(15);

        // 2. Usamos la misma vista 'notes.index' pero cambiamos el título.
        return view('notes.index', [
            'notes' => $notes,
            'view_title' => '🌐 Explorar Notas Públicas',
        ]);
    }

    /**
     * Muestra una lista de las notas que el usuario actual ha marcado como favoritas (bookmarks).
     * Requiere que el modelo User tenga la relación 'bookmarkedNotes'.
     */
    public function favorites(Request $request): View
    {
        // Obtener solo las notas marcadas como favoritas por el usuario actual.
        // Asume que la relación 'bookmarkedNotes' existe en el modelo User
        $notes = $request->user()
                         ->bookmarkedNotes() // <-- Relación many-to-many con la tabla 'note_user_bookmarks'
                         ->latest('note_user_bookmarks.created_at') // Ordenar por el momento en que se marcó el favorito
                         ->with(['user', 'tags']) // Cargar el autor original y los tags de la nota
                         ->paginate(15);

        return view('notes.index', [
            'notes' => $notes,
            'view_title' => '⭐ Mis Notas Favoritas',
        ]);
    }

    /**
     * Muestra el formulario para crear una nueva nota.
     */
    public function create(): View
    {
        $colors = [
            'bg-default', 'bg-red', 'bg-orange-red', 'bg-orange', 'bg-yellow-orange', 
            'bg-yellow', 'bg-yellow-green', 'bg-green', 'bg-blue-green', 'bg-blue', 
            'bg-blue-violet', 'bg-violet', 'bg-red-violet'
        ];
        
        $tags = Tag::all();

        return view('notes.create', compact('colors', 'tags'));
    }

    /**
     * Almacena una nueva nota en la base de datos.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validar los datos del formulario
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'color' => 'nullable|string|max:50',
            'is_public' => 'nullable|boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id', // Asegura que cada ID de tag existe
        ]);

        // 2. Crear la nota asignándola al usuario autenticado
        $note = $request->user()->notes()->create($validated);

        // 3. Adjuntar tags si existen
        if (!empty($validated['tags'])) {
            $note->tags()->attach($validated['tags']);
        }
        
        return redirect()->route('notes.index')->with('success', '¡Nota creada exitosamente!');
    }

    /**
     * Muestra el contenido de una nota específica.
     */
    public function show(Note $note): View|RedirectResponse
    {
        // Aplicar la NotePolicy para verificar si el usuario puede ver la nota
        // Esto redirigirá automáticamente a 403 si el usuario no tiene permiso.
        if (Gate::denies('view', $note)) {
            return redirect()->route('notes.index')->with('error', 'No tienes permiso para ver esa nota.');
        }

        // Cargar los tags para la vista de detalle
        $note->load('tags');
        
        return view('notes.show', [
            'note' => $note,
        ]);
    }

    /**
     * Muestra el formulario para editar una nota existente.
     */
    public function edit(Note $note): View|RedirectResponse
    {
        // Aplicar la NotePolicy para verificar si el usuario es el dueño o admin
        if (Gate::denies('update', $note)) {
             return redirect()->route('notes.index')->with('error', 'No tienes permiso para editar esta nota.');
        }

        $colors = [
            'bg-default', 'bg-red', 'bg-orange-red', 'bg-orange', 'bg-yellow-orange', 
            'bg-yellow', 'bg-yellow-green', 'bg-green', 'bg-blue-green', 'bg-blue', 
            'bg-blue-violet', 'bg-violet', 'bg-red-violet'
        ];
        
        $tags = Tag::all();
        $selectedTags = $note->tags->pluck('id')->toArray();

        return view('notes.edit', compact('note', 'colors', 'tags', 'selectedTags'));
    }

    /**
     * Actualiza la nota en la base de datos.
     */
    public function update(Request $request, Note $note): RedirectResponse
    {
        // Aplicar la NotePolicy para verificar si el usuario es el dueño o admin
        if (Gate::denies('update', $note)) {
             return redirect()->route('notes.index')->with('error', 'No tienes permiso para actualizar esta nota.');
        }

        // 1. Validar los datos del formulario
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'color' => 'nullable|string|max:50',
            'is_public' => 'nullable|boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id', // Asegura que cada ID de tag existe
        ]);

        // 2. Actualizar la nota
        $note->update($validated);

        // 3. Sincronizar tags (borra los viejos y adjunta los nuevos)
        $note->tags()->sync($validated['tags'] ?? []);

        return redirect()->route('notes.edit', $note)->with('success', '¡Nota actualizada exitosamente!');
    }

    /**
     * Elimina la nota de la base de datos.
     */
    public function destroy(Note $note): RedirectResponse
    {
        // Aplicar la NotePolicy para verificar si el usuario puede eliminar la nota
        if (Gate::denies('delete', $note)) {
             return redirect()->route('notes.index')->with('error', 'No tienes permiso para eliminar esta nota.');
        }

        // Eliminar la nota
        $note->delete();

        return redirect()->route('notes.index')->with('success', 'Nota eliminada exitosamente.');
    }
}
