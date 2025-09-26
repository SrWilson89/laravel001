<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            // El administrador ve todas las notas
            $notes = Note::all();
        } else {
            // Los usuarios normales y VIP ven sus propias notas (públicas y privadas) y las notas públicas de otros usuarios.
            $notes = Note::where('user_id', $user->id)
                         ->orWhere(function ($query) {
                             $query->where('is_public', true)
                                   ->where('user_id', '!=', auth()->id());
                         })
                         ->get();
        }

        // 💡 CORRECCIÓN: Apuntando a 'notes.index' para evitar el error de vista no encontrada
        return view('notes.index', [
            'notes' => $notes,
            'view_title' => '📝 Mis Notas'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('notes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'color_class' => 'nullable|string',
            'is_public' => 'nullable|boolean',
        ]);

        Note::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'content' => $validated['content'],
            'color_class' => $request->input('color_class', 'bg-default'),
            'is_public' => $request->has('is_public'),
        ]);

        return redirect()->route('notes.index')->with('success', '¡Nota creada con éxito!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Este método aún no ha sido implementado.
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $note = Note::findOrFail($id);

        if ($note->user_id !== auth()->id()) {
            abort(403);
        }

        return view('notes.edit', compact('note'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $note = Note::findOrFail($id);

        if ($note->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'color_class' => 'nullable|string',
            'is_public' => 'nullable|boolean',
        ]);

        $note->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'color_class' => $request->input('color_class', 'bg-default'),
            'is_public' => $request->has('is_public'),
        ]);

        return redirect()->route('notes.index')->with('success', '¡Nota actualizada con éxito!');
    }

    /**
     * Remove the specified resource from storage (ELIMINACIÓN PERMANENTE).
     */
    public function destroy(string $id)
    {
        $note = Note::findOrFail($id);

        if ($note->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403);
        }

        // Esto realiza la eliminación permanente si el modelo Note NO usa SoftDeletes.
        $note->delete(); 

        return redirect()->route('notes.index')->with('success', '¡Nota eliminada permanentemente!');
    }

    /**
     * Muestra una lista de las notas marcadas como favoritas por el usuario.
     */
    public function favorites()
    {
        $notes = auth()->user()->likedNotes;

        // 💡 CORRECCIÓN: Apuntando a 'notes.index' para evitar el error de vista no encontrada
        return view('notes.index', [
            'notes' => $notes,
            'view_title' => '⭐ Notas Favoritas'
        ]);
    }
}