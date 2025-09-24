<?php

namespace App\Http\Controllers;

// app/Http/Controllers/LikeController.php

use App\Models\Note;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function store(Note $note)
    {
        // El usuario debe ser VIP para dar like
        if (Auth::user()->role !== 'vip') {
            return back()->with('error', 'Solo los usuarios VIP pueden dar like.');
        }

        Auth::user()->likedNotes()->syncWithoutDetaching($note->id);

        return back()->with('success', 'Me gusta añadido.');
    }

    public function destroy(Note $note)
    {
        Auth::user()->likedNotes()->detach($note->id);

        return back()->with('success', 'Me gusta eliminado.');
    }
}