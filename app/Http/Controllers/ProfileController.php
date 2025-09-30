<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information, including the photo.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $path = null;

        // 1. Validar solo la foto de perfil
        $request->validate([
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], 
        ]);
        
        // 2. Manejar la subida de la foto de perfil con manejo de excepciones
        if ($request->hasFile('profile_photo')) {
            try {
                // Intenta guardar el archivo. Si falla (por permisos), salta al catch.
                $path = $request->file('profile_photo')->store('profile-photos', 'public'); 

                if ($path === false) {
                    // Si store() devuelve false (falla sin excepción), redirige con error.
                    return Redirect::route('profile.edit')->with('error', 'Fallo al guardar la foto. Problema de permisos de escritura en el servidor.');
                }
                
                // Si llegamos aquí, la subida fue exitosa.
                
                // Eliminar la foto anterior del disco si existe
                if ($user->profile_photo_path) {
                    Storage::disk('public')->delete($user->profile_photo_path);
                }

                // Asignar la nueva ruta a la propiedad del modelo
                $user->profile_photo_path = $path;

            } catch (\Exception $e) {
                // Si hay una excepción de permisos o I/O, la capturamos.
                // Registra el error completo para debug (lo verás en el log de Laravel)
                \Log::error("Error al subir la foto de perfil: " . $e->getMessage());
                
                // Muestra un mensaje amigable al usuario
                return Redirect::route('profile.edit')->with('error', 'Error del servidor: No se pudo escribir la foto en el disco. Revisa los permisos de la carpeta storage/app/public/.');
            }
        }

        // 3. Llenar los datos validados del Form Request (name, email)
        $user->fill($request->validated()); 

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // 4. Guardar todos los cambios.
        $user->save();

        // Si $path no es nulo, significa que la subida fue exitosa.
        $successMessage = $path ? 'Perfil actualizado con la nueva foto.' : 'Perfil actualizado correctamente.';

        return Redirect::route('profile.edit')->with('success', $successMessage);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        
        Auth::logout();

        // Eliminar la foto de perfil del disco antes de borrar el usuario
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        // Eliminar la cuenta del usuario
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
