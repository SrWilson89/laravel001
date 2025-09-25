<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController; // Agrega esta línea para el controlador de Home

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Rutas de autenticación de Laravel
Auth::routes();

// Rutas protegidas por el middleware 'auth'
// Solo accesibles para usuarios que han iniciado sesión
Route::middleware(['auth'])->group(function () {
    // La ruta de inicio ahora usa el controlador de notas y está protegida
    Route::get('/', [NoteController::class, 'index'])->name('notes.index');
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Rutas para el perfil del usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Rutas para la gestión de notas
    Route::get('/notes/create', [NoteController::class, 'create'])->name('notes.create');
    Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');
    Route::get('/notes/{note}/edit', [NoteController::class, 'edit'])->name('notes.edit');
    Route::put('/notes/{note}', [NoteController::class, 'update'])->name('notes.update');
    Route::delete('/notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');

    // Rutas para la funcionalidad de 'likes'
    Route::post('/notes/{note}/like', [LikeController::class, 'store'])->name('notes.like');
    Route::delete('/notes/{note}/unlike', [LikeController::class, 'destroy'])->name('notes.unlike');
});