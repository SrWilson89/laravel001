<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\NoteController;

// Muestra el formulario para crear una nota
Route::get('/notes/create', [NoteController::class, 'create']);

// Procesa el formulario y guarda la nota
Route::post('/notes', [NoteController::class, 'store']);

Route::get('/', [NoteController::class, 'index']); // Agregamos esta línea

// Muestra el formulario para editar una nota
Route::get('/notes/{id}/edit', [NoteController::class, 'edit']);

// Actualiza los datos de la nota
Route::put('/notes/{id}', [NoteController::class, 'update']);

// Elimina una nota de la base de datos
Route::delete('/notes/{id}', [NoteController::class, 'destroy']);