<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Muestra el formulario para editar el perfil del usuario.
     */
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Actualiza los datos del perfil del usuario.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validar los datos del formulario
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255', 
                Rule::unique('users')->ignore($user->id)
            ],
        ]);

        // Actualizar los datos del usuario
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->save();

        return redirect()->route('notes.index')->with('success', 'Perfil actualizado exitosamente.');
    }
}