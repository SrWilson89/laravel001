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
*/

// RUTA DE LOGOUT
// Permite que el formulario de cerrar sesión use la ruta 'logout'
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// DEBE SER POST para el envío del formulario
Route::post('/messages/bulk-action', [MessageController::class, 'bulkAction'])->name('messages.bulk_action');

// Rutas protegidas por el middleware 'auth' (Solo accesibles para usuarios que han iniciado sesión)
Route::middleware(['auth'])->group(function () {
    // Rutas principales
    Route::get('/', [NoteController::class, 'index'])->name('notes.index');
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Rutas de Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // 🔑 RUTA AÑADIDA: Para la actualización de contraseña
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

        // Acción Masiva (POST /messages/bulk-action)
        Route::post('/bulk-action', [MessageController::class, 'bulkAction'])->name('bulk_action');

        // Envío de mensajes (Solo VIP/Admin)
        Route::get('/create', [MessageController::class, 'create'])->name('create');
        Route::post('/', [MessageController::class, 'store'])->name('store');
        
        // Acciones Individuales
        Route::patch('/{message}/read', [MessageController::class, 'markAsRead'])->name('read');
        // El método destroy usa Soft Delete (DELETE /messages/{message})
        Route::delete('/{message}', [MessageController::class, 'destroy'])->name('destroy'); 
        // Restaurar de papelera (POST /messages/{id}/restore)
        Route::post('/{id}/restore', [MessageController::class, 'restore'])->name('restore');
    });
});