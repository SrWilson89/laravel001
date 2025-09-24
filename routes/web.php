<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // Agrega esta línea para usar Auth::routes()
use App\Http\Controllers\NoteController;
use App\Http\Controllers\LikeController;

// La ruta de inicio ahora usa el controlador de notas directamente
Route::get('/', [NoteController::class, 'index'])->name('notes.index');

// Rutas para la gestión de notas
// Usamos named routes, una buena práctica en Laravel
Route::get('/notes/create', [NoteController::class, 'create'])->name('notes.create');
Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');
Route::get('/notes/{note}/edit', [NoteController::class, 'edit'])->name('notes.edit');
Route::put('/notes/{note}', [NoteController::class, 'update'])->name('notes.update');
Route::delete('/notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');

// Rutas para la funcionalidad de 'likes' (protegidas por middleware de autenticación)
Route::post('/notes/{note}/like', [LikeController::class, 'store'])->middleware('auth')->name('notes.like');
Route::delete('/notes/{note}/unlike', [LikeController::class, 'destroy'])->middleware('auth')->name('notes.unlike');

// Esto registra todas las rutas de autenticación (login, register, logout, etc.)
Auth::routes();
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
