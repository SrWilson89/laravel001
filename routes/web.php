<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| En este archivo se han fusionado tus rutas originales con las de Laravel Breeze.
| Se ha corregido la ruta /dashboard para mostrar tus notas.
|
*/

// RUTAS DE AUTENTICACIÓN DE BREEZE
// Esto incluye /login, /register, etc., y define el nombre 'login'.
require __DIR__.'/auth.php';

// RUTA DE LOGOUT
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// RUTA /home (Si sigue existiendo)
Route::get('/home', [HomeController::class, 'index'])->name('home');

// DEBE SER POST para el envío del formulario (Ruta bulk action fuera de prefix)
Route::post('/messages/bulk-action', [MessageController::class, 'bulkAction'])->name('messages.bulk_action');

// Rutas protegidas por el middleware 'auth' (Solo accesibles para usuarios que han iniciado sesión)
Route::middleware(['auth'])->group(function () {
    
    // 🔑 CORRECCIÓN: RUTA PRINCIPAL /
    // Ahora muestra la lista de notas de tu controlador
    Route::get('/', [NoteController::class, 'index'])->name('notes.index');

    // 🔑 CORRECCIÓN: RUTA /DASHBOARD
    // Ahora también muestra la lista de notas de tu controlador
    Route::get('/dashboard', [NoteController::class, 'index'])->middleware(['verified'])->name('dashboard');

    // Rutas de Perfil (CRUD, incluyendo la actualización de contraseña)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas para la gestión de notas (CRUD)
    Route::get('/notes/create', [NoteController::class, 'create'])->name('notes.create');
    Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');
    Route::get('/notes/{note}/edit', [NoteController::class, 'edit'])->name('notes.edit');
    Route::put('/notes/{note}', [NoteController::class, 'update'])->name('notes.update');
    Route::delete('/notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');

    // Rutas para la funcionalidad de 'likes' (Favoritos/Corazón)
    Route::post('/notes/{note}/like', [LikeController::class, 'store'])->name('notes.like');
    Route::delete('/notes/{note}/unlike', [LikeController::class, 'destroy'])->name('notes.unlike');
    Route::get('/notes/favorites', [NoteController::class, 'favorites'])->name('notes.favorites');

    // Rutas para Mensajería
    Route::prefix('messages')->name('messages.')->group(function () {
        // Vistas de navegación
        Route::get('/', [MessageController::class, 'index'])->name('index');
        Route::get('/sent', [MessageController::class, 'sent'])->name('sent');
        Route::get('/trash', [MessageController::class, 'trash'])->name('trash');

        // Envío de mensajes (Solo VIP/Admin)
        Route::get('/create', [MessageController::class, 'create'])->name('create');
        Route::post('/', [MessageController::class, 'store'])->name('store');
        
        // Acciones Individuales
        Route::patch('/{message}/read', [MessageController::class, 'markAsRead'])->name('read');
        Route::delete('/{message}', [MessageController::class, 'destroy'])->name('destroy'); 
        Route::post('/{id}/restore', [MessageController::class, 'restore'])->name('restore');
    });
});