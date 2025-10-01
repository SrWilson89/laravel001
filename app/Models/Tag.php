<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    // Campos que pueden ser asignados masivamente
    protected $fillable = ['name', 'slug'];

    /**
     * Relación de muchos a muchos con el modelo Note.
     * Un Tag puede estar en muchas Notas.
     */
    public function notes()
    {
        return $this->belongsToMany(Note::class);
    }
}
