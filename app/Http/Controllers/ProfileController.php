<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Muestra el formulario de edición de perfil.
     */
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Actualiza la información del perfil del usuario.
     */
    public function update(Request $request)
    {
        // Esto asume que tienes un formulario para actualizar el nombre y email.
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $request->user()->id,
        ]);

        $request->user()->fill($validated);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return redirect()->route('profile.edit')->with('success', '¡Perfil actualizado con éxito!');
    }

    /**
     * Elimina la cuenta del usuario.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Tu cuenta ha sido eliminada.');
    }

    /**
     * 🔑 Método NUEVO: Actualiza la contraseña del usuario autenticado.
     */
    public function updatePassword(Request $request)
    {
        // 1. Validar la solicitud:
        $validated = $request->validate([
            // Verifica que la contraseña actual sea correcta.
            'current_password' => ['required', 'current_password'], 
            // Exige que la nueva contraseña y su confirmación coincidan.
            'password' => ['required', 'confirmed', Password::defaults()], 
        ], [
            'current_password.current_password' => 'La contraseña actual introducida no es correcta.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ]);

        // 2. Actualizar la contraseña en la base de datos
        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        // 3. Redireccionar de vuelta con un mensaje de éxito
        return back()->with('success', '¡Contraseña actualizada con éxito!');
    }
}