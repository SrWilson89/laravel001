<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class NoteController extends Controller
{
    /**
     * Define los colores disponibles para las notas.
     * Esta lista se usa en los métodos create y edit.
     * @return array
     */
    protected function getAvailableColors(): array
    {
        // Usamos clases de Tailwind CSS para el color de fondo.
        // El prefijo 'bg-' se usará en la vista.
        return [
            'bg-default',
            'bg-red',
            'bg-orange-red',
            'bg-orange',
            'bg-yellow-orange',
            'bg-yellow',
            'bg-yellow-green',
            'bg-green',
            'bg-cyan-green',
            'bg-cyan',
            'bg-blue-cyan',
            'bg-blue',
            'bg-blue-violet',
            'bg-violet',
            'bg-red-violet',
        ];
    }

    /**
     * Muestra una lista de las notas del usuario actual.
     */
    public function index(Request $request): View
    {
        $notes = $request->user()
                         ->notes()
                         ->latest()
                         ->with('tags')
                         ->paginate(15);

        return view('notes.index', [
            'notes' => $notes,
            'view_title' => 'Mis Notas',
        ]);
    }

    /**
     * Muestra las notas públicas.
     */
    public function publicNotes(): View
    {
        $notes = Note::query()
                     ->where('is_public', true)
                     ->latest()
                     ->with(['user', 'tags'])
                     ->paginate(15);

        return view('notes.index', [
            'notes' => $notes,
            'view_title' => 'Notas Públicas',
        ]);
    }

    /**
     * Muestra solo las notas marcadas como favoritas por el usuario.
     */
    public function favorites(Request $request): View
    {
        $notes = $request->user()
                         ->bookmarkedNotes()
                         ->latest('note_user_bookmarks.created_at')
                         ->with(['user', 'tags'])
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
        $tags = Tag::all();
        $colors = $this->getAvailableColors(); // Obtener la lista de colores

        return view('notes.create', [
            'tags' => $tags,
            'colors' => $colors, // Pasar la lista de colores a la vista
        ]);
    }

    /**
     * Almacena una nota recién creada en el almacenamiento.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'color' => 'nullable|string|max:255',
            'is_public' => 'boolean',
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'exists:tags,id',
        ]);

        $note = $request->user()->notes()->create($validated);
        
        // Sincronizar tags
        if (isset($validated['tag_ids'])) {
            $note->tags()->sync($validated['tag_ids']);
        }

        return redirect()->route('notes.index')->with('success', 'Nota creada exitosamente.');
    }

    /**
     * Muestra la nota especificada.
     */
    public function show(Note $note): View
    {
        if (Gate::denies('view', $note)) {
            abort(403);
        }

        $note->load(['user', 'tags']);
        
        return view('notes.show', [
            'note' => $note,
        ]);
    }

    /**
     * Muestra el formulario para editar una nota existente.
     */
    public function edit(Note $note): View|RedirectResponse
    {
        if (Gate::denies('update', $note)) {
            return redirect()->route('notes.index')->with('error', 'No tienes permiso para editar esta nota.');
        }

        // Obtener todos los tags disponibles
        $tags = Tag::all();

        // Obtener los IDs de los tags actualmente seleccionados
        $selected_tags = $note->tags->pluck('id')->toArray();

        // Obtener la lista de colores disponibles
        $colors = $this->getAvailableColors();

        return view('notes.edit', [
            'note' => $note,
            'tags' => $tags,
            'selected_tags' => $selected_tags,
            'colors' => $colors, // <-- ¡Ahora pasamos los colores!
        ]);
    }

    /**
     * Actualiza la nota especificada en el almacenamiento.
     */
    public function update(Request $request, Note $note): RedirectResponse
    {
        if (Gate::denies('update', $note)) {
            return redirect()->route('notes.index')->with('error', 'No tienes permiso para actualizar esta nota.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'color' => 'nullable|string|max:255',
            'is_public' => 'boolean',
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'exists:tags,id',
        ]);

        $note->update($validated);
        
        $note->tags()->sync($validated['tag_ids'] ?? []);

        return redirect()->route('notes.show', $note)->with('success', 'Nota actualizada exitosamente.');
    }

    /**
     * Elimina la nota especificada del almacenamiento.
     */
    public function destroy(Note $note): RedirectResponse
    {
        if (Gate::denies('delete', $note)) {
            return redirect()->route('notes.index')->with('error', 'No tienes permiso para eliminar esta nota.');
        }

        $note->delete();

        return redirect()->route('notes.index')->with('success', 'Nota eliminada exitosamente.');
    }
}
