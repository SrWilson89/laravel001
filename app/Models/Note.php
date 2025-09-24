<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    /**
     * The user that owns the note.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function likes()
    {
        return $this->belongsToMany(User::class, 'note_user_likes');
    }

    protected $fillable = [
        'title',
        'content',
        'color_class',
        'user_id', // Añade 'user_id' aquí
        'is_public' // Añade 'is_public' aquí
    ];
}